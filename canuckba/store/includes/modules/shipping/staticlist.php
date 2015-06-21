<?php
/**
 * @package shippingMethod
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version 1.00 staticlist.php 2008-02-25 Rick Riehle $
 */
/**
  Staticlist version 1.00
  by Rick Riehle 2008-02-25
  based on Store Pickup Module Class...
    and the Flst Rate Module Class...
    and the UPS Module Class...
    and the Zone Module Class...
    and snippets from here, there and everywhere.

  This shipping module lists a configurable number of static options
  from which a customer may select.  The options consist of a description
  paired with a price.  This might be useful, for example, if you make
  regular deliveries to a specific set of locations such as Farmer's 
  Markets, or if you are involved in fund raisers, each with a specific
  organizational affiliation, delivery date or location.`  It is 
  similar to the Flat Rate shipping module, except that it supports an
  arbitrary number of options rather than just one.

  INSTALLATION 

  This module consists of the following files.

  ~/includes/modules/shipping/staticlist.php
  ~/includes/languages/english/modules/shipping/staticlist.php

  These are new files; no existing Zen Cart files, core or otherwise,
  are modified.  Deploy them to your test instance of Zen Cart.  

  By default, the module comes configured to support 4 options.  This can be
  changed by editing the line below in the constructor that defines
  $this->num_options.  The constructor is in
  ~/includes/modules/shipping/staticlist.php

  Install the module by going to the admin screen, clicking on Modules,
  then clicking on Shipping.  A list of all shipping modules should appear.
  Select staticlist and click install.  Edit the configuration and the
  module should be ready for duty.  
 */

class staticlist extends base {

  /**
   * $code determines the internal 'code' name used to designate "this" payment module
   *
   * @var string
   */
  var $code;

  /**
   * $title is the displayed name for this payment method
   *
   * @var string
   */
  var $title;

  /**
   * $description is a soft name for this payment method
   *
   * @var string
   */
  var $description;

  /**
   * module's icon
   *
   * @var string
   */
  var $icon;

  /**
   * $enabled determines whether this module shows or not during checkout
   *
   * @var boolean
   */
  var $enabled;

  /**
   * User configurable setting for the number of options that will be displayed under this shipping method.
   *
   * @var integer
   */
  var $num_options;


  function staticlist() {
    global $order, $db;

    $this->code = 'staticlist';
    $this->title = MODULE_SHIPPING_STATICLIST_TEXT_TITLE;
    $this->description = MODULE_SHIPPING_STATICLIST_TEXT_DESCRIPTION;
    $this->sort_order = MODULE_SHIPPING_STATICLIST_SORT_ORDER;
    $this->icon = '';
    $this->tax_class = MODULE_SHIPPING_STATICLIST_TAX_CLASS;
    $this->tax_basis = MODULE_SHIPPING_STATICLIST_TAX_BASIS;

    // CUSTOMIZE THIS SETTING FOR THE NUMBER OF OPTIONS NEEDED!!
    $this->num_options = 3;

    // disable only when entire cart is free shipping
    if (zen_get_shipping_enabled($this->code)) {
      $this->enabled = ((MODULE_SHIPPING_STATICLIST_STATUS == 'True') ? true : false);
    }

    if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_STATICLIST_ZONE > 0) ) {
      $check_flag = false;
      $check = $db->Execute("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . "
                             where geo_zone_id = '" . MODULE_SHIPPING_STATICLIST_ZONE . "'
                             and zone_country_id = '" . $order->delivery['country']['id'] . "'
                             order by zone_id");
      while (!$check->EOF) {
        if ($check->fields['zone_id'] < 1) {
          $check_flag = true;
          break;
        } elseif ($check->fields['zone_id'] == $order->delivery['zone_id']) {
          $check_flag = true;
          break;
        }
        $check->MoveNext();
      }

      if ($check_flag == false) {
        $this->enabled = false;
      }
    }
  }


  /**
  * Load descriptions and costs from language template file
  * ~/includes/languages/english/modules/shipping/staticlist.php
  *
  * @param string $method
  * @return array
  */
  function quote($method = '') {
    global $order;

    $this->quotes = array('id' => $this->code,
                          'module' => $this->title,
                          'methods' => array());

    $fee = zen_get_configuration_key_value('MODULE_SHIPPING_STATICLIST_FEE');

    /*
     *  When the system calls a shipping module's quote() function with a non-null value for $method, it does not want a fully 
     *  populated array of shipping methods; rather, it is only interested in the value of $this->quotes['methods'][0], that 
     *  is, it is only interested in the value of the first element of the methods array. Under this condition, $method should 
     *  be used to determine which of the available options is to be reported back via $this->quotes['methods'][0] and the 
     *  rest of the methods array (namely $this->quotes['methods'][1] and $this->quotes['methods'][2] and so on) is irrelevant.
     */

    if ( zen_not_null($method) )  {
      $this->quotes['methods'][0] = array( 'id' => $method,
                                           'title' => zen_get_configuration_key_value('MODULE_SHIPPING_STATICLIST_OPT_' . $method),
                                           'cost'  => zen_get_configuration_key_value('MODULE_SHIPPING_STATICLIST_COST_' . $method) + $fee );
    }  else  {
      for ($i=0; $i<$this->num_options; $i++)  {
        $this->quotes['methods'][$i] = array( 'id'  => $i,
                                              'title' => zen_get_configuration_key_value('MODULE_SHIPPING_STATICLIST_OPT_' . $i),
                                              'cost'  => zen_get_configuration_key_value('MODULE_SHIPPING_STATICLIST_COST_' . $i) + $fee );
      }
    }

    if ($this->tax_class > 0)  {
      $this->quotes['tax'] = zen_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
    }

    if (zen_not_null($this->icon)) $this->quotes['icon'] = zen_image($this->icon, $this->title);

    return $this->quotes;
  }


  /**
   * Check to see whether module is installed
   *
   * @return boolean
   */
  function check() {
    global $db;
    if (!isset($this->_check)) {
      $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_STATICLIST_STATUS'");
      $this->_check = $check_query->RecordCount();
    }
    return $this->_check;
  }


  /**
   * Install the staticlist module and its configuration settings
   *
   */
  function install() {
    global $db;
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Static List', 'MODULE_SHIPPING_STATICLIST_STATUS', 'True', 'Do you want to offer a static list of shipping options?  Note: to change the number of options available through this shipping module, modify the value of num_options in includes/modules/shipping/staticlist.php, and then remove and re-install this module via Admin -> Modules -> Shipping.', '6', '0', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Fee', 'MODULE_SHIPPING_STATICLIST_FEE', '0.00', 'A handling or processing fee that is added to each option.', '6', '0', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_STATICLIST_TAX_CLASS', '0', 'Use the following tax class on the fee.', '6', '0', 'zen_get_tax_class_title', 'zen_cfg_pull_down_tax_classes(', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Tax Basis', 'MODULE_SHIPPING_STATICLIST_TAX_BASIS', 'Shipping', 'On what basis is tax calculated? Options are:<br />Shipping - Based on the delivery address of the customer.<br />Billing - Based on billing address of the customer.<br />Store - Based on the store address if the billing/shipping zone is the same as the store zone.', '6', '0', 'zen_cfg_select_option(array(\'Shipping\', \'Billing\', \'Store\'), ', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_STATICLIST_ZONE', '0', 'If a zone is selected, enable the static list for that zone only.', '6', '0', 'zen_get_zone_class_title', 'zen_cfg_pull_down_zone_classes(', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_STATICLIST_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
    for ($i=0; $i < $this->num_options; $i++) {
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Option " . $i . " Description', 'MODULE_SHIPPING_STATICLIST_OPT_" . $i ."', 'This is the text the customer will see for option " . $i . "', 'Description of option " . $i . "', '6', '0', 'zen_cfg_textarea(', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Option " . $i . " Cost', 'MODULE_SHIPPING_STATICLIST_COST_" . $i . "', '" . $i . ".00', 'Shipping rate for option " . $i . "', '6', '0', now())");
    }
  }


  /**
   * Remove the module and all its settings
   *
   */
  function remove() {
    global $db;
    $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }


  /**
   * Internal list of configuration keys used for configuration of the module
   *
   * @return array
   */
  function keys() {
    $keys = array('MODULE_SHIPPING_STATICLIST_STATUS', 'MODULE_SHIPPING_STATICLIST_FEE', 'MODULE_SHIPPING_STATICLIST_TAX_CLASS', 'MODULE_SHIPPING_STATICLIST_TAX_BASIS', 'MODULE_SHIPPING_STATICLIST_ZONE', 'MODULE_SHIPPING_STATICLIST_SORT_ORDER');

    for ($i=0; $i < $this->num_options; $i++) {
      $keys[] = 'MODULE_SHIPPING_STATICLIST_OPT_' . $i;
      $keys[] = 'MODULE_SHIPPING_STATICLIST_COST_' . $i;
    }

    return $keys;
  }
}
?>
