<?php
/*
  $Id: canadapost.php,v 3.7 October 23 2004

  Before using this class, you should open a Canada
  Post Eparcel Account, and change the CPCIP to
  your ID. Visit www.canadapost.ca for details.

  XML connection method with Canada Post.

  Copyright (c) 2002,2003 Kelvin Zhang (kelvin@syngear.com)
  Modified by Kenneth Wang (kenneth@cqww.net), 2002.11.12
  LXWXH added by Tom St.Croix (management@betterthannature.com)
  All thanks to Kelvin and Kenneth and many others.

  Released under the GNU General Public License
  


  Updated to Zen Cart v1.3.0 April 9/2006
*/
/**
 * Canada Post Shipping Module class
 *
 */
class canadapost extends base {
  /**
   * Declare shipping module alias code
   *
   * @var string
   */
  var $code;
  /**
   * Shipping module display name
   *
   * @var string
   */
  var $title;
  /**
   * Shipping module display description
   *
   * @var string
   */
  var $description;
  /**
   * Shipping module icon filename/path
   *
   * @var string
   */
  var $icon;
  /**
   * Shipping module status
   *
   * @var boolean
   */
  var $enabled;
  /**
   * Shipping Types
   *
   * @var array
   */
  var $types;
  var $boxcount;
  /**
   * Constructor
   *
   * @return usps
   */
  function canadapost() {
    global $order, $db, $template;
    $this->code = 'canadapost';
      $this->title = MODULE_SHIPPING_CANADAPOST_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_CANADAPOST_TEXT_DESCRIPTION;
      $this->icon = DIR_WS_IMAGES . 'shipping_canadapost.gif';
      $this->comments = '';
      $this->enabled = ((MODULE_SHIPPING_CANADAPOST_STATUS == 'True') ? true : false);
      $this->server = MODULE_SHIPPING_CANADAPOST_SERVERIP;
      $this->port = MODULE_SHIPPING_CANADAPOST_SERVERPOST;
      $this->language = (in_array(strtolower($_SESSION['languages_code']), array('en', 'fr'))) ? strtolower($_SESSION['languages_code']) :MODULE_SHIPPING_CANADAPOST_LANGUAGE;
      $this->CPCID = MODULE_SHIPPING_CANADAPOST_CPCID;
      $this->turnaround_time = MODULE_SHIPPING_CANADAPOST_TIME;
      $this->sort_order = MODULE_SHIPPING_CANADAPOST_SORT_ORDER;
      $this->items_qty = 0;
      $this->items_price = 0;
      $this->tax_class = MODULE_SHIPPING_CANADAPOST_TAX_CLASS;
      $this->tax_basis = MODULE_SHIPPING_CANADAPOST_TAX_BASIS;
      $this->cp_online_handling = ((MODULE_SHIPPING_CANADAPOST_CP_HANDLING == 'True') ? true : false);


    // disable when entire cart is free shipping
    if (zen_get_shipping_enabled($this->code)) {
      $this->enabled = ((MODULE_SHIPPING_CANADAPOST_STATUS == 'True') ? true : false);
    }
      if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_CANADAPOST_ZONE > 0) ) {
        $check_flag = false;
        $check = $db->Execute("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_CANADAPOST_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
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
   * Get quote from shipping provider's API:
   *
   * @param string $method
   * @return array of quotation results
   */
    function quote($method = '') {
      global $order, $shipping_weight, $shipping_num_boxes, $total_weight, $boxcount, $handling_cp;
    // will round to 2 decimals 9.112 becomes 9.11 thus a product can be 0.1 of a KG
      $shipping_weight = round($shipping_weight, 2);


      $country_name = zen_get_countries(STORE_COUNTRY, true);
      $this->_canadapostOrigin(SHIPPING_ORIGIN_ZIP, $country_name['countries_iso_code_2']);

      if (!zen_not_null($order->delivery['state']) && $order->delivery['zone_id'] > 0 ) {
        $state_name = zen_get_zone_code($order->delivery['country_id'], $order->delivery['zone_id'], '');
        $order->delivery['state'] = $state_name;
      }

      $this->_canadapostDest($order->delivery['city'], $order->delivery['state'], $order->delivery['country']['iso_code_2'], (zen_not_null($order->delivery['postcode']) ? $order->delivery['postcode'] : 'null'));

      $products_array = $_SESSION['cart']->get_products();
      for ($i=0; $i<count($products_array); $i++)
        // product name sent as "online product" to canada post as some characters will cause CP to result in an error if & % and others in the product name
        if (zen_get_product_is_always_free_shipping($products_array[$i]['id']) == false) $this->_addItem ($products_array[$i][quantity], $products_array[$i][final_price], $products_array[$i][weight], $products_array[$i][length], $products_array[$i][width], $products_array[$i][height], 'online_product', $products_array[$i][ready_to_ship], $products_array[$i][dim_type], $products_array[$i][weight_type]);

      $canadapostQuote = $this->_canadapostGetQuote();
      if ( (is_array($canadapostQuote)) && (sizeof($canadapostQuote) > 0) ) {
        $this->quotes = array('id' => $this->code,
                              'module' => $this->title . ' (' . $this->boxCount . MODULE_SHIPPING_PACKAGING_RESULTS . ')');
        $methods = array();
        for ($i=0; $i<sizeof($canadapostQuote); $i++) {
          list($type, $cost) = each($canadapostQuote[$i]);

	if ( $this->cp_online_handling == true) {
	  if ( $method == '' || $method == $type ) {
            $methods[] = array('id' => $type,
                               'title' => $type,
                               'cost' => $cost + $this->handling_cp);
	}
		} else {
	  if ( $method == '' || $method == $type ) {
            $methods[] = array('id' => $type,
                               'title' => $type,
                               'cost' => (MODULE_SHIPPING_CANADAPOST_SHIPPING_HANDLING + $cost));
			}
		}
	}
      if ($this->tax_class > 0) {
        $this->quotes['tax'] = zen_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }
        $this->quotes['methods'] = $methods;
      } else {
      	if ( $canadapostQuote != false ) {
      	    $errmsg = $canadapostQuote;
      	} else {
      	    $errmsg = MODULE_SHIPPING_CANADAPOST_CALC_ERROR;
        }
      	$errmsg .= MODULE_SHIPPING_CANADAPOST_ERROR_INFO;
        $this->quotes = array('module' => $this->title,
                              'error' => $errmsg);
      }


      if (zen_not_null($this->icon)) $this->quotes['icon'] = zen_image($this->icon, $this->title);
      if (zen_not_null($this->comments)) $this->quotes['comments'] = $this->comments;

      return $this->quotes;
    }
  /**
   * check status of module
   *
   * @return boolean
   */
    function check() {
      global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_CANADAPOST_STATUS'");
        $this->_check = $check_query->RecordCount();
      }
      return $this->_check;
    }
  /**
   * Install this module
   *
   */
    function install() {
      global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable CanadaPost Shipping', 'MODULE_SHIPPING_CANADAPOST_STATUS', 'True', 'Do you want to offer Canada Post shipping?', '6', '0', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter CanadaPost Server', 'MODULE_SHIPPING_CANADAPOST_SERVERIP', 'sellonline.canadapost.ca', 'Canada Post server. <br>(default: sellonline.canadapost.ca)', '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter CanadaPost Server Port', 'MODULE_SHIPPING_CANADAPOST_SERVERPOST', '30000', 'Service Port of Canada Post server. <br>(default: 30000)', '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter Selected Language-optional', 'MODULE_SHIPPING_CANADAPOST_LANGUAGE', 'en', 'Canada Post supports two languages:<br><strong>en</strong>-english<br><strong>fr</strong>-french.', '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter Your CanadaPost Customer ID', 'MODULE_SHIPPING_CANADAPOST_CPCID', 'CPC_DEMO_XML', 'Canada Post Customer ID Merchant Identification assigned by Canada Post.', '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter Turn Around Time(optional)', 'MODULE_SHIPPING_CANADAPOST_TIME', '0', 'Turn Around Time -hours.', '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_CANADAPOST_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'zen_get_tax_class_title', 'zen_cfg_pull_down_tax_classes(', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Tax Basis', 'MODULE_SHIPPING_CANADAPOST_TAX_BASIS', 'Shipping', 'On what basis is Shipping Tax calculated. Options are<br />Shipping - Based on customers Shipping Address<br />Billing Based on customers Billing address<br />Store - Based on Store address if Billing/Shipping Zone equals Store zone', '6', '0', 'zen_cfg_select_option(array(\'Shipping\', \'Billing\', \'Store\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_CANADAPOST_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'zen_get_zone_class_title', 'zen_cfg_pull_down_zone_classes(', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use CP Handling Charge System', 'MODULE_SHIPPING_CANADAPOST_CP_HANDLING', 'False', 'Use the Canada Post shipping and handling charge system (instead of the handling charge feature built-in to this module)?', '6', '0', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Charge per box', 'MODULE_SHIPPING_CANADAPOST_SHIPPING_HANDLING', '0', 'Handling Charge is only used if the CP Handling System is set to false', '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_CANADAPOST_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");

      global $sniffer;
      if (method_exists($sniffer, 'field_type')) {
        if (!$sniffer->field_exists(TABLE_PRODUCTS, 'products_weight_type'))
          $db->Execute("ALTER TABLE " . TABLE_PRODUCTS . " ADD products_weight_type ENUM('lbs','kgs') NOT NULL default 'kgs' after products_weight");
        if (!$sniffer->field_exists(TABLE_PRODUCTS, 'products_dim_type'))
          $db->Execute("ALTER TABLE " . TABLE_PRODUCTS . " ADD products_dim_type ENUM('in','cm') NOT NULL default 'cm' after products_weight_type");
        if (!$sniffer->field_exists(TABLE_PRODUCTS, 'products_length'))
          $db->Execute("ALTER TABLE " . TABLE_PRODUCTS . " ADD products_length DECIMAL(6,2) DEFAULT '12' NOT NULL after products_dim_type");
        if (!$sniffer->field_exists(TABLE_PRODUCTS, 'products_width'))
          $db->Execute("ALTER TABLE " . TABLE_PRODUCTS . " ADD products_width DECIMAL(6,2) DEFAULT '12' NOT NULL after products_length");
        if (!$sniffer->field_exists(TABLE_PRODUCTS, 'products_height'))
          $db->Execute("ALTER TABLE " . TABLE_PRODUCTS . " ADD products_height DECIMAL(6,2) DEFAULT '12' NOT NULL after products_width");
        if (!$sniffer->field_exists(TABLE_PRODUCTS, 'products_ready_to_ship'))
          $db->Execute("ALTER TABLE " . TABLE_PRODUCTS . " ADD products_ready_to_ship ENUM('0','1') NOT NULL default '1' after products_height");
      }
    }
  /**
   * Remove this module
   *
   */
    function remove() {
      global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  /**
   * Build array of keys used for installing/managing this module
   *
   * @return array
   */
    function keys() {
      return array('MODULE_SHIPPING_CANADAPOST_STATUS', 'MODULE_SHIPPING_CANADAPOST_SERVERIP', 'MODULE_SHIPPING_CANADAPOST_SERVERPOST', 'MODULE_SHIPPING_CANADAPOST_LANGUAGE', 'MODULE_SHIPPING_CANADAPOST_CPCID', 'MODULE_SHIPPING_CANADAPOST_TIME', 'MODULE_SHIPPING_CANADAPOST_TAX_CLASS', 'MODULE_SHIPPING_CANADAPOST_TAX_BASIS', 'MODULE_SHIPPING_CANADAPOST_ZONE', 'MODULE_SHIPPING_CANADAPOST_CP_HANDLING', 'MODULE_SHIPPING_CANADAPOST_SHIPPING_HANDLING', 'MODULE_SHIPPING_CANADAPOST_SORT_ORDER');
   }


    function _canadapostOrigin($postal, $country){
      $this->_canadapostOriginPostalCode = str_replace(' ', '', $postal);
      $this->_canadapostOriginCountryCode = $country;
    }


    function _canadapostDest($dest_city,$dest_province,$dest_country,$dest_zip){
      $this->dest_city = $dest_city;
      $this->dest_province = $dest_province;
      $this->dest_country = $dest_country;
      $this->dest_zip = str_replace(' ', '', $dest_zip);
    }


    /*
      Add items to parcel.
    */

    function _addItem ($quantity, $rate, $weight, $length, $width, $height, $description, $ready_to_ship, $dim_type, $weight_type) {
      $index = $this->items_qty;
      $this->item_quantity[$index] = (string)$quantity;
      $this->item_weight[$index] = ( $weight ? (string)$weight : '0' );
      $this->item_length[$index] = ( $length ? (string)$length : '0' );
      $this->item_width[$index] = ( $width ? (string)$width : '0' );
      $this->item_height[$index] = ( $height ? (string)$height : '0' );
      $this->item_description[$index] = $description;
      $this->item_ready_to_ship[$index] = (string)$ready_to_ship;
      $this->item_dim_type[$index] = (string)$dim_type;
      $this->item_weight_type[$index] = (string)$weight_type;
      $this->items_qty ++;
      $this->items_price += $quantity * $rate;
    }


    /*
      using HTTP/POST send message to canada post server
    */
    function _sendToHost($host, $port, $method='GET', $path, $data, $useragent=0) {
	// Supply a default method of GET if the one passed was empty
	if (empty($method))
	    $method = 'GET';
	$method = strtoupper($method);
    if ($method == 'GET')
	    $path .= '?' . $data;
   	$buf = "";
	// try to connect to Canada Post server, for 3 seconds
	$fp = @fsockopen($host, $port, $errno, $errstr, 3);
//echo 'errno='.$errno.'<br>errstr='.$errstr . '<br>';
	if ( $fp ) {
	  fputs($fp, "$method $path HTTP/1.1\n");
	  fputs($fp, "Host: $host\n");
	  fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
	  fputs($fp, "Content-length: " . strlen($data) . "\n");
	  if ($useragent)
		fputs($fp, "User-Agent: Zen Cart site\n");
	  fputs($fp, "Connection: close\n\n");
	  if ($method == 'POST')
		fputs($fp, $data);

	  while (!feof($fp))
		$buf .= fgets($fp,128);
	  fclose($fp);

	} else {
     $buf = '<?xml version="1.0" ?><eparcel><error><statusMessage>' . MODULE_SHIPPING_CANADAPOST_COMM_ERROR . ($errno != 0 ? '<br /><strong>' . $errno . ' ' . $errstr . '</strong>' : '') . '</statusMessage></error></eparcel>';
	}

	return $buf;
    }

    function _canadapostGetQuote() {
	$strXML = "<?xml version=\"1.0\" ?>";

	// set package configuration.
	$strXML .= "<eparcel>\n";
	$strXML .= "        <language>" . $this->language . "</language>\n";
	$strXML .= "        <ratesAndServicesRequest>\n";
	$strXML .= "                <merchantCPCID>" . $this->CPCID . "</merchantCPCID>\n";
	$strXML .= "                <fromPostalCode>" . $this->_canadapostOriginPostalCode . "</fromPostalCode>\n";
	$strXML .= "                <turnAroundTime>" . $this->turnaround_time . "</turnAroundTime>\n";
	$strXML .= "                <itemsPrice>" . (string)$this->items_price . "</itemsPrice>\n";
/*
	if ($this->item_dim_type[$i] == 'in') {
		$strXML .= "                <length>" . ($this->item_length[$i] * (25/10)) . "</length>\n";
		$strXML .= "                <width>" . ($this->item_width[$i] * (25/10)) . "</width>\n";
		$strXML .= "                <height>" . ($this->item_height[$i] * (25/10)) . "</height>\n";
	} else {
		$strXML .= "                <length>" . $this->item_length[$i] . "</length>\n";
		$strXML .= "                <width>" . $this->item_width[$i] . "</width>\n";
		$strXML .= "                <height>" . $this->item_height[$i] . "</height>\n";
	}
*/

	// add items information.
	$strXML .= "            <lineItems>\n";
	for ($i=0; $i < $this->items_qty; $i++) {
		$strXML .= "	    <item>\n";
		$strXML .= "                <quantity>" . $this->item_quantity[$i] . "</quantity>\n";
    if ($this->item_weight_type[$i] == 'lbs') {
      $strXML .= "                <weight>" . ($this->item_weight[$i] * (453597/1000000)) . "</weight>\n";
    } else {
      $strXML .= "                <weight>" . $this->item_weight[$i] . "</weight>\n";
    }
    if ($this->item_dim_type[$i] == 'in') {
      $strXML .= "                <length>" . ($this->item_length[$i] * (254/100)) . "</length>\n";
    } else {
      $strXML .= "                <length>" . $this->item_length[$i] . "</length>\n";
    }
    if ($this->item_dim_type[$i] == 'in') {
      $strXML .= "                <width>" . ($this->item_width[$i] * (254/100)) . "</width>\n";
    } else {
      $strXML .= "                <width>" . $this->item_width[$i] . "</width>\n";
    }
    if ($this->item_dim_type[$i] == 'in') {
      $strXML .= "                <height>" . ($this->item_height[$i] * (254/100)) . "</height>\n";
    } else {
      $strXML .= "                <height>" . $this->item_height[$i] . "</height>\n";
    }
    $this->item_description[$i] = str_replace("&", "and", $this->item_description[$i]);
		$strXML .= "                <description>" . $this->item_description[$i] . "</description>\n";
    if ($this->item_ready_to_ship[$i] == '1') { $strXML .= "                <readyToShip/>\n"; }
      $strXML .= "	    </item>\n";
    }
    $strXML .= "           </lineItems>\n";

	// add destination information.
	$strXML .= "               <city>" . $this->dest_city . "</city>\n";
	$strXML .= "               <provOrState>" . $this->dest_province . "</provOrState>\n";
	$strXML .= "               <country>" . $this->dest_country . "</country>\n";
	$strXML .= "               <postalCode>" . $this->dest_zip . "</postalCode>\n";
	$strXML .= "        </ratesAndServicesRequest>\n";
	$strXML .= "</eparcel>\n";

	// print $strXML;
	if ($resultXML = $this->_sendToHost($this->server,$this->port,'POST','',$strXML)) {
		return $this->_parserResult($resultXML);
	} else {
	    return false;
	}
    }


    /*
      Parser XML message returned by canada post server.
    */
    function _parserResult($resultXML) {
    	$statusMessage = substr($resultXML, strpos($resultXML, "<statusMessage>")+strlen("<statusMessage>"), strpos($resultXML, "</statusMessage>")-strlen("<statusMessage>")-strpos($resultXML, "<statusMessage>"));
    	//print "message = $statusMessage";
	$cphandling = substr($resultXML, strpos($resultXML, "<handling>")+strlen("<handling>"), strpos($resultXML, "</handling>")-strlen("<handling>")-strpos($resultXML, "<handling>"));
	$this->handling_cp = $cphandling;
    	if ($statusMessage == 'OK') {
    		$strProduct = substr($resultXML, strpos($resultXML, "<product id=")+strlen("<product id=>"), strpos($resultXML, "</product>")-strlen("<product id=>")-strpos($resultXML, "<product id="));
    		$index = 0;
    		$aryProducts = false;
    		while (strpos($resultXML, "</product>")) {
			$cpnumberofboxes = substr_count($resultXML, "<expediterWeight");
			$this->boxCount = $cpnumberofboxes;
    			$name = substr($resultXML, strpos($resultXML, "<name>")+strlen("<name>"), strpos($resultXML, "</name>")-strlen("<name>")-strpos($resultXML, "<name>"));
    			$rate = substr($resultXML, strpos($resultXML, "<rate>")+strlen("<rate>"), strpos($resultXML, "</rate>")-strlen("<rate>")-strpos($resultXML, "<rate>"));
    			$shippingDate = substr($resultXML, strpos($resultXML, "<shippingDate>")+strlen("<shippingDate>"), strpos($resultXML, "</shippingDate>")-strlen("<shippingDate>")-strpos($resultXML, "<shippingDate>"));
    			$deliveryDate = substr($resultXML, strpos($resultXML, "<deliveryDate>")+strlen("<deliveryDate>"), strpos($resultXML, "</deliveryDate>")-strlen("<deliveryDate>")-strpos($resultXML, "<deliveryDate>"));
    			$deliveryDayOfWeek = substr($resultXML, strpos($resultXML, "<deliveryDayOfWeek>")+strlen("<deliveryDayOfWeek>"), strpos($resultXML, "</deliveryDayOfWeek>")-strlen("<deliveryDayOfWeek>")-strpos($resultXML, "<deliveryDayOfWeek>"));
    			$nextDayAM = substr($resultXML, strpos($resultXML, "<nextDayAM>")+strlen("<nextDayAM>"), strpos($resultXML, "</nextDayAM>")-strlen("<nextDayAM>")-strpos($resultXML, "<nextDayAM>"));
    			$packingID = substr($resultXML, strpos($resultXML, "<packingID>")+strlen("<packingID>"), strpos($resultXML, "</packingID>")-strlen("<packingID>")-strpos($resultXML, "<packingID>"));
    			$aryProducts[$index] = array($name . ', ' . $deliveryDate => $rate);
    			$index++;
    			$resultXML = substr($resultXML, strpos($resultXML, "</product>") + strlen("</product>"));
    		}
    		return $aryProducts;
    	} else {
    		if (strpos($resultXML, "<error>")) return $statusMessage;
    		else return false;
    	}
    }
  }
?>