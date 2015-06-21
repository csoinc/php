<?php
/**
 * specials_index module
 *
 * @package modules
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: specials_index.php 3018 2006-02-12 21:04:04Z wilt $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
  $specials_index_query = "select p.products_id, p.products_image, pd.products_name, p.products_tax_class_id, p.products_price
   from (" . TABLE_PRODUCTS . " p
   left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id
   left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id )
   where p.products_id = s.products_id and p.products_id = pd.products_id and p.products_status = '1' and s.status = 1 and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";
} else {
  $specials_index_query = "select distinct p.products_id, p.products_image, pd.products_name, p.products_tax_class_id, p.products_price
   from (" . TABLE_PRODUCTS . " p
   left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id
   left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id ), " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c
   where p.products_id = p2c.products_id
   and p2c.categories_id = c.categories_id
   and c.parent_id = '" . (int)$new_products_category_id . "'
   and p.products_id = s.products_id and p.products_id = pd.products_id and p.products_status = '1' and s.status = '1' and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";
}
$specials_index = $db->ExecuteRandomMulti($specials_index_query, MAX_DISPLAY_SPECIAL_PRODUCTS_INDEX);

$row = 0;
$col = 0;
$list_box_contents = array();
$title = '';

$num_products_count = $specials_index->RecordCount();
// show only when 1 or more
if ($num_products_count > 0) {
  if ($num_products_count < SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS || SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS == 0 ) {
    $col_width = floor(100/$num_products_count);
  } else {
    $col_width = floor(100/SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS);
  }

  $list_box_contents = array();
  while (!$specials_index->EOF) {

    $products_price = zen_get_products_display_price($specials_index->fields['products_id']);

    $specials_index->fields['products_name'] = zen_get_products_name($specials_index->fields['products_id']);
    $list_box_contents[$row][$col] = array('params' => 'align="center" width="' . $col_width . '%;" valign="top"',
    'text' => '
                     <dl class="list_cell">
                     	<dt>
<a href="' . zen_href_link(zen_get_info_page($specials_index->fields['products_id']), 'products_id=' . $specials_index->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $specials_index->fields['products_image'], $specials_index->fields['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>
						</dt><dt class="title_specials">
<a href="' . zen_href_link(zen_get_info_page($specials_index->fields['products_id']), 'products_id=' . $specials_index->fields['products_id']) . '" title="' . $specials_index->fields['products_name'] . '" alt="' . $specials_index->fields['products_name'] . '">' . substrEx($specials_index->fields['products_name'], 40) . '</a>
						</dt><dt class="price_specials">'
. $products_price .

'</dt><dt class="btn_specials"><a href="' . zen_href_link(zen_get_info_page($specials_index->fields['products_id']), 'products_id=' . $specials_index->fields['products_id']) . '">' . zen_image_button('small_view.gif', IMAGE_BUTTON_BUY_NOW) . '</a>&nbsp; ' .
 (STORE_STATUS == '0' ? '<a href="' . zen_href_link(basename($PHP_SELF), zen_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $specials_index->fields['products_id']) . '">' . zen_image_button('button_in_cart.gif', IMAGE_BUTTON_BUY_NOW) . '</a>' : '' ) .
                    '</dt></dl>
	');

/*

                     <table cellspacing=0 cellpadding=0>
                      <tr><td background=images/m24.gif width=169 height=61>
                           <table cellspacing=0 cellpadding=0 align=center>
                            <tr><td height=28></td></tr>
                            <tr><td class=fe2 align=center style="padding-left:7px;padding-right:7px;"><a class=fe2 href="' . zen_href_link(zen_get_info_page($specials_index->fields['products_id']), 'products_id=' . $specials_index->fields['products_id']) . '">' . substr($specials_index->fields['products_name'],0,40) . '</a></td></tr>
                            <tr><td height=6></td></tr>
                           </table>
                      </td></tr>
                      <tr><td class=bg>
                           <table cellspacing=0 cellpadding=0 align=center width=115>
                            <tr><td height=5></td></tr>
                            <tr><td height=120 align=center><a href="' . zen_href_link(zen_get_info_page($specials_index->fields['products_id']), 'products_id=' . $specials_index->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $specials_index->fields['products_image'], $specials_index->fields['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td></tr>
                            <tr><td height=4></td></tr>
                            <tr><td class=fe1 align=right>' . $products_price . '</td></tr>
                           </table>
                      </td></tr>
                      <tr><td><img src=images/m26.gif width=169 height=9></td></tr>
                      <tr><td height=5></td></tr>
                      <tr><td align=center><a href="' . zen_href_link(zen_get_info_page($specials_index->fields['products_id']), 'products_id=' . $specials_index->fields['products_id']) . '">' . zen_image_button('small_view.gif', IMAGE_BUTTON_BUY_NOW) . '</a>&nbsp;<a href="' . zen_href_link(basename($PHP_SELF), zen_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $specials_index->fields['products_id']) . '">' . zen_image_button('button_in_cart.gif', IMAGE_BUTTON_BUY_NOW) . '</a></td></tr>
                     </table>


    ');
*/

    $col ++;
    if ($col > (SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS - 1)) {
      $col = 0;
      $row ++;
    }
    $specials_index->MoveNextRandom();
  }
  
  //to append enough cells for alignment
  while ( $col > 0 && $col < (SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS)) {
		$list_box_contents[$row][$col] = array('params' =>'align="center" width="' . $col_width . '%;" valign="top"',
    	'text' => '&nbsp;');
	    $col ++;
  }

  if ($specials_index->RecordCount() > 0) {
    $title = sprintf(TABLE_HEADING_SPECIALS_INDEX, strftime('%B'));
    $zc_show_specials = true;
  }
}
?>