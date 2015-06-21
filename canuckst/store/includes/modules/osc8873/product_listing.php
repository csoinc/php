<?php
/**
 * product_listing module
 *
 * @package modules
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: product_listing.php 6787 2007-08-24 14:06:33Z drbyte $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
$show_submit = zen_run_normal();
$listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_PRODUCTS_LISTING, 'p.products_id', 'page');
$zco_notifier->notify('NOTIFY_MODULE_PRODUCT_LISTING_RESULTCOUNT', $listing_split->number_of_rows);
$how_many = 0;

$zc_col_count_description = 0;
$lc_align = '';

if ($listing_split->number_of_rows > 0) {
  $rows = 0;
  $listing = $db->Execute($listing_split->sql_query);
  $extra_row = 0;
  while (!$listing->EOF) {

    if ((($rows-$extra_row)/2) == floor(($rows-$extra_row)/2)) {
      $list_box_contents[$rows] = array('params' => 'class="productListing-even"');
    } else {
      $list_box_contents[$rows] = array('params' => 'class="productListing-odd"');
    }

    $cur_row = sizeof($list_box_contents) - 1;

    $lc_align = '';

    $lc_title = '';
    $lc_model = '';
    $lc_description = '';
    $lc_price = '';
    $lc_image = '';
    $lc_btn = '';

    $lc_text = '';

    if (isset($_GET['manufacturers_id'])) {
      $lc_title = '<div class="itemTitle"><a href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), 'cPath=' . (($_GET['manufacturers_id'] > 0 and $_GET['filter_id']) > 0 ?  zen_get_generated_category_path_rev($_GET['filter_id']) : ($_GET['cPath'] > 0 ? zen_get_generated_category_path_rev($_GET['cPath']) : zen_get_generated_category_path_rev($listing->fields['master_categories_id']))) . '&products_id=' . $listing->fields['products_id']) . '">' . $listing->fields['products_name'] . '</a></div>';
      if ($listing->fields['products_model']!='') $lc_model = '<div class="itemModel">Item Code : '. $listing->fields['products_model'] .'</div>';
      $lc_description = '<div class="listingDescription">' . zen_trunc_string(zen_clean_html(stripslashes(zen_get_products_description($listing->fields['products_id'], $_SESSION['languages_id']))), PRODUCT_LIST_DESCRIPTION) . '</div>' ;
    } else {
      $lc_title = '<div class="itemTitle"><a href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), 'cPath=' . (($_GET['manufacturers_id'] > 0 and $_GET['filter_id']) > 0 ?  zen_get_generated_category_path_rev($_GET['filter_id']) : ($_GET['cPath'] > 0 ? zen_get_generated_category_path_rev($_GET['cPath']) : zen_get_generated_category_path_rev($listing->fields['master_categories_id']))) . '&products_id=' . $listing->fields['products_id']) . '">' . $listing->fields['products_name'] . '</a></div>';
      if ($listing->fields['products_model']!='') $lc_model = '<div class="itemModel">Item Code : '. $listing->fields['products_model'] .'</div>';
      $lc_description = '<div class="listingDescription">' . zen_trunc_string(zen_clean_html(stripslashes(zen_get_products_description($listing->fields['products_id'], $_SESSION['languages_id']))), PRODUCT_LIST_DESCRIPTION) . '</div>';
    }

    //case 'PRODUCT_LIST_PRICE':
    $lc_price = '<div class="itemPrice">'.zen_get_products_display_price_complex($listing->fields['products_id']) . '</div>';

    // more info in place of buy now
    $lc_button = '';
    if (zen_has_product_attributes($listing->fields['products_id']) or PRODUCT_LIST_PRICE_BUY_NOW == '0') {
      $lc_button .= '<a href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), 'cPath=' . (($_GET['manufacturers_id'] > 0 and $_GET['filter_id']) > 0 ?  zen_get_generated_category_path_rev($_GET['filter_id']) : ($_GET['cPath'] > 0 ? $_GET['cPath'] : zen_get_generated_category_path_rev($listing->fields['master_categories_id']))) . '&products_id=' . $listing->fields['products_id']) . '">' . /*MORE_INFO_TEXT*/ zen_image_button(BUTTON_IMAGE_SEL_BUY, BUTTON_SEL_BUY_ALT, 'class="listingBuyNowButton"') . '</a></div>';
    } else {
      if (PRODUCT_LISTING_MULTIPLE_ADD_TO_CART != 0) {
        if (
        // not a hide qty box product
        $listing->fields['products_qty_box_status'] != 0 &&
        // product type can be added to cart
        zen_get_products_allow_add_to_cart($listing->fields['products_id']) != 'N'
        &&
        // product is not call for price
        $listing->fields['product_is_call'] == 0
        &&
        // product is in stock or customers may add it to cart anyway
        ($listing->fields['products_quantity'] > 0 || SHOW_PRODUCTS_SOLD_OUT_IMAGE == 0) ) {
          $how_many++;
        }
        // hide quantity box
        if ($listing->fields['products_qty_box_status'] == 0) {
          $lc_button .= '<a href="' . zen_href_link($_GET['main_page'], zen_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing->fields['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_BUY_NOW, BUTTON_BUY_NOW_ALT, 'class="listingBuyNowButton"') . '</a>';
        } else {
          $lc_button .= TEXT_PRODUCT_LISTING_MULTIPLE_ADD_TO_CART . "<input type=\"text\" name=\"products_id[" . $listing->fields['products_id'] . "]\" value=\"0\" size=\"4\" />";
        }
      } else {
        // qty box with add to cart button
        if (PRODUCT_LIST_PRICE_BUY_NOW == '2' && $listing->fields['products_qty_box_status'] != 0) {
          $lc_button .= zen_draw_form('cart_quantity', zen_href_link(zen_get_info_page($listing->fields['products_id']), zen_get_all_get_params(array('action')) . 'action=add_product&products_id=' . $listing->fields['products_id']), 'post', 'enctype="multipart/form-data"') . '<input type="text" name="cart_quantity" value="' . (zen_get_buy_now_qty($listing->fields['products_id'])) . '" maxlength="6" size="4" /><br />' . zen_draw_hidden_field('products_id', $listing->fields['products_id']) . zen_image_submit(BUTTON_IMAGE_IN_CART, BUTTON_IN_CART_ALT) . '</form>';
        } else {
          $lc_button .= '<a href="' . zen_href_link($_GET['main_page'], zen_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing->fields['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_BUY_NOW, BUTTON_BUY_NOW_ALT, 'class="listingBuyNowButton"') . '</a>';
        }
      }
    }
    $the_button = $lc_button;
    $products_link = '<a href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), 'cPath=' . ( ($_GET['manufacturers_id'] > 0 and $_GET['filter_id']) > 0 ? zen_get_generated_category_path_rev($_GET['filter_id']) : $_GET['cPath'] > 0 ? zen_get_generated_category_path_rev($_GET['cPath']) : zen_get_generated_category_path_rev($listing->fields['master_categories_id'])) . '&products_id=' . $listing->fields['products_id']) . '">' . /*MORE_INFO_TEXT*/ zen_image_button(BUTTON_IMAGE_SEL_BUY, BUTTON_SEL_BUY_ALT, 'class="listingBuyNowButton"') . '</a>';

    if (STORE_STATUS != '0') {
      //showcase only
      $lc_btn = '<div class="itemButton">' . zen_get_details_button($listing->fields['products_id'], $the_button, $products_link);
    }
    else {
      $lc_btn = '<div class="itemButton">' . zen_get_buy_now_button($listing->fields['products_id'], $the_button, $products_link) . '' . zen_get_products_quantity_min_units_display($listing->fields['products_id']);
    }

    $lc_btn .= ' &nbsp; ' . (zen_get_show_product_switch($listing->fields['products_id'], 'ALWAYS_FREE_SHIPPING_IMAGE_SWITCH') ? (zen_get_product_is_always_free_shipping($listing->fields['products_id']) ? TEXT_PRODUCT_FREE_SHIPPING_ICON . '' : '') : '') . '</div>';

    //'PRODUCT_LIST_IMAGE':
    if ($listing->fields['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == 0) {
      $lc_image = '';
    } else {
      if (isset($_GET['manufacturers_id'])) {
        $lc_image = '<div class="itemImage"><a href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), 'cPath=' . (($_GET['manufacturers_id'] > 0 and $_GET['filter_id']) > 0 ?  zen_get_generated_category_path_rev($_GET['filter_id']) : ($_GET['cPath'] > 0 ? zen_get_generated_category_path_rev($_GET['cPath']) : zen_get_generated_category_path_rev($listing->fields['master_categories_id']))) . '&products_id=' . $listing->fields['products_id']) . '">' . zen_image(/*DIR_WS_IMAGES . $listing->fields['products_image']*/ getImageMedium($listing->fields['products_image']), $listing->fields['products_name'], IMAGE_PRODUCT_LISTING_WIDTH, IMAGE_PRODUCT_LISTING_HEIGHT, 'class="listingProductImage"') . '<br/>More colors, sizes or info</a></div>';
      } else {
        $lc_image = '<div class="itemImage"><a href="' . zen_href_link(zen_get_info_page($listing->fields['products_id']), 'cPath=' . (($_GET['manufacturers_id'] > 0 and $_GET['filter_id']) > 0 ?  zen_get_generated_category_path_rev($_GET['filter_id']) : ($_GET['cPath'] > 0 ? zen_get_generated_category_path_rev($_GET['cPath']) : zen_get_generated_category_path_rev($listing->fields['master_categories_id']))) . '&products_id=' . $listing->fields['products_id']) . '">' . zen_image(/*DIR_WS_IMAGES . $listing->fields['products_image']*/ getImageMedium($listing->fields['products_image']), $listing->fields['products_name'], IMAGE_PRODUCT_LISTING_WIDTH, IMAGE_PRODUCT_LISTING_HEIGHT, 'class="listingProductImage"') . '<br/>More colors, sizes or info</a></div>';
      }
    }


    $lc_text = '<table cellpadding=0 cellspacing=0 class="product_list_item"><tr><td width="220" rowspan=3>' . $lc_image . '</td>' .
			'<td>' . $lc_title . $lc_model . '<br/>' . $lc_description . '<br/></td></tr>' .
			'<tr><td height="20">' . $lc_price . '</td></tr>' . 
			'<tr><td >' . $lc_btn . '</td></tr></table>';


    $list_box_contents[$rows][0] = array('align' => $lc_align,
                                              'params' => 'class="productListing-data"',
                                              'text'  => $lc_text);
    $listing->MoveNext();
    $rows++;

  }
  $error_categories = false;
} else {
  $list_box_contents = array();

  $list_box_contents[0] = array('params' => 'class="productListing-odd"');
  $list_box_contents[0][] = array('params' => 'class="productListing-data"',
                                              'text' => TEXT_NO_PRODUCTS);

  $error_categories = true;
}

if (($how_many > 0 and $show_submit == true and $listing_split->number_of_rows > 0) and (PRODUCT_LISTING_MULTIPLE_ADD_TO_CART == 1 or  PRODUCT_LISTING_MULTIPLE_ADD_TO_CART == 3) ) {
  $show_top_submit_button = true;
} else {
  $show_top_submit_button = false;
}
if (($how_many > 0 and $show_submit == true and $listing_split->number_of_rows > 0) and (PRODUCT_LISTING_MULTIPLE_ADD_TO_CART >= 2) ) {
  $show_bottom_submit_button = true;
} else {
  $show_bottom_submit_button = false;
}



if ($how_many > 0 && PRODUCT_LISTING_MULTIPLE_ADD_TO_CART != 0 and $show_submit == true and $listing_split->number_of_rows > 0) {
  // bof: multiple products
  echo zen_draw_form('multiple_products_cart_quantity', zen_href_link(FILENAME_DEFAULT, zen_get_all_get_params(array('action')) . 'action=multiple_products_add_product'), 'post', 'enctype="multipart/form-data"');
}

?>
