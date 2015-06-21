<?php
/**
 * @package languageDefines
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: checkout_shipping.php 4042 2006-07-30 23:05:39Z drbyte $
 */

define('NAVBAR_TITLE_1', 'Checkout');
define('NAVBAR_TITLE_2', 'Shipping Method');

define('HEADING_TITLE', 'Step 1 of 3 - Delivery Information');

define('TABLE_HEADING_SHIPPING_ADDRESS', 'Shipping Address');
define('TEXT_CHOOSE_SHIPPING_DESTINATION', 'Your order will be shipped to the address at the left or you may change the shipping address by clicking the <em>Change Address</em> button.');
define('TITLE_SHIPPING_ADDRESS', 'Shipping Information:');

define('TABLE_HEADING_SHIPPING_METHOD', 'Shipping Method:');
define('TEXT_CHOOSE_SHIPPING_METHOD', 'Please select the preferred shipping method to use on this order. <BR> <span style="color:#e33">IMPORTANT: The shipping rate used during this transaction will be our minimum $10.00 charge. The actual shipping charge will be added to your bill at time of shipping. The rate may increase depending on location, and size/weight of package(s).</span><br> You can <a style="cursor:pointer;" onClick="addComment(\'Notify me the shipping fee before shipping.\')">click here to ask for a shipping fee notice</a>, then the order won\'t be shipped until we get your confirmation to the shipping rate. <span style="color:#e33">We highly recommend this step for International orders.</span>');
define('TITLE_PLEASE_SELECT', 'Please Select');
define('TEXT_ENTER_SHIPPING_INFORMATION', 'This is currently the only shipping method available to use on this order.');
define('TITLE_NO_SHIPPING_AVAILABLE', 'Not Available At This Time');
define('TEXT_NO_SHIPPING_AVAILABLE','<span class="alert">Sorry, we are not shipping to your region at this time.</span><br />Please contact us for alternate arrangements.');

define('TABLE_HEADING_COMMENTS', 'Special Instructions or Comments About Your Order');

define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', 'Continue to Step 2');
define('TEXT_CONTINUE_CHECKOUT_PROCEDURE', '- choose your payment method.');

// when free shipping for orders over $XX.00 is active
  define('FREE_SHIPPING_TITLE', 'Free Shipping');
  define('FREE_SHIPPING_DESCRIPTION', 'Free shipping for orders over %s');
?>
