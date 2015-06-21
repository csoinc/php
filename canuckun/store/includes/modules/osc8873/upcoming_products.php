<?php
/**
 * upcoming_products module
 *
 * @package modules
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: upcoming_products.php 6424 2007-05-31 05:59:21Z ajeh $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
 
// initialize vars
$categories_products_id_list = '';
$list_of_products = '';
$expected_query = '';

$display_limit = zen_get_upcoming_date_range();
$limit_clause = "  order by " . EXPECTED_PRODUCTS_FIELD . " " . EXPECTED_PRODUCTS_SORT . "
                   limit " . MAX_DISPLAY_UPCOMING_PRODUCTS;

if ( (($manufacturers_id > 0 && $_GET['filter_id'] == 0) || $_GET['music_genre_id'] > 0 || $_GET['record_company_id'] > 0) || (!isset($new_products_category_id) || $new_products_category_id == '0') ) {
  $expected_query = "select p.products_id, p.products_image, pd.products_name, products_date_available as date_expected, p.master_categories_id
                     from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd
                     where p.products_id = pd.products_id
                     and p.products_status = 1
                     and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'" .
                     $display_limit .
                     $limit_clause;
} else {
  // get all products and cPaths in this subcat tree
  $productsInCategory = zen_get_categories_products_list( (($manufacturers_id > 0 && $_GET['filter_id'] > 0) ? zen_get_generated_category_path_rev($_GET['filter_id']) : $cPath), false, true, 0, $display_limit);

  if (is_array($productsInCategory) && sizeof($productsInCategory) > 0) {
    // build products-list string to insert into SQL query
    foreach($productsInCategory as $key => $value) {
      $list_of_products .= $key . ', ';
    }
    $list_of_products = substr($list_of_products, 0, -2); // remove trailing comma

    $expected_query = "select p.products_id, p.products_image, pd.products_name, products_date_available as date_expected, p.master_categories_id
                       from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd
                       where p.products_id = pd.products_id
                       and p.products_id in (" . $list_of_products . ")
                       and pd.language_id = '" . (int)$_SESSION['languages_id'] . "' " .
                       $display_limit .
                       $limit_clause;
  }
}

$list_box_contents = array();
$title = '';
$row = 0;
$col = 0;

if ($expected_query != '') $expected = $db->ExecuteRandomMulti($expected_query, 6);//MAX_DISPLAY_SEARCH_RESULTS_UPCOMING);

$num_products_count = $expected->RecordCount();


if ($expected_query != '' && $num_products_count > 0) {

  $col_width = floor(100/ 3); //SHOW_PRODUCT_INFO_COLUMNS_UPCOMING_PRODUCTS);

  while (!$expected->EOF) {

    $products_price = zen_get_products_display_price($expected->fields['products_id']);

    $list_box_contents[$row][$col] = array('params' =>'align="center" width="' . $col_width . '%;" valign="top"',
    'text' => '
                     <dl class="list_cell">
                     	<dt>
<a href="' . zen_href_link(zen_get_info_page($expected->fields['products_id']), 'products_id=' . $expected->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $expected->fields['products_image'], $expected->fields['products_name'], 100 /*IMAGE_UPCOMING_PRODUCTS_LISTING_WIDTH*/, 100 /*IMAGE_UPCOMING_PRODUCTS_LISTING_HEIGHT*/) . '</a>
						</dt><dt class="title_upcoming">
<a href="' . zen_href_link(zen_get_info_page($expected->fields['products_id']), 'products_id=' . $expected->fields['products_id']) . '" title="' . $expected->fields['products_name'] . '" alt="' . $expected->fields['products_name'] . '">' . substrEx($expected->fields['products_name'], 40) . '</a>
						</dt><dt class="price_upcoming">'
. $products_price .

'</dt><dt class="btn_upcoming"><a href="' . zen_href_link(zen_get_info_page($expected->fields['products_id']), 'products_id=' . $expected->fields['products_id']) . '">' . zen_image_button('small_view.gif', IMAGE_BUTTON_BUY_NOW) . '</a></dt></dl>
	');

    $col ++;
    if ($col > (/*SHOW_PRODUCT_INFO_COLUMNS_UPCOMING_PRODUCTS*/ 3 - 1)) {
      $col = 0;
      $row ++;
    }
    $expected->MoveNextRandom();
  }
  
  //to append enough cells for alignment
  while ( $col > 0 && $col < 3 /*SHOW_PRODUCT_INFO_COLUMNS_UPCOMING_PRODUCTS*/) {
		$list_box_contents[$row][$col] = array('params' =>'align="center" width="' . $col_width . '%;" valign="top"',
    	'text' => '&nbsp;');
	    $col ++;
  }

  if ($num_products_count > 0) {
    if (isset($new_products_category_id) && $new_products_category_id !=0) {
      $category_title = zen_get_categories_name((int)$new_products_category_id);
      $title = TABLE_HEADING_UPCOMING_PRODUCTS . ($category_title != '' ? ' - ' . $category_title : '');
    } else {
      $title = TABLE_HEADING_UPCOMING_PRODUCTS;
    }
    $zc_show_upcoming = true;
  }


}


?>