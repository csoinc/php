<?php
/**
 * featured_products module - prepares content for display
 *
 * @package modules
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: featured_products.php 3012 2006-02-11 16:34:02Z wilt $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
  $featured_products_query = "select distinct p.products_id, p.products_image, pd.products_name, p.products_tax_class_id, p.products_price
                           from (" . TABLE_PRODUCTS . " p
                           left join " . TABLE_FEATURED . " f on p.products_id = f.products_id
                           left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id )
                           where p.products_id = f.products_id and p.products_id = pd.products_id and p.products_status = 1 and f.status = 1 and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";
} else {
  $featured_products_query = "select distinct p.products_id, p.products_image, pd.products_name, p.products_tax_class_id, p.products_price
                           from (" . TABLE_PRODUCTS . " p
                           left join " . TABLE_FEATURED . " f on p.products_id = f.products_id
                           left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id), " .
  TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " .
  TABLE_CATEGORIES . " c
                           where p.products_id = p2c.products_id
                           and p2c.categories_id = c.categories_id
                           and c.parent_id = '" . (int)$new_products_category_id . "'
                           and p.products_id = f.products_id and p.products_id = pd.products_id and p.products_status = 1 and f.status = 1 and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";

}
$featured_products = $db->ExecuteRandomMulti($featured_products_query, MAX_DISPLAY_SEARCH_RESULTS_FEATURED);

$row = 0;
$col = 0;
$list_box_contents = array();
$title = '';

$num_products_count = $featured_products->RecordCount();


// show only when 1 or more
if ($num_products_count > 0) {
/*
  if ($num_products_count < SHOW_PRODUCT_INFO_COLUMNS_FEATURED_PRODUCTS || SHOW_PRODUCT_INFO_COLUMNS_FEATURED_PRODUCTS == 0) {
    $col_width = floor(100/$num_products_count);
  } else {
    $col_width = floor(100/SHOW_PRODUCT_INFO_COLUMNS_FEATURED_PRODUCTS);
  }
*/ //to keep the alignment right
  $col_width = floor(100/SHOW_PRODUCT_INFO_COLUMNS_FEATURED_PRODUCTS);

  while (!$featured_products->EOF) {

    $products_price = zen_get_products_display_price($featured_products->fields['products_id']);

    $list_box_contents[$row][$col] = array('params' =>'align="center" width="' . $col_width . '%;" valign="top"',
    'text' => '
                     <dl class="list_cell">
                     	<dt>
<a href="' . zen_href_link(zen_get_info_page($featured_products->fields['products_id']), 'products_id=' . $featured_products->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $featured_products->fields['products_image'], $featured_products->fields['products_name'], IMAGE_FEATURED_PRODUCTS_LISTING_WIDTH, IMAGE_FEATURED_PRODUCTS_LISTING_HEIGHT) . '</a>
						</dt><dt class="title_featured">
<a href="' . zen_href_link(zen_get_info_page($featured_products->fields['products_id']), 'products_id=' . $featured_products->fields['products_id']) . '" title="' . $featured_products->fields['products_name'] . '" alt="' . $featured_products->fields['products_name'] . '">' . substrEx($featured_products->fields['products_name'], 40) . '</a>
						</dt><dt class="price_featured">'
. $products_price .

'</dt><dt class="btn_featured"><a href="' . zen_href_link(zen_get_info_page($featured_products->fields['products_id']), 'products_id=' . $featured_products->fields['products_id']) . '">' . zen_image_button('small_view.gif', IMAGE_BUTTON_BUY_NOW) . '</a>&nbsp;' . 
 (STORE_STATUS == '0' ? '<a href="' . zen_href_link(basename($PHP_SELF), zen_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products->fields['products_id']) . '">' . zen_image_button('button_in_cart.gif', IMAGE_BUTTON_BUY_NOW) . '</a>' : '' ) .
                    ' </dt></dl>
	');

/*
                     <table cellspacing=0 cellpadding=0>
                      <tr><td width=130 height=40>
                           <table cellspacing=0 cellpadding=0 align=center>
                            <tr><td align=center style="padding-left:7px;padding-right:7px;"><a href="' . zen_href_link(zen_get_info_page($featured_products->fields['products_id']), 'products_id=' . $featured_products->fields['products_id']) . '" title="' . $featured_products->fields['products_name'] . '" alt="' . $featured_products->fields['products_name'] . '">' . substrEx($featured_products->fields['products_name'], 30) . '</a></td></tr>
                           </table>
                      </td></tr>
                      <tr><td >
                           <table cellspacing=0 cellpadding=0 align=center width=100>
                            <tr><td height=100 align=center><a href="' . zen_href_link(zen_get_info_page($featured_products->fields['products_id']), 'products_id=' . $featured_products->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $featured_products->fields['products_image'], $featured_products->fields['products_name'], IMAGE_FEATURED_PRODUCTS_LISTING_WIDTH, IMAGE_FEATURED_PRODUCTS_LISTING_HEIGHT) . '</a></td></tr>
                            <tr><td align=right>' . $products_price . '</td></tr>
                           </table>
                      </td></tr>
                      <tr><td height=5></td></tr>
                      <tr><td align=center><a href="' . zen_href_link(zen_get_info_page($featured_products->fields['products_id']), 'products_id=' . $featured_products->fields['products_id']) . '">' . zen_image_button('small_view.gif', IMAGE_BUTTON_BUY_NOW) . '</a>&nbsp;<a href="' . zen_href_link(basename($PHP_SELF), zen_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products->fields['products_id']) . '">' . zen_image_button('button_in_cart.gif', IMAGE_BUTTON_BUY_NOW) . '</a></td></tr>
                      <tr><td><img src=images/m26.gif width=135 height=9></td></tr>
                     </table>
    
    ');
*/

    $col ++;
    if ($col > (SHOW_PRODUCT_INFO_COLUMNS_FEATURED_PRODUCTS - 1)) {
      $col = 0;
      $row ++;
    }
    $featured_products->MoveNextRandom();
  }
  
  //to append enough cells for alignment
  while ( $col > 0 && $col < (SHOW_PRODUCT_INFO_COLUMNS_FEATURED_PRODUCTS)) {
		$list_box_contents[$row][$col] = array('params' =>'align="center" width="' . $col_width . '%;" valign="top"',
    	'text' => '&nbsp;');
	    $col ++;
  }

  if ($featured_products->RecordCount() > 0) {
    if (isset($new_products_category_id) && $new_products_category_id !=0) {
      $category_title = zen_get_categories_name((int)$new_products_category_id);
      $title = TABLE_HEADING_FEATURED_PRODUCTS . ($category_title != '' ? ' - ' . $category_title : '');
    } else {
      $title = TABLE_HEADING_FEATURED_PRODUCTS ;
    }
    $zc_show_featured = true;
  }
}
?>