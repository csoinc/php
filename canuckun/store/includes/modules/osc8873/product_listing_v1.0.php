<?php
/**
 * product_listing module
 *
 * @package modules
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: product_listing.php 3240 2006-03-22 04:10:45Z ajeh $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
$show_submit = zen_run_normal();
$listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_PRODUCTS_LISTING, 'p.products_id', 'page');
$listing = $db->Execute($listing_split->sql_query);

// display limits
$display_limit = zen_get_products_new_timelimit();

$row = 0;
$col = 0;
$list_box_contents = array();
$title = '';

$num_products_count = $listing->RecordCount();

// show only when 1 or more
if ($num_products_count > 0) {
  if ($num_products_count < SHOW_PRODUCT_INFO_COLUMNS_NEW_PRODUCTS || SHOW_PRODUCT_INFO_COLUMNS_NEW_PRODUCTS == 0 ) {
    $col_width = floor(100/$num_products_count);
  } else {
    $col_width = floor(100/SHOW_PRODUCT_INFO_COLUMNS_NEW_PRODUCTS);
  }

  while (!$listing->EOF) {

    $products_price = zen_get_products_display_price($listing->fields['products_id']);

    $listing->fields['products_name'] = zen_get_products_name($listing->fields['products_id']);
    $list_box_contents[$row][$col] = array('params' => 'align="center" width="' . $col_width . '%;" valign="top"',
    'text' => '
                     <table cellspacing=0 cellpadding=0> 
                      <tr><td background=images/m24.gif width=169 height=61>
                           <table cellspacing=0 cellpadding=0 align=center>
                            <tr><td height=28></td></tr>
                            <tr><td class=fe2 align=center style="padding-left:7px;padding-right:7px;"><a class=fe2 href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), 'products_id=' . $listing->fields['products_id']) . '">' . substr($listing->fields['products_name'],0,40) . '</a></td></tr>
                            <tr><td height=6></td></tr>
                           </table>
                      </td></tr>
                      <tr><td class=bg>
                           <table cellspacing=0 cellpadding=0 align=center width=115>
                            <tr><td height=5></td></tr>
                            <tr><td height=120 align=center><a href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), 'products_id=' . $listing->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $listing->fields['products_image'], $listing->fields['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td></tr>
                            <tr><td height=4></td></tr>
                            <tr><td class=fe1 align=right>' . $products_price . '</td></tr>
                           </table>
                      </td></tr>
                      <tr><td><img src=images/m26.gif width=169 height=9></td></tr>
                      <tr><td height=5></td></tr>
                      <tr><td align=center><a href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), 'products_id=' . $listing->fields['products_id']) . '">' . zen_image_button('small_view.gif', IMAGE_BUTTON_BUY_NOW) . '</a>&nbsp;<a href="' . zen_href_link(basename($PHP_SELF), zen_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing->fields['products_id']) . '">' . zen_image_button('button_in_cart.gif', IMAGE_BUTTON_BUY_NOW) . '</a></td></tr>
                     </table>
                                           ');

    $col ++;
    if ($col > (SHOW_PRODUCT_INFO_COLUMNS_NEW_PRODUCTS - 1)) {
      $col = 0;
      $row ++;
    }
    $listing->MoveNext();
  }

  if ($listing->RecordCount() > 0) {
    if (isset($new_products_category_id) && $new_products_category_id != 0) {
      $category_title = zen_get_categories_name((int)$new_products_category_id);
      $title = sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')) . ($category_title != '' ? ' - ' . $category_title : '' ) ;
    } else {
      $title = sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')) ;
    }
    $zc_show_new_products = true;
  }
}
?>