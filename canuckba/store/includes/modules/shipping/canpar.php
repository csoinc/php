<?php
/*
  canpar.php,v 0.1 2006/07/22 10:52:11 hpdl Exp $

  ORIGINAL CANPAR SCRIPT
  Copyright (c) 2006 J. B. Wallace (jbwallace@shaw.ca) 2006.7.22

  INTEGRATION WITH XML
  Copyright (c) 2006 K. B. Gervais (kevinalwayswins@hotmail.com) 2006.8.25
  Adaption copyright CyKron Interactive (www.cykron.com).

  MODIFICATION TO WORK WITH ZEN CART
  Copyright (c) 2007 Steve Oliveira (oliveira.steve@gmail.com) 7/24/2007

  Released under the GNU General Public License
*/

  class canpar {
    var $code, $title, $description, $icon, $enabled;

// class constructor
    function canpar() {
      global $order;

      $this->code = 'canpar';
      $this->title = MODULE_SHIPPING_CANPAR_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_CANPAR_TEXT_DESCRIPTION;
      $this->mark_up = MODULE_SHIPPING_CANPAR_MARK_UP;
      $this->sort_order = MODULE_SHIPPING_CANPAR_SORT_ORDER;
      $this->icon = DIR_WS_IMAGES . 'icons/canpar.gif';
      $this->tax_class = MODULE_SHIPPING_CANPAR_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_CANPAR_STATUS == 'True') ? true : false);

    }

// class methods
    function quote($method = '') {
      global $order, $shipping_weight,$shipping_num_boxes, $total_weight, $boxcount, $handling_cp, $cart;

      $srcFSA = substr(strtoupper(SHIPPING_ORIGIN_ZIP), 0, 3);
      $desFSA = substr(strtoupper($order->delivery['postcode']), 0, 3);

      $srcFSA1stLetter = substr(strtoupper(SHIPPING_ORIGIN_ZIP), 0, 1);
	  $desFSA1stLetter = substr(strtoupper($order->delivery['postcode']), 0, 1);

      $PkgWT = $shipping_weight;

      //Connect to CanPar here to get quote, and parse XML.
      $request = join('&', array('service=1',
                                 'quantity=' . $shipping_num_boxes,
                                 'unit=L',
                                 'origin=' . $srcFSA,
                                 'dest=' . $desFSA,
                                 'cod=0',
                                 'weight=' . intval($shipping_weight),
                                 'put=0',
                                 'xc=0',
                                 'dec=0'));
		
	  $body = file_get_contents('http://www.canpar.com/CanparRateXML/BaseRateXML.jsp?' . $request);
      $body_array = explode("<BaseRate>", $body);

      $ShippingCost = $body_array[1];
      $ShippingCost = ereg_replace('</BaseRate></CanparCharges></CanparRate>', '', $ShippingCost);


	  if (strlen($ErrMsg) == 0) {
		  $this->quotes = array('id' => $this->code,
		                        'module' => MODULE_SHIPPING_CANPAR_TEXT_TITLE,
		                        'methods' => array(array('id' => $this->code,
		                                                 'title' => MODULE_SHIPPING_CANPAR_TEXT_WAY,
		                                                 'cost' => $ShippingCost)));
	  } else {
          $this->quotes = array('module' => $this->title,
                                'error' => $ErrMsg);
	  }

      if ($this->tax_class > 0) {
        $this->quotes['tax'] =  zen_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }

      if (zen_not_null($this->icon)) $this->quotes['icon'] = zen_image($this->icon, $this->title, null, null, 'align="middle"');

      return $this->quotes;
    }

    function check() {
	  global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_CANPAR_STATUS'");
        $this->_check = $check_query->RecordCount();
      }
      return $this->_check;
    }

    function install() {
	  global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable CANPAR Shipping', 'MODULE_SHIPPING_CANPAR_STATUS', 'True', 'Do you want to offer CANPAR rate shipping?', '6', '0', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_CANPAR_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'zen_get_tax_class_title', 'zen_cfg_pull_down_tax_classes(', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Mark Up', 'MODULE_SHIPPING_CANPAR_MARK_UP', '1', 'Use the following mark-up on the shipping list fees.', '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_CANPAR_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
    }

    function remove() {
	  global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_SHIPPING_CANPAR_STATUS', 'MODULE_SHIPPING_CANPAR_TAX_CLASS', 'MODULE_SHIPPING_CANPAR_MARK_UP', 'MODULE_SHIPPING_CANPAR_SORT_ORDER');
    }
  }
?>
