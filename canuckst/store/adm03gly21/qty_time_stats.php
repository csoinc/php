<?php

require('includes/application_top.php');
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies(); 
 
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/qty_time_stats_stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>

<script type="text/javascript">
  <!--
  function init()
  {
    cssjsmenu('navbar');
    if (document.getElementById)
    {
      var kill = document.getElementById('hoverJS');
      kill.disabled = true;
    }
  }
  // -->
  
 function get_cookie(Name) {
  var search = Name + "="
  var returnvalue = "";
  if (document.cookie.length > 0) {
    offset = document.cookie.indexOf(search)
    // if cookie exists
    if (offset != -1) { 
      offset += search.length
      // set index of beginning of value
      end = document.cookie.indexOf(";", offset);
      // set index of end of cookie value
      if (end == -1) end = document.cookie.length;
      returnvalue=unescape(document.cookie.substring(offset, end))
      }
   }
  return returnvalue;
} 
  
</script>



</head>
<body onLoad="init()">
 
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- bof Date Range Calendar -->
<script language="javascript">
var StartDate = new ctlSpiffyCalendarBox("StartDate", "date_range_form", "start_date", "btnDate1", "<?php echo (($start_date == '') ? '' : ($start_date == '')); ?>", scBTNMODE_CALBTN);
var EndDate = new ctlSpiffyCalendarBox("EndDate", "date_range_form", "end_date", "btnDate1", "<?php echo (($end_date == '') ? '' : ($end_date == '')); ?>", scBTNMODE_CALBTN);
/*
var scBTNMODE_DEFAULT;
var scBTNMODE_CUSTOMBLUE;
var scBTNMODE_CALBTN;
*/
</script>
<!-- eof Date Range Calendar -->



<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2"> <!-- bof Main Table -->
  <tr>
      <td class="pageHeading"><?php echo HEADING_TITLE_QTS; ?></td>
  </tr>
  <tr>
    <td>
    
      <table border="0" width="100%" cellspacing="2" cellpadding="2"> <!-- bof TimeSpan Table -->
     <?php echo zen_draw_form('date_range_form', FILENAME_QTY_TIME_STATS, '', 'GET');
           echo zen_draw_hidden_field('action', 'process');
          ?>
          <input type="hidden" name="ajax_pid" id="ajax_pid" value="">
          <input type="hidden" name="ajax_cid" id="ajax_cid" value="">
        <tr>
          <td class="main" valign="top">
          
            <table cellpadding="2" cellspacing="2" border="0"> <!-- bof Product ID, Name, Model fields table -->
            <tr>
              <td colspan="2">
                <?php echo HEADING_PRODUCT_SELECTION; ?>
              </td>
            </tr>
            <tr>
              <td class="main">
                <?php echo TEXT_PRODUCT_NAME; ?>
              </td>
              <td class="main">
                <input type="text" name="product_name">
              </td>
            </tr>
            <tr>
              <td class="main">
                <?php echo TEXT_PRODUCT_ID; ?>
              </td>
              <td class="main"> 
                <input type="text" name="product_id">
              </td>
            </tr>
            <tr>
              <td class="main">
                <?php echo TEXT_PRODUCT_MODEL; ?>
              </td>
              <td class="main">
                <input type="text" name="product_model">
              </td>
            </tr>
            <!-- <tr> -->
             <!--  <td class="main" colspan="2"> -->
                <?php require('xmodules_product_tree.php'); ?>
              <!-- </td> -->
            <!-- </tr> -->
          </table> <!-- bof Product ID, Name, Model fields table -->
          
          </td>
          <td valign="top">
            
            <table cellpadding="2" cellspacing="2" border="0"> <!-- bof Time Span Table -->
              <tr>
                <td colspan="2">
                  <?php echo HEADING_TIMES; ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo TEXT_THIS_MONTH; ?>
                </td>
                <td>
                  <input type="checkbox" checked name="this_month" value="1">
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo TEXT_LAST_MONTH; ?>
                </td>
                <td>
                  <input type="checkbox" checked name="last_month" value="1">
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo TEXT_LAST_3_MONTH; ?>
                </td>
                <td>
                  <input type="checkbox" checked name="last_3_month" value="1">
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo TEXT_LAST_6_MONTH; ?>
                </td>
                <td>
                  <input type="checkbox" checked name="last_6_month" value="1">
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo TEXT_YEAR; ?>
                </td>
                <td>
                  <input type="checkbox" checked name="year" value="1">
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo TEXT_LAST_YEAR; ?>
                </td>
                <td>
                  <input type="checkbox" checked name="last_year" value="1">
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo TEXT_CUSTOM_RANGE; ?>
                <br>
                  <?php echo TEXT_START_DATE; ?>
                  <br>
                  <script language="javascript">StartDate.writeControl(); StartDate.dateFormat="<?php echo DATE_FORMAT_SPIFFYCAL; ?>";</script>
                  <br>
                  <br>
                  <?php echo TEXT_END_DATE; ?>
                  <br>
                  <script language="javascript">EndDate.writeControl(); EndDate.dateFormat="<?php echo DATE_FORMAT_SPIFFYCAL; ?>";</script>
                </td>
              </tr>
            </table> <!-- eof Time Span Table -->
            
          </td>
          <!--
          <td valign="top">
          
           <table cellpadding="2" cellspacing="2" border="0"> <!-- bof Details Table -->
             <!-- <tr>
                <td colspan="2">
                  <?php //echo TEXT_INCLUDE_DETAILS; ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?php //echo TEXT_QTY; ?>
                </td>
                <td>
                  <input type="checkbox" checked name="qty" value="1">
                </td>
              </tr>
              <tr>
                <td>
                  <?php //echo TEXT_PRICE; ?>
                </td>
                <td>
                  <input type="checkbox" checked name="price" value="1">
                </td>
              </tr> 
              <tr>
                <td>
                  <?php //echo TEXT_TAX; ?>
                </td>
                <td>
                  <input type="checkbox" checked name="tax" value="1">
                </td>
              </tr> 
              </table> <!-- eof Details Table -->
          
        <!--  </td> -->
        
          <td valign="top">
          
          <table cellpadding="2" cellspacing="2" border="0"> <!-- bof Status Table -->
              <tr>
                <td>
                  <?php echo TEXT_STATUS; ?>
                </td>
              </tr>
              <tr>
                <td>
                  <select name="status">
 <option value="0"><?php echo TEXT_ALL; ?></option>
                    <?php
                      $status_sql = "select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . "";
                      $status_result = $db->Execute($status_sql);
                      while (!$status_result->EOF)  {
                         echo '<option value="' . $status_result->fields['orders_status_id'] . '">' . $status_result->fields['orders_status_name'] . '</option>';
                         $status_result->MoveNext();
                      }
                    ?>
                    <!-- <option value="pending"><?php //echo TEXT_PENDING; ?></option>
                    <option value="processing"><?php //echo TEXT_PROCESSING; ?></option>
                    <option value="delivered"><?php //echo TEXT_DELIVERED; ?></option>
                    <option value="update"><?php //echo TEXT_UPDATE; ?></option> -->
                  </select>
                </td>
              </tr>
            </table> <!-- eof Status Table -->
          
          </td>
          
         </tr> 
        
        
       
        <tr>
          <td colspan="4" align="right">
            <?php echo '<input type="submit" value="Show Report">'; ?>
          </td>
        </tr>
        </form>
      </table> <!-- eof TimeSpan Table -->
      </td>
      </tr>
     
      
        <!-- bof results table -->
        <?php
          if ($_GET['action'] == "process") { 
            // Create time span for SQL
            $sql_time_span_this_month = "";
            $sql_time_span_last_month = "";
            $sql_time_span_last_3_month = "";
            $sql_time_span_last_6_month = "";
            $sql_time_span_year = "";
            $sql_time_span_last_year = "";
            $month_now = date('m');
            $year_now = date('Y');
            $day_now = date('d');
            $last_month =  date('Y-m', strtotime('-1 month'));
            $last_3_month =  date('Y-m', strtotime('-3 month'));
            $last_6_month =  date('Y-m', strtotime('-6 month'));
            $last_year =  date('Y', strtotime('-1 year'));
           
            if ($_GET['this_month'] == "1") { // This Month
              $sql_time_span_this_month = $year_now . "-" . $month_now . "-%% %%:%%:%%";
            }
            if ($_GET['last_month'] == "1") { // Last Month
              $sql_time_span_last_month = " o.date_purchased >= '" . $last_month . "-01 00:00:00' AND o.date_purchased  <= '" . $year_now . "-" . $month_now . "-" . $day_now . " 23:59:59'";
            }
            if ($_GET['last_3_month'] == "1") { // Last 3 Months
              $sql_time_span_last_3_month = " o.date_purchased >= '" . $last_3_month . "-01 00:00:00' AND o.date_purchased  <= '" . $year_now . "-" . $month_now . "-" . $day_now . " 23:59:59'";
            }
            if ($_GET['last_6_month'] == "1") { // Last 6 Months
              //$sql_time_span_last_6_month = " o.date_purchased > '" . $last_6_month . "-01 00:00:00' AND o.date_purchased  < '" . $year_now . "-" . $month_now . "-". $day_now . " 23:59:59'";
			 $sql_time_span_last_6_month = " o.date_purchased >= '" . $last_6_month . "-01 00:00:00' AND o.date_purchased  <= '" . $year_now . "-" . $month_now . "-". $day_now . " 23:59:59'";
            }
            if ($_GET['year'] == "1") { // This Year
             // $sql_time_span_this_year = " o.date_purchased > '" . $year_now . "-01-01 00:00:00' AND o.date_purchased  < '" . $year_now . "-" . $month_now . "-". $day_now . " 23:59:59'";
			  $sql_time_span_this_year = " o.date_purchased >= '" . $year_now . "-01-01 00:00:00' AND o.date_purchased  <= '" . $year_now . "-" . $month_now . "-". $day_now . " 23:59:59'";
            }
            if ($_GET['last_year'] == "1") { // Last Year
             //echo $sql_time_span_last_year = " o.date_purchased > '" . $last_year . "-01-01 00:00:00' AND o.date_purchased  < '" . $year_now . "-" . $month_now . "-". $day_now . " 23:59:59'";
			 $sql_time_span_last_year = " o.date_purchased >= '" . $last_year . "-01-01 00:00:00' AND o.date_purchased  <= '" . $last_year . "-" . '12' . "-". '31' . " 23:59:59'";
            }
            
			
            if ($_GET['start_date'] != "" && $_GET['end_date'] != "") { // Custom Date Range
              // Format dates
              $start_date_chunks = explode("/", $_GET['start_date']);
              $start_month = $start_date_chunks[0];
              $start_day = $start_date_chunks[1];
              $start_year = $start_date_chunks[2];
              
              $end_date_chunks = explode("/", $_GET['end_date']);
              $end_month = $end_date_chunks[0];
              $end_day = $end_date_chunks[1];
              $end_year = $end_date_chunks[2];
              
              $sql_time_span_custom = " o.date_purchased > '" . $start_year . "-" . $start_month . "-" . $start_day . " 00:00:00' AND o.date_purchased  < '" . $end_year . "-" . $end_month . "-". $end_day . " 23:59:59'";
            }
            
            // Set SQL Product ID, Name or Model
            $sql_product = "";
            if ($_GET['product_name'] != "") {
              $sql_product = "AND op.products_name = '" . $_GET['product_name']; // Query using product name
              $prod_name = $_GET['product_name'];
            }
            if ($_GET['product_id'] != "") {
              $sql_product = "AND op.products_id = '" . $_GET['product_id']; // Query using product id
              $prod_name = zen_get_products_name($_GET['product_id']);
            }
            if ($_GET['product_model'] != "") {
              $sql_product = "AND op.products_model = '" . $_GET['product_model'];  // Query using product model
               $product_query = "select pd.products_name, pd.products_id from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p where p.products_model = '" . $_GET['product_model'] . "' AND p.products_id = pd.products_id";
                if ($product = $db->Execute($product_query)) {
                  $prod_name = $product->fields['products_name'];
                }
              } 
            
            if ($_GET['ajax_pid'] != "") {
               $sql_product = "AND op.products_id = '" . $_GET['ajax_pid']; // Query using product id from Ajax Tree
               $prod_name = zen_get_products_name($_GET['ajax_pid']);
            }
            
            // Set SQL order status
            $sql_order_status = "";
   if ($_GET['status'] != "0") {
              $sql_order_status = "AND o.orders_status = '" . $_GET['status'] . "'";
            } else if ($_GET['status'] == "0") {
              $sql_order_status = "";
            }
            /*if ($_GET['status'] == "all") { // Order status all
              $sql_order_status = "";
            }
            if ($_GET['status'] == "pending") { // Order status pending
              $sql_order_status = "AND o.orders_status = '1'";
            }
            if ($_GET['status'] == "processing") { // Order status processing
              $sql_order_status = "AND o.orders_status = '2'";
            }
            if ($_GET['status'] == "delivered") { // Order status delivered
              $sql_order_status = "AND o.orders_status = '3'";
            }
            if ($_GET['status'] == "update") { // Order status update
              $sql_order_status = "AND o.orders_status = '4'";
            }*/
            if ($_GET['ajax_cid'] != "") {
              $prod_name = zen_get_category_name($_GET['ajax_cid'], (int)$_SESSION['languages_id']);
            }
           
            $display_details = "op.products_quantity, op.final_price, op.products_tax"
            
        ?>
        <tr>
          <td>
            <?php echo "<b>" . TEXT_REPORT_FOR . " " . $prod_name . "</b>"; ?>
          </td>
        </tr>
        <tr>
          <td>
          <table cellpadding="4" cellspacing="0" border="0" class="display_table" width="100%">
          <tr class="dataTableHeadingRow">
            <td align="center" class="dataTableHeadingContent2"><?php echo HEADING_PRODUCT_ID; ?></td>
            <td align="center" class="dataTableHeadingContent2"><?php echo HEADING_PRODUCT; ?></td>
            <td align="center" class="dataTableHeadingContent2" colspan="2"><?php echo TEXT_THIS_MONTH; ?></td>
            <td align="center" class="dataTableHeadingContent2" colspan="2"><?php echo TEXT_LAST_MONTH; ?></td>
            <td align="center" class="dataTableHeadingContent2" colspan="2"><?php echo TEXT_LAST_3_MONTH; ?></td>
            <td align="center" class="dataTableHeadingContent2" colspan="2"><?php echo TEXT_LAST_6_MONTH; ?></td>
            <td align="center" class="dataTableHeadingContent2" colspan="2"><?php echo TEXT_YEAR; ?></td>
            <td align="center" class="dataTableHeadingContent2" colspan="2"><?php echo TEXT_LAST_YEAR; ?></td>
            <td align="center" class="dataTableHeadingContent2" colspan="2"><?php echo $start_year . "-" . $start_month . "-" . $start_day . " " . TEXT_TO . " " . $end_year . "-" . $end_month . "-". $end_day; ?></td>
          </tr>
          <tr class="dataTableHeadingRow">
             <td align="center" class="dataTableHeadingContent3">&nbsp;</td>
             <td align="center" class="dataTableHeadingContent3">&nbsp;</td>
             <td align="center" class="dataTableHeadingContent3"><?php echo TEXT_QTY; ?></td>
             <td align="center" class="dataTableHeadingContent3"><?php echo TEXT_PRICE; ?></td>
             <td align="center" class="dataTableHeadingContent3"><?php echo TEXT_QTY; ?></td>
             <td align="center" class="dataTableHeadingContent3"><?php echo TEXT_PRICE; ?></td>
             <td align="center" class="dataTableHeadingContent3"><?php echo TEXT_QTY; ?></td>
             <td align="center" class="dataTableHeadingContent3"><?php echo TEXT_PRICE; ?></td>
             <td align="center" class="dataTableHeadingContent3"><?php echo TEXT_QTY; ?></td>
             <td align="center" class="dataTableHeadingContent3"><?php echo TEXT_PRICE; ?></td>
             <td align="center" class="dataTableHeadingContent3"><?php echo TEXT_QTY; ?></td>
             <td align="center" class="dataTableHeadingContent3"><?php echo TEXT_PRICE; ?></td>
             <td align="center" class="dataTableHeadingContent3"><?php echo TEXT_QTY; ?></td>
             <td align="center" class="dataTableHeadingContent3"><?php echo TEXT_PRICE; ?></td>
             <td align="center" class="dataTableHeadingContent3"><?php echo TEXT_QTY; ?></td>
             <td align="center" class="dataTableHeadingContent3"><?php echo TEXT_PRICE; ?></td>
          </tr>
        
          
              <?php // Get products name and ID from input boxes
            
              if ($_GET['product_id'] != "" ) { // Get products name from ID
                $product_query = "select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $_GET['product_id'] . "'";
                if ($product = $db->Execute($product_query)) {
                  $products_name = $product->fields['products_name'];
                  $products_id = $_GET['product_id'];
                }
              }
              if ($_GET['product_model'] != "" ) { // Get products name from model
                $product_query = "select pd.products_name, pd.products_id from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p where p.products_model = '" . $_GET['product_model'] . "' AND p.products_id = pd.products_id";
                if ($product = $db->Execute($product_query)) {
                  $products_name = $product->fields['products_name'];
                  $products_id = $product->fields['products_id'];
                }
              }
              if ($_GET['product_name'] != "" ) { // Get products name from name
               $product_query = "select products_name, products_id from " . TABLE_PRODUCTS_DESCRIPTION . " where products_name LIKE '%" . $_GET['product_name'] . "%'";
                if ($product = $db->Execute($product_query)) {
                  $products_name = $product->fields['products_name'];
                  $products_id = $product->fields['products_id'];
                }
              }
              if ($_GET['ajax_pid'] != "" ) { // Get products name from Ajax Tree product id
                $product_query = "select products_name, products_id from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $_GET['ajax_pid'] . "'";
                if ($product = $db->Execute($product_query)) {
                  $products_name = $product->fields['products_name'];
                  $products_id = $product->fields['products_id'];
                }
              }
              
              // For Selected Ajax Category ID
              $do_cats = false;
              if ($_GET['ajax_cid'] != "") {
                $products_in_cat_array = zen_get_categories_products_list($_GET['ajax_cid']);
                $lp = sizeof($products_in_cat_array);
                if ($lp > 0 ) {
                  $do_cats = true;
                }
              }
              $l = 0;
              $grand_total_qty = 0;
              $grand_total_price = 0;
              $grand_total_tax = 0;
			  
			 
              while ($do_cats == true && $l < $lp) {
                $products_id = $products_in_cat_array[$l];
                $product_query = "select products_name, products_id from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $products_id . "'";
                if ($product = $db->Execute($product_query)) {
                  $products_name = $product->fields['products_name'];
                }
                  ?>
          <tr>         
            <td class="display_right" align="center" bgcolor="#EEEEEE"><?php echo $products_id; ?></td>
            <td class="display_right" bgcolor="#EEEEEE"><b><?php echo $products_name; ?></b>
			<?php
					$sql_option="select products_options_values_name,products_options_values_id  from products_options_values  inner join  products_attributes  ";
					$sql_option.="on products_attributes.options_values_id=products_options_values.products_options_values_id ";
					$sql_option.=" where products_attributes.products_id='$products_id' ";
					$result_option_1=mysql_query($sql_option);
					$numrows_option_1=mysql_num_rows($result_option_1);
					 $ncheck=0;
					 unset($optionid_num);
					if($numrows_option_1>0){
			?>
					<table width="100%" border="0" cellspacing="1" cellpadding="0" >
							 <?php	
								while($data_option_1=mysql_fetch_array($result_option_1)){
								$optionid_num[$ncheck]=$data_option_1['products_options_values_id'];
								$ncheck++;
							 ?>
									 <tr bgcolor="#FFFFFF"><td>- <?php echo $data_option_1['products_options_values_name'].$data_option_1['products_options_values_id']?></td></tr>
							 <?php }?> 
					</table>
				 <?php }  ?> 
 			</td>
              <?php
				 unset($quantity_total_display);
				 unset($price_total_display);
               if ($_GET['this_month'] == "1") { 
                   // The SQL Query for this month
					 if(sizeof($optionid_num)>0){
					 for($incheck=0;$incheck<sizeof($optionid_num);$incheck++){  
								$quantity_total = 0;
								$price_total = 0;
								$tax_total = 0;
								$quantity = 0;
								$price = 0;
								$tax = 0;				  
						  
								  $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op,orders_products_attributes opa  where o.date_purchased LIKE '" . $sql_time_span_this_month . "' AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id AND o.orders_id=opa.orders_id  AND  opa.products_options_values_id=".$optionid_num[$incheck];					  
								  $result = $db->Execute($sql);
								  while (!$result->EOF)  {
									 $quantity = $result->fields['products_quantity'];
									 $price = $result->fields['final_price'];
									 $tax = $result->fields['products_tax'];
									 $tax_total = $tax_total + ($tax * $quantity);
									 $quantity_total = $quantity_total + $quantity;
									 $price_total = $price_total + ($price * $quantity);
									 $result->MoveNext();
								  }
							  $quantity_total_display[$incheck]=$quantity_total;
							  $price_total_display[$incheck]=$price_total;
			 
						  }
				  }else{
						$quantity_total = 0;
						$price_total = 0;
						$tax_total = 0;
						$quantity = 0;
						$price = 0;
						$tax = 0;
					  $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op where o.date_purchased LIKE '" . $sql_time_span_this_month . "' AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id ";					  
					  $result = $db->Execute($sql);
					  while (!$result->EOF)  {
						 $quantity = $result->fields['products_quantity'];
						 $price = $result->fields['final_price'];
						 $tax = $result->fields['products_tax'];
						 $tax_total = $tax_total + ($tax * $quantity);
						 $quantity_total = $quantity_total + $quantity;
						 $price_total = $price_total + ($price * $quantity);
						 $result->MoveNext();
					  }						
				  }
               } else {
                  $quantity_total = TEXT_NOT_SELECTED; // This month not selected
                  $tax_total = TEXT_NOT_SELECTED; // This month not selected
                  $price_total = TEXT_NOT_SELECTED; // This month not selected
               }
 
               ?>
                  <td align="center" class="quantity" valign="top" title="<?php echo TEXT_QTY_FOR . $products_name . " - " . TEXT_THIS_MONTH; ?>">
                    <?php // echo $quantity_total;  // Display Quantity of selected product for this month ?><br>
				  <table width="100%" border="0" cellspacing="1" cellpadding="0">
					 <?php  
					if(sizeof($quantity_total_display)>0){
 					 for($incheck=0;$incheck<sizeof($quantity_total_display);$incheck++){ ?>
							 <tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total_display[$incheck]?></td></tr>
					 <?php }  }else{  
					    if(sizeof($optionid_num)==0) $rowtmp=1; 
						else $rowtmp=sizeof($optionid_num); 
					    for($incheck=0;$incheck<$rowtmp;$incheck++){ ?> 
					 		<tr bgcolor="#FFFFFF" ><td align="center" height="100%" ><?php echo $quantity_total?></td></tr>
					 <?php } }?>
					</table>
                  </td>
                  <td align="center" class="price" valign="top"  title="<?php echo TEXT_PRICE_FOR . $products_name . " - " . TEXT_THIS_MONTH; ?>"><br>
                    <?php // echo $currencies->format($price_total); ?>
				 <table width="100%" border="0" cellspacing="1" cellpadding="0">
					 <?php
					 if(sizeof($quantity_total_display)>0){
					  for($incheck=0;$incheck<sizeof($price_total_display);$incheck++){ ?>
							 <tr bgcolor="#FFFFFF"><td  align="center"><?php echo $currencies->format($price_total_display[$incheck])?></td></tr>
					 <?php } }else{
					    if(sizeof($optionid_num)==0) $rowtmp=1; 
						else $rowtmp=sizeof($optionid_num); 
					    for($incheck=0;$incheck<$rowtmp;$incheck++){
					 ?> 
					 		<tr bgcolor="#FFFFFF"><td align="center"><?php echo $currencies->format($price_total)?></td></tr>
					 <?php } } ?>
					</table>
                  </td>
              <?php
				 unset($quantity_total_display);
				 unset($price_total_display);
               if ($_GET['last_month'] == "1") { 
                   // The SQL Query for last month
				 if(sizeof($optionid_num)>0){
					 for($incheck=0;$incheck<sizeof($optionid_num);$incheck++){  
							$quantity_total = 0;
							$price_total = 0;
							$tax_total = 0;
							$quantity = 0;
							$price = 0;
							$tax = 0;
						  $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op,orders_products_attributes opa  where". $sql_time_span_last_month . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id  AND o.orders_id=opa.orders_id  AND  opa.products_options_values_id=".$optionid_num[$incheck];
						  $result = $db->Execute($sql);
						  while (!$result->EOF)  {
							 $quantity = $result->fields['products_quantity'];
							 $price = $result->fields['final_price'];
							 $tax = $result->fields['products_tax'];
							 $tax_total = $tax_total + ($tax * $quantity);
							 $quantity_total = $quantity_total + $quantity;
							 $price_total = $price_total + ($price * $quantity);
						   $result->MoveNext();
						  }
						 $quantity_total_display[$incheck]=$quantity_total;
						 $price_total_display[$incheck]=$price_total;				  
				     }
				 }else{
						  
						  $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op where". $sql_time_span_last_month . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id";
						  $result = $db->Execute($sql);
						  while (!$result->EOF)  {
							 $quantity = $result->fields['products_quantity'];
							 $price = $result->fields['final_price'];
							 $tax = $result->fields['products_tax'];
							 $tax_total = $tax_total + ($tax * $quantity);
							 $quantity_total = $quantity_total + $quantity;
							 $price_total = $price_total + ($price * $quantity);
						   $result->MoveNext();
						  }
				 }	
               } else {
                  $quantity_total = TEXT_NOT_SELECTED; // Last month not selected
                   $tax_total = TEXT_NOT_SELECTED; // This month not selected
                  $price_total = TEXT_NOT_SELECTED; // This month not selected
               }
               ?>
                  <td align="center" class="quantity"  valign="top" title="<?php echo TEXT_QTY_FOR . $products_name . " - " . TEXT_LAST_MONTH; ?>">
                    <?php // echo $quantity_total;   // Display Quantity of selected product for this month   ?><br>
				<table width="100%" border="0" cellspacing="1" cellpadding="0"  >
					 <?php  
					if(sizeof($quantity_total_display)>0){
 					 for($incheck=0;$incheck<sizeof($quantity_total_display);$incheck++){ ?>
							 <tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total_display[$incheck]?></td></tr>
					 <?php }  }else{
					    if(sizeof($optionid_num)==0) $rowtmp=1; 
						else $rowtmp=sizeof($optionid_num); 
					    for($incheck=0;$incheck<$rowtmp;$incheck++){					 
					 	?> 
					 		<tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total?></td></tr>
					 <?php } } ?>
					</table>
                  </td>
                  <td align="center" class="price"  valign="top" title="<?php echo TEXT_PRICE_FOR . $products_name . " - " . TEXT_LAST_MONTH; ?>">
                    <?php // echo $currencies->format($price_total); ?><br>
				 <table width="100%" border="0" cellspacing="1" cellpadding="0">
					 <?php
					 if(sizeof($quantity_total_display)>0){
					  for($incheck=0;$incheck<sizeof($price_total_display);$incheck++){ ?>
							 <tr bgcolor="#FFFFFF"><td  align="center"><?php echo $currencies->format($price_total_display[$incheck])?></td></tr>
					 <?php } }else{ 
					    if(sizeof($optionid_num)==0) $rowtmp=1; 
						else $rowtmp=sizeof($optionid_num); 
					    for($incheck=0;$incheck<$rowtmp;$incheck++){					 
					 ?> 
					 		<tr bgcolor="#FFFFFF"><td align="center"><?php echo $currencies->format($price_total)?></td></tr>
					 <?php } } ?>
					</table>					
                  </td>
            <?php
				 unset($quantity_total_display);
				 unset($price_total_display);			
               if ($_GET['last_3_month'] == "1") { 
                   // The SQL Query for last 3 month
				 if(sizeof($optionid_num)>0){
					 for($incheck=0;$incheck<sizeof($optionid_num);$incheck++){  
						$quantity_total = 0;
						$price_total = 0;
						$tax_total = 0;
						$quantity = 0;
						$price = 0;
						$tax = 0;
						 $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op,orders_products_attributes opa  where". $sql_time_span_last_3_month . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id  AND o.orders_id=opa.orders_id  AND  opa.products_options_values_id=".$optionid_num[$incheck];
						  $result = $db->Execute($sql);
						  while (!$result->EOF)  {
							 $quantity = $result->fields['products_quantity'];
							 $price = $result->fields['final_price'];
							 $tax = $result->fields['products_tax'];
							 $tax_total = $tax_total + ($tax * $quantity);
							 $quantity_total = $quantity_total + $quantity;
							 $price_total = $price_total + ($price * $quantity);
						   $result->MoveNext();
						  }
					  $quantity_total_display[$incheck]=$quantity_total;
					  $price_total_display[$incheck]=$price_total;

				   }			
							
				}else{
				
						$quantity_total = 0;
						$price_total = 0;
						$tax_total = 0;
						$quantity = 0;
						$price = 0;
						$tax = 0;			
					   $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op where". $sql_time_span_last_3_month . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id";
					  $result = $db->Execute($sql);
					  while (!$result->EOF)  {
						 $quantity = $result->fields['products_quantity'];
						 $price = $result->fields['final_price'];
						 $tax = $result->fields['products_tax'];
						 $tax_total = $tax_total + ($tax * $quantity);
						 $quantity_total = $quantity_total + $quantity;
						 $price_total = $price_total + ($price * $quantity);
					   $result->MoveNext();
					  }
				  
				}  
               } else {
                  $quantity_total = TEXT_NOT_SELECTED; // Last 3 month not selected
                   $tax_total = TEXT_NOT_SELECTED; // This month not selected
                  $price_total = TEXT_NOT_SELECTED; // This month not selected
               }
               ?>
                  <td align="center" class="quantity"  valign="top" title="<?php echo TEXT_QTY_FOR . $products_name . " - " . TEXT_LAST_3_MONTH; ?>">
                    <?php // echo $quantity_total; // Display Quantity of selected product for this month  ?><br>
					 <table width="100%" border="0" cellspacing="1" cellpadding="0"  >
					 <?php  
					if(sizeof($quantity_total_display)>0){
 					 for($incheck=0;$incheck<sizeof($quantity_total_display);$incheck++){ ?>
							 <tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total_display[$incheck]?></td></tr>
					 <?php }  }else{
					    if(sizeof($optionid_num)==0) $rowtmp=1; 
						else $rowtmp=sizeof($optionid_num); 
					    for($incheck=0;$incheck<$rowtmp;$incheck++){					 
					 ?> 
					 		<tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total?></td></tr>
					 <?php } } ?>
					</table>
                  </td>
                  <td align="center" class="price" valign="top" title="<?php echo TEXT_PRICE_FOR . $products_name . " - " . TEXT_LAST_3_MONTH; ?>">
                    <?php // echo $currencies->format($price_total); ?><br>
					 <table width="100%" border="0" cellspacing="1" cellpadding="0">
					 <?php
					 if(sizeof($quantity_total_display)>0){
					  for($incheck=0;$incheck<sizeof($price_total_display);$incheck++){ ?>
							 <tr bgcolor="#FFFFFF"><td  align="center"><?php echo $currencies->format($price_total_display[$incheck])?></td></tr>
					 <?php } }else{ 
					    if(sizeof($optionid_num)==0) $rowtmp=1; 
						else $rowtmp=sizeof($optionid_num); 
					    for($incheck=0;$incheck<$rowtmp;$incheck++){					 
					 ?> 
					 		<tr bgcolor="#FFFFFF"><td align="center"><?php echo $currencies->format($price_total)?></td></tr>
					 <?php } }?>
					</table>
                  </td>
            <?php
				 unset($quantity_total_display);
				 unset($price_total_display);			
               if ($_GET['last_6_month'] == "1") { 
                   // The SQL Query for last 6 month
				 if(sizeof($optionid_num)>0){
					 for($incheck=0;$incheck<sizeof($optionid_num);$incheck++){  
						$quantity_total = 0;
						$price_total = 0;
						$tax_total = 0;
						$quantity = 0;
						$price = 0;
						$tax = 0;
						
						  $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op,orders_products_attributes opa where". $sql_time_span_last_6_month . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id  AND o.orders_id=opa.orders_id  AND  opa.products_options_values_id=".$optionid_num[$incheck];
						  $result = $db->Execute($sql);
						  while (!$result->EOF)  {
							 $quantity = $result->fields['products_quantity'];
							 $price = $result->fields['final_price'];
							 $tax = $result->fields['products_tax'];
							 $tax_total = $tax_total + ($tax * $quantity);
							 $quantity_total = $quantity_total + $quantity;
							 $price_total = $price_total + ($price * $quantity);
						   $result->MoveNext();
						  }
						 $quantity_total_display[$incheck]=$quantity_total;
					     $price_total_display[$incheck]=$price_total;
					  }	
					}else{	
						  $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op where". $sql_time_span_last_6_month . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id";
						  $result = $db->Execute($sql);
						  while (!$result->EOF)  {
							 $quantity = $result->fields['products_quantity'];
							 $price = $result->fields['final_price'];
							 $tax = $result->fields['products_tax'];
							 $tax_total = $tax_total + ($tax * $quantity);
							 $quantity_total = $quantity_total + $quantity;
							 $price_total = $price_total + ($price * $quantity);
						   $result->MoveNext();
						  }
				  
				  } 
               } else {
                  $quantity_total = TEXT_NOT_SELECTED; // Last 6 month not selected
                   $tax_total = TEXT_NOT_SELECTED; // This month not selected
                  $price_total = TEXT_NOT_SELECTED; // This month not selected
               }
               ?>
                  <td align="center" class="quantity" valign="top" title="<?php echo TEXT_QTY_FOR . $products_name . " - " . TEXT_LAST_6_MONTH; ?>">
                    <?php // echo $quantity_total;  // Display Quantity of selected product for this month  ?><br>
					 <table width="100%" border="0" cellspacing="1" cellpadding="0"  >
					 <?php  
					if(sizeof($quantity_total_display)>0){
 					 for($incheck=0;$incheck<sizeof($quantity_total_display);$incheck++){ ?>
							 <tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total_display[$incheck]?></td></tr>
					 <?php }  }else{ 
					    if(sizeof($optionid_num)==0) $rowtmp=1; 
						else $rowtmp=sizeof($optionid_num); 
					    for($incheck=0;$incheck<$rowtmp;$incheck++){					 
					 ?> 
					 		<tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total?></td></tr>
					 <?php } } ?>
					</table>					
                  </td>
                  <td align="center" class="price" valign="top" title="<?php echo TEXT_PRICE_FOR . $products_name . " - " . TEXT_LAST_6_MONTH; ?>">
                    <?php // echo $currencies->format($price_total); ?><br>
					 <table width="100%" border="0" cellspacing="1" cellpadding="0">
					 <?php
					 if(sizeof($quantity_total_display)>0){
					  for($incheck=0;$incheck<sizeof($price_total_display);$incheck++){ ?>
							 <tr bgcolor="#FFFFFF"><td  align="center"><?php echo $currencies->format($price_total_display[$incheck])?></td></tr>
					 <?php } }else{ 
					    if(sizeof($optionid_num)==0) $rowtmp=1; 
						else $rowtmp=sizeof($optionid_num); 
					    for($incheck=0;$incheck<$rowtmp;$incheck++){					 
					 ?> 
					 		<tr bgcolor="#FFFFFF"><td align="center"><?php echo $currencies->format($price_total)?></td></tr>
					 <?php } }?>
					</table>					
                  </td>
            <?php
				 unset($quantity_total_display);
				 unset($price_total_display);			
               if ($_GET['year'] == "1") { 
                   // The SQL Query for this year
				 if(sizeof($optionid_num)>0){
					 for($incheck=0;$incheck<sizeof($optionid_num);$incheck++){  
						$quantity_total = 0;
						$price_total = 0;
						$tax_total = 0;
						$quantity = 0;
						$price = 0;
						$tax = 0;
						  $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op,orders_products_attributes opa where". $sql_time_span_this_year . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id AND o.orders_id=opa.orders_id  AND  opa.products_options_values_id=".$optionid_num[$incheck];
						  $result = $db->Execute($sql);
						  while (!$result->EOF)  {
							 $quantity = $result->fields['products_quantity'];
							 $price = $result->fields['final_price'];
							 $tax = $result->fields['products_tax'];
							 $tax_total = $tax_total + ($tax * $quantity);
							 $quantity_total = $quantity_total + $quantity;
							 $price_total = $price_total + ($price * $quantity);
						   $result->MoveNext();
						  }
 						 $quantity_total_display[$incheck]=$quantity_total;
					     $price_total_display[$incheck]=$price_total; 						
					}
				}else{		
						   $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op where". $sql_time_span_this_year . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id";
						  $result = $db->Execute($sql);
						  while (!$result->EOF)  {
							 $quantity = $result->fields['products_quantity'];
							 $price = $result->fields['final_price'];
							 $tax = $result->fields['products_tax'];
							 $tax_total = $tax_total + ($tax * $quantity);
							 $quantity_total = $quantity_total + $quantity;
							 $price_total = $price_total + ($price * $quantity);
						   $result->MoveNext();
						  }
				  
				  } 
               } else {
                  $quantity_total = TEXT_NOT_SELECTED; // This year not selected
                   $tax_total = TEXT_NOT_SELECTED; // This month not selected
                  $price_total = TEXT_NOT_SELECTED; // This month not selected
               }
               ?>
                  <td align="center" class="quantity" valign="top" title="<?php echo TEXT_QTY_FOR . $products_name . " - " . TEXT_YEAR; ?>">
                    <?php // echo $quantity_total;  // Display Quantity of selected product for this month  ?><br>

 					 <table width="100%" border="0" cellspacing="1" cellpadding="0"  >
					 <?php  
					if(sizeof($quantity_total_display)>0){
 					 for($incheck=0;$incheck<sizeof($quantity_total_display);$incheck++){ ?>
							 <tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total_display[$incheck]?></td></tr>
					 <?php }  }else{ 
					    if(sizeof($optionid_num)==0) $rowtmp=1; 
						else $rowtmp=sizeof($optionid_num); 
					    for($incheck=0;$incheck<$rowtmp;$incheck++){					 
					 ?> 
					 		<tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total?></td></tr>
					 <?php } } ?>
					 </table>					
					
                  </td>
                  <td align="center" class="price" valign="top" title="<?php echo TEXT_PRICE_FOR . $products_name . " - " . TEXT_YEAR; ?>">
                    <?php // echo $currencies->format($price_total); ?><br>
					 <table width="100%" border="0" cellspacing="1" cellpadding="0">
					 <?php
					 if(sizeof($quantity_total_display)>0){
					  for($incheck=0;$incheck<sizeof($price_total_display);$incheck++){ ?>
							 <tr bgcolor="#FFFFFF"><td  align="center"><?php echo $currencies->format($price_total_display[$incheck])?></td></tr>
					 <?php } }else{
					    if(sizeof($optionid_num)==0) $rowtmp=1; 
						else $rowtmp=sizeof($optionid_num); 
					    for($incheck=0;$incheck<$rowtmp;$incheck++){					 
					  ?> 
					 		<tr bgcolor="#FFFFFF"><td align="center"><?php echo $currencies->format($price_total)?></td></tr>
					 <?php }  }?>
					 </table>					
                  </td>
            <?php
				 unset($quantity_total_display);
				 unset($price_total_display);			
               if ($_GET['last_year'] == "1") { 
                   // The SQL Query for last year
				 if(sizeof($optionid_num)>0){
					 for($incheck=0;$incheck<sizeof($optionid_num);$incheck++){  
						$quantity_total = 0;
						$price_total = 0;
						$tax_total = 0;
						$quantity = 0;
						$price = 0;
						$tax = 0;
						
						   $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op,orders_products_attributes opa where". $sql_time_span_last_year . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id AND o.orders_id=opa.orders_id  AND  opa.products_options_values_id=".$optionid_num[$incheck];
						  $result = $db->Execute($sql);
						  while (!$result->EOF)  {
							 $quantity = $result->fields['products_quantity'];
							 $price = $result->fields['final_price'];
							 $tax = $result->fields['products_tax'];
							 $tax_total = $tax_total + ($tax * $quantity);
							 $quantity_total = $quantity_total + $quantity;
							 $price_total = $price_total + ($price * $quantity);
						   $result->MoveNext();
						  }
 						 $quantity_total_display[$incheck]=$quantity_total;
					     $price_total_display[$incheck]=$price_total; 								  
						
				  }			
				}else{		
						   $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op where". $sql_time_span_last_year . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id";
						  $result = $db->Execute($sql);
						  while (!$result->EOF)  {
							 $quantity = $result->fields['products_quantity'];
							 $price = $result->fields['final_price'];
							 $tax = $result->fields['products_tax'];
							 $tax_total = $tax_total + ($tax * $quantity);
							 $quantity_total = $quantity_total + $quantity;
							 $price_total = $price_total + ($price * $quantity);
						   $result->MoveNext();
						  }
				 }  
               } else {
                  $quantity_total = TEXT_NOT_SELECTED; // Last year not selected
               }
               ?>
                  <td align="center" class="quantity" valign="top" title="<?php echo TEXT_QTY_FOR . $products_name . " - " . TEXT_LAST_YEAR; ?>">
                    <?php // echo $quantity_total;  // Display Quantity of selected product for this month   ?><br>
 					 <table width="100%" border="0" cellspacing="1" cellpadding="0"  >
					 <?php  
					if(sizeof($quantity_total_display)>0){
 					 for($incheck=0;$incheck<sizeof($quantity_total_display);$incheck++){ ?>
							 <tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total_display[$incheck]?></td></tr>
					 <?php }  }else{ 
					    if(sizeof($optionid_num)==0) $rowtmp=1; 
						else $rowtmp=sizeof($optionid_num); 
					    for($incheck=0;$incheck<$rowtmp;$incheck++){					 
					 ?> 
					 		<tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total?></td></tr>
					 <?php }  }?>
					 </table>					
                  </td>
                  <td align="center" class="price" valign="top" title="<?php echo TEXT_PRICE_FOR . $products_name . " - " . TEXT_LAST_YEAR; ?>">
                    <?php // echo $currencies->format($price_total); ?><br>
					 <table width="100%" border="0" cellspacing="1" cellpadding="0">
					 <?php
					 if(sizeof($quantity_total_display)>0){
					  for($incheck=0;$incheck<sizeof($price_total_display);$incheck++){ ?>
							 <tr bgcolor="#FFFFFF"><td  align="center"><?php echo $currencies->format($price_total_display[$incheck])?></td></tr>
					 <?php } }else{ 
					    if(sizeof($optionid_num)==0) $rowtmp=1; 
						else $rowtmp=sizeof($optionid_num); 
					    for($incheck=0;$incheck<$rowtmp;$incheck++){					 
					 ?> 
					 		<tr bgcolor="#FFFFFF"><td align="center"><?php echo $currencies->format($price_total)?></td></tr>
					 <?php } }?>
					 </table>					
                  </td>
            <?php
				 unset($quantity_total_display);
				 unset($price_total_display);			
               if ($_GET['start_date'] != "" && $_GET['end_date'] != "") { 
                   // The SQL Query for custom date range
				 if(sizeof($optionid_num)>0){
					 for($incheck=0;$incheck<sizeof($optionid_num);$incheck++){  
						$quantity_total = 0;
						$price_total = 0;
						$tax_total = 0;
						$quantity = 0;
						$price = 0;
						$tax = 0;
						
					   $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op,orders_products_attributes opa where". $sql_time_span_custom . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id  AND o.orders_id=opa.orders_id  AND  opa.products_options_values_id=".$optionid_num[$incheck];
					  $result = $db->Execute($sql);
					  while (!$result->EOF)  {
						 $quantity = $result->fields['products_quantity'];
						 $price = $result->fields['final_price'];
						 $tax = $result->fields['products_tax'];
						 $tax_total = $tax_total + ($tax * $quantity);
						 $quantity_total = $quantity_total + $quantity;
						 $price_total = $price_total + ($price * $quantity);
					   $result->MoveNext();
					  }						
				 		 $quantity_total_display[$incheck]=$quantity_total;
					     $price_total_display[$incheck]=$price_total;
				 }		
						
				}else{		
					   $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op where". $sql_time_span_custom . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id";
					  $result = $db->Execute($sql);
					  while (!$result->EOF)  {
						 $quantity = $result->fields['products_quantity'];
						 $price = $result->fields['final_price'];
						 $tax = $result->fields['products_tax'];
						 $tax_total = $tax_total + ($tax * $quantity);
						 $quantity_total = $quantity_total + $quantity;
						 $price_total = $price_total + ($price * $quantity);
					   $result->MoveNext();
					  }
				 }	  
               } else {
                  $quantity_total = TEXT_NOT_SELECTED; // Last year not selected
                  $price_total = 0;
                  $tax_total = 0;
               }
               ?>
                  <td align="center" class="quantity" valign="top" title="<?php echo TEXT_QTY_FOR . $products_name . " - " . TEXT_CUSTOM_RANGE; ?>">
                    <?php // echo $quantity_total;   // Display Quantity of selected product for this month  ?><br>
 					 <table width="100%" border="0" cellspacing="1" cellpadding="0"  >
					 <?php  
					if(sizeof($quantity_total_display)>0){
 					 for($incheck=0;$incheck<sizeof($quantity_total_display);$incheck++){ ?>
							 <tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total_display[$incheck]?></td></tr>
					 <?php }  }else{ 
					    if(sizeof($optionid_num)==0) $rowtmp=1; 
						else $rowtmp=sizeof($optionid_num); 
					    for($incheck=0;$incheck<$rowtmp;$incheck++){					 
					 ?> 
					 		<tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total?></td></tr>
					 <?php } } ?>
					 </table>
					
                  </td>
                  <td align="center" class="price" valign="top" title="<?php echo TEXT_PRICE_FOR . $products_name . " - " . TEXT_CUSTOM_RANGE; ?>">
                    <?php // echo $currencies->format($price_total); ?><br>
					 <table width="100%" border="0" cellspacing="1" cellpadding="0">
					 <?php
					 if(sizeof($quantity_total_display)>0){
					  for($incheck=0;$incheck<sizeof($price_total_display);$incheck++){ ?>
							 <tr bgcolor="#FFFFFF"><td  align="center"><?php echo $currencies->format($price_total_display[$incheck])?></td></tr>
					 <?php } }else{ 
					    if(sizeof($optionid_num)==0) $rowtmp=1; 
						else $rowtmp=sizeof($optionid_num); 
					    for($incheck=0;$incheck<$rowtmp;$incheck++){?> 
					 		<tr bgcolor="#FFFFFF"><td align="center"><?php echo $currencies->format($price_total)?></td></tr>
					 <?php } } ?>
					 </table>					
                  </td>
          </tr>
                <?php
         
                $l++;
                }
                ?>
          
          <?php // For Selected Product 
            if ($do_cats == false) {
          ?>
          <tr>
            <td class="display_right" align="center" bgcolor="#EEEEEE"><?php echo $products_id; ?></td>
            <td class="display_right" bgcolor="#EEEEEE"><strong><?php echo $products_name; ?></strong>
			<?php
					$sql_option="select products_options_values_name,products_options_values_id  from products_options_values  inner join  products_attributes  ";
					$sql_option.="on products_attributes.options_values_id=products_options_values.products_options_values_id ";
					$sql_option.=" where products_attributes.products_id='$products_id' ";
					$result_option_2=mysql_query($sql_option);
				 
					if(mysql_num_rows($result_option_2)>0){
			?>
					<table width="100%" border="0" cellspacing="1" cellpadding="0" >
					 <?php	$ncheck=0;
						while($data_option_2=mysql_fetch_array($result_option_2)){
						$optionid_num[$ncheck]=$data_option_2['products_options_values_id'];
					 ?>
			 <tr bgcolor="#FFFFFF">
                <td>
                  - <?php echo $data_option_2['products_options_values_name']?>
                </td>
              </tr>
					 <?php $ncheck++; } ?> 
					</table>
				 <?php }  ?> 
			</td>
              <?php
			   unset($quantity_total_display);
			   unset($price_total_display);
               if ($_GET['this_month'] == "1") { 
                   // The SQL Query for this month
 				 if(sizeof($optionid_num)>0){	   
						 for($incheck=0;$incheck<sizeof($optionid_num);$incheck++){  
							  $quantity_total = 0;
							  $price_total = 0;
							  $tax_total = 0;
							  $quantity = 0;
							  $price = 0;
							  $tax = 0;				  
								  $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op,orders_products_attributes opa  where o.date_purchased LIKE '" . $sql_time_span_this_month . "' AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id and o.orders_id=opa.orders_id  AND  opa.products_options_values_id=".$optionid_num[$incheck];
								  $result = $db->Execute($sql);
								  while (!$result->EOF)  {
									 $quantity = $result->fields['products_quantity'];
									 $price = $result->fields['final_price'];
									 $tax = $result->fields['products_tax'];
									 $tax_total = $tax_total + ($tax * $quantity);
									 $quantity_total = $quantity_total + $quantity;
									 $price_total = $price_total + ($price * $quantity);
								   $result->MoveNext();
								  }
							  $quantity_total_display[$incheck]=$quantity_total;
							  $price_total_display[$incheck]=$price_total;
						}		  
			    	
				}else{
							  $quantity_total = 0;
							  $price_total = 0;
							  $tax_total = 0;
							  $quantity = 0;
							  $price = 0;
							  $tax = 0;				  
							  $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op  where o.date_purchased LIKE '" . $sql_time_span_this_month . "' AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id";
							  $result = $db->Execute($sql);
							  while (!$result->EOF)  {
								 $quantity = $result->fields['products_quantity'];
								 $price = $result->fields['final_price'];
								 $tax = $result->fields['products_tax'];
								 $tax_total = $tax_total + ($tax * $quantity);
								 $quantity_total = $quantity_total + $quantity;
								 $price_total = $price_total + ($price * $quantity);
							   $result->MoveNext();
							  }
				}
               } else {
                  $quantity_total = TEXT_NOT_SELECTED; // This month not selected
                  $tax_total = TEXT_NOT_SELECTED; // This month not selected
                  $price_total = TEXT_NOT_SELECTED; // This month not selected
               }
               ?>
                  <td align="center" class="quantity" valign="top" title="<?php echo TEXT_QTY_FOR . $products_name . " - " . TEXT_THIS_MONTH; ?>">
                    <?php // echo $quantity_total; // Display Quantity of selected product for this month    ?><br>
						  <table width="100%" border="0" cellspacing="1" cellpadding="0">
							 <?php  
							if(sizeof($quantity_total_display)>0){
							 for($incheck=0;$incheck<sizeof($quantity_total_display);$incheck++){ ?>
									 <tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total_display[$incheck]?></td></tr>
							 <?php }  }else{  
								if(sizeof($optionid_num)==0) $rowtmp=1; 
								else $rowtmp=sizeof($optionid_num); 
								for($incheck=0;$incheck<$rowtmp;$incheck++){ ?> 
									<tr bgcolor="#FFFFFF" ><td align="center" height="100%" ><?php echo $quantity_total?></td></tr>
							 <?php } }?>
							</table>							
                  </td>
                  <td align="center" class="price" valign="top" title="<?php echo TEXT_PRICE_FOR . $products_name . " - " . TEXT_THIS_MONTH; ?>">
                    <?php // echo $currencies->format($price_total); ?><br>
					 <table width="100%" border="0" cellspacing="1" cellpadding="0">
						 <?php
						 if(sizeof($quantity_total_display)>0){
						  for($incheck=0;$incheck<sizeof($price_total_display);$incheck++){ ?>
								 <tr bgcolor="#FFFFFF"><td  align="center"><?php echo $currencies->format($price_total_display[$incheck])?></td></tr>
						 <?php } }else{
							if(sizeof($optionid_num)==0) $rowtmp=1; 
							else $rowtmp=sizeof($optionid_num); 
							for($incheck=0;$incheck<$rowtmp;$incheck++){
						 ?> 
								<tr bgcolor="#FFFFFF"><td align="center"><?php echo $currencies->format($price_total)?></td></tr>
						 <?php } } ?>
						</table>
                  </td>
              <?php
				 unset($quantity_total_display);
				 unset($price_total_display);
               if ($_GET['last_month'] == "1") { 
                   // The SQL Query for last month
				 if(sizeof($optionid_num)>0){				   
					 for($incheck=0;$incheck<sizeof($optionid_num);$incheck++){  
						  $quantity_total = 0;
						  $price_total = 0;
						  $tax_total = 0;				 
						  $quantity = 0;
						  $price = 0;
						  $tax = 0;
										  
						  $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op,orders_products_attributes opa where". $sql_time_span_last_month . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id and o.orders_id=opa.orders_id  AND  opa.products_options_values_id=".$optionid_num[$incheck];
						  $result = $db->Execute($sql);
						  while (!$result->EOF)  {
							 $quantity = $result->fields['products_quantity'];
							 $price = $result->fields['final_price'];
							 $tax = $result->fields['products_tax'];
							 
							 $tax_total = $tax_total + ($tax * $quantity);
							 $quantity_total = $quantity_total + $quantity;
							 $price_total = $price_total + ($price * $quantity);
							 
							 $result->MoveNext();
						  }
						  $quantity_total_display[$incheck]=$quantity_total;
						  $price_total_display[$incheck]=$price_total;
					 } 
				 }else{
						  $quantity_total = 0;
						  $price_total = 0;
						  $tax_total = 0;				 
						  $quantity = 0;
						  $price = 0;
						  $tax = 0;
										  
						  $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op where". $sql_time_span_last_month . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id ";
						  $result = $db->Execute($sql);
						  while (!$result->EOF)  {
							 $quantity = $result->fields['products_quantity'];
							 $price = $result->fields['final_price'];
							 $tax = $result->fields['products_tax'];
							 $tax_total = $tax_total + ($tax * $quantity);
							 $quantity_total = $quantity_total + $quantity;
							 $price_total = $price_total + ($price * $quantity);
							 $result->MoveNext();
						  }				 
				 } 
               } else {
                  $quantity_total = TEXT_NOT_SELECTED; // Last month not selected
                  $tax_total = TEXT_NOT_SELECTED; // This month not selected
                  $price_total = TEXT_NOT_SELECTED; // This month not selected
               }
 
               ?>
                  <td align="center" class="quantity"  valign="top"><br>
                    <?php // echo $quantity_total;  // Display Quantity of selected product for this month ?>
					  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						 <?php  
						if(sizeof($quantity_total_display)>0){
						 for($incheck=0;$incheck<sizeof($quantity_total_display);$incheck++){ ?>
								 <tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total_display[$incheck]?></td></tr>
						 <?php }  }else{  
							if(sizeof($optionid_num)==0) $rowtmp=1; 
							else $rowtmp=sizeof($optionid_num); 
							for($incheck=0;$incheck<$rowtmp;$incheck++){ ?> 
								<tr bgcolor="#FFFFFF" ><td align="center" height="100%" ><?php echo $quantity_total?></td></tr>
						 <?php } }?>
						</table>					
                  </td>
                  <td align="center" class="price" valign="top"><br>
                    <?php // echo $currencies->format($price_total); ?>
					 <table width="100%" border="0" cellspacing="1" cellpadding="0">
						 <?php
						 if(sizeof($quantity_total_display)>0){
						  for($incheck=0;$incheck<sizeof($price_total_display);$incheck++){ ?>
								 <tr bgcolor="#FFFFFF"><td  align="center"><?php echo $currencies->format($price_total_display[$incheck])?></td></tr>
						 <?php } }else{
							if(sizeof($optionid_num)==0) $rowtmp=1; 
							else $rowtmp=sizeof($optionid_num); 
							for($incheck=0;$incheck<$rowtmp;$incheck++){
						 ?> 
								<tr bgcolor="#FFFFFF"><td align="center"><?php echo $currencies->format($price_total)?></td></tr>
						 <?php } } ?>
						</table>					
                  </td>
            <?php
				 unset($quantity_total_display);
				 unset($price_total_display);			
               if ($_GET['last_3_month'] == "1") { 
                   // The SQL Query for last 3 month
				 if(sizeof($optionid_num)>0){
					 for($incheck=0;$incheck<sizeof($optionid_num);$incheck++){  
						$quantity_total = 0;
						$price_total = 0;
						$tax_total = 0;
						$quantity = 0;
						$price = 0;
						$tax = 0;
						   $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op,orders_products_attributes opa where". $sql_time_span_last_3_month . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id  AND o.orders_id=opa.orders_id  AND  opa.products_options_values_id=".$optionid_num[$incheck];
						  $result = $db->Execute($sql);
						  while (!$result->EOF)  {
							 $quantity = $result->fields['products_quantity'];
							 $price = $result->fields['final_price'];
							 $tax = $result->fields['products_tax'];
							 $tax_total = $tax_total + ($tax * $quantity);
							 $quantity_total = $quantity_total + $quantity;
							 $price_total = $price_total + ($price * $quantity);
						   $result->MoveNext();
						  }
 						 $quantity_total_display[$incheck]=$quantity_total;
					     $price_total_display[$incheck]=$price_total; 									  
						  
					}
					
				  }else{
						  $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op where". $sql_time_span_last_3_month . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id";
						  $result = $db->Execute($sql);
						  while (!$result->EOF)  {
							 $quantity = $result->fields['products_quantity'];
							 $price = $result->fields['final_price'];
							 $tax = $result->fields['products_tax'];
							 $tax_total = $tax_total + ($tax * $quantity);
							 $quantity_total = $quantity_total + $quantity;
							 $price_total = $price_total + ($price * $quantity);
						   $result->MoveNext();
						  }
				  
				  
				  }	  
               } else {
                  $quantity_total = TEXT_NOT_SELECTED; // Last 3 month not selected
                   $tax_total = TEXT_NOT_SELECTED; // This month not selected
                  $price_total = TEXT_NOT_SELECTED; // This month not selected
               }
               ?>
                  <td align="center" class="quantity"  valign="top" title="<?php echo TEXT_QTY_FOR . $products_name . " - " . TEXT_LAST_3_MONTH; ?>">
                    <?php // echo $quantity_total;   // Display Quantity of selected product for this month ?><br>
					  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						 <?php  
						if(sizeof($quantity_total_display)>0){
						 for($incheck=0;$incheck<sizeof($quantity_total_display);$incheck++){ ?>
								 <tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total_display[$incheck]?></td></tr>
						 <?php }  }else{  
							if(sizeof($optionid_num)==0) $rowtmp=1; 
							else $rowtmp=sizeof($optionid_num); 
							for($incheck=0;$incheck<$rowtmp;$incheck++){ ?> 
								<tr bgcolor="#FFFFFF" ><td align="center" height="100%" ><?php echo $quantity_total?></td></tr>
						 <?php } }?>
						</table>					
                  </td>
                  <td align="center" class="price" valign="top" title="<?php echo TEXT_PRICE_FOR . $products_name . " - " . TEXT_LAST_3_MONTH; ?>">
                    <?php // echo $currencies->format($price_total); ?><br>
				 <table width="100%" border="0" cellspacing="1" cellpadding="0">
					 <?php
					 if(sizeof($quantity_total_display)>0){
					  for($incheck=0;$incheck<sizeof($price_total_display);$incheck++){ ?>
							 <tr bgcolor="#FFFFFF"><td  align="center"><?php echo $currencies->format($price_total_display[$incheck])?></td></tr>
					 <?php } }else{
					    if(sizeof($optionid_num)==0) $rowtmp=1; 
						else $rowtmp=sizeof($optionid_num); 
					    for($incheck=0;$incheck<$rowtmp;$incheck++){
					 ?> 
					 		<tr bgcolor="#FFFFFF"><td align="center"><?php echo $currencies->format($price_total)?></td></tr>
					 <?php } } ?>
					</table>
										
                  </td>
            <?php
				 unset($quantity_total_display);
				 unset($price_total_display);
               if ($_GET['last_6_month'] == "1") { 
                   // The SQL Query for last 6 month
				 if(sizeof($optionid_num)>0){
					 for($incheck=0;$incheck<sizeof($optionid_num);$incheck++){  
						$quantity_total = 0;
						$price_total = 0;
						$tax_total = 0;
						$quantity = 0;
						$price = 0;
						$tax = 0;

						$sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op,orders_products_attributes opa where". $sql_time_span_last_6_month . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id AND o.orders_id=opa.orders_id  AND  opa.products_options_values_id=".$optionid_num[$incheck];
						$result = $db->Execute($sql);
						while (!$result->EOF)  {
							 $quantity = $result->fields['products_quantity'];
							 $price = $result->fields['final_price'];
							 $tax = $result->fields['products_tax'];
							 $tax_total = $tax_total + ($tax * $quantity);
							 $quantity_total = $quantity_total + $quantity;
							 $price_total = $price_total + ($price * $quantity);
							 $result->MoveNext();
						}
 						 $quantity_total_display[$incheck]=$quantity_total;
					     $price_total_display[$incheck]=$price_total; 								
				     }		
				  }else{
						$sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op where". $sql_time_span_last_6_month . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id";
						$result = $db->Execute($sql);
						while (!$result->EOF)  {
						 $quantity = $result->fields['products_quantity'];
						 $price = $result->fields['final_price'];
						 $tax = $result->fields['products_tax'];
						 $tax_total = $tax_total + ($tax * $quantity);
						 $quantity_total = $quantity_total + $quantity;
						 $price_total = $price_total + ($price * $quantity);
						$result->MoveNext();
						}
				  }		
               } else {
                  $quantity_total = TEXT_NOT_SELECTED; // Last 6 month not selected
                   $tax_total = TEXT_NOT_SELECTED; // This month not selected
                  $price_total = TEXT_NOT_SELECTED; // This month not selected
               }
               ?>
                  <td align="center" class="quantity" valign="top" title="<?php echo TEXT_QTY_FOR . $products_name . " - " . TEXT_LAST_6_MONTH; ?>">
                    <?php // echo $quantity_total;  // Display Quantity of selected product for this month ?><br>
					  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						 <?php  
						if(sizeof($quantity_total_display)>0){
						 for($incheck=0;$incheck<sizeof($quantity_total_display);$incheck++){ ?>
								 <tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total_display[$incheck]?></td></tr>
						 <?php }  }else{  
							if(sizeof($optionid_num)==0) $rowtmp=1; 
							else $rowtmp=sizeof($optionid_num); 
							for($incheck=0;$incheck<$rowtmp;$incheck++){ ?> 
								<tr bgcolor="#FFFFFF" ><td align="center" height="100%" ><?php echo $quantity_total?></td></tr>
						 <?php } }?>
						</table>					
                  </td>
                  <td align="center" class="price" valign="top" title="<?php echo TEXT_PRICE_FOR . $products_name . " - " . TEXT_LAST_6_MONTH; ?>">
                    <?php // echo $currencies->format($price_total); ?><br>
				 <table width="100%" border="0" cellspacing="1" cellpadding="0">
					 <?php
					 if(sizeof($quantity_total_display)>0){
					  for($incheck=0;$incheck<sizeof($price_total_display);$incheck++){ ?>
							 <tr bgcolor="#FFFFFF"><td  align="center"><?php echo $currencies->format($price_total_display[$incheck])?></td></tr>
					 <?php } }else{
					    if(sizeof($optionid_num)==0) $rowtmp=1; 
						else $rowtmp=sizeof($optionid_num); 
					    for($incheck=0;$incheck<$rowtmp;$incheck++){
					 ?> 
					 		<tr bgcolor="#FFFFFF"><td align="center"><?php echo $currencies->format($price_total)?></td></tr>
					 <?php } } ?>
					</table>					
                  </td>
            <?php
				 unset($quantity_total_display);
				 unset($price_total_display);               
			   if ($_GET['year'] == "1") { 
                   // The SQL Query for this year
				 if(sizeof($optionid_num)>0){
					 for($incheck=0;$incheck<sizeof($optionid_num);$incheck++){  
						$quantity_total = 0;
						$price_total = 0;
						$tax_total = 0;
						$quantity = 0;
						$price = 0;
						$tax = 0;
					   $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op,orders_products_attributes opa where". $sql_time_span_this_year . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id  AND o.orders_id=opa.orders_id  AND  opa.products_options_values_id=".$optionid_num[$incheck];
					  $result = $db->Execute($sql);
					  while (!$result->EOF)  {
						 $quantity = $result->fields['products_quantity'];
						 $price = $result->fields['final_price'];
						 $tax = $result->fields['products_tax'];
						 $tax_total = $tax_total + ($tax * $quantity);
						 $quantity_total = $quantity_total + $quantity;
						 $price_total = $price_total + ($price * $quantity);
					   $result->MoveNext();
					  }
					 $quantity_total_display[$incheck]=$quantity_total;
					 $price_total_display[$incheck]=$price_total; 		
					}  
				}else{
					   $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op where". $sql_time_span_this_year . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id";
					  $result = $db->Execute($sql);
					  while (!$result->EOF)  {
						 $quantity = $result->fields['products_quantity'];
						 $price = $result->fields['final_price'];
						 $tax = $result->fields['products_tax'];
						 $tax_total = $tax_total + ($tax * $quantity);
						 $quantity_total = $quantity_total + $quantity;
						 $price_total = $price_total + ($price * $quantity);
					   $result->MoveNext();
					  }
				
				
				}	  
               } else {
                  $quantity_total = TEXT_NOT_SELECTED; // This year not selected
                   $tax_total = TEXT_NOT_SELECTED; // This month not selected
                  $price_total = TEXT_NOT_SELECTED; // This month not selected
               }
               ?>
                  <td align="center" class="quantity" valign="top" title="<?php echo TEXT_QTY_FOR . $products_name . " - " . TEXT_YEAR; ?>">
                    <?php // echo $quantity_total;  // Display Quantity of selected product for this month  ?><br>
				  <table width="100%" border="0" cellspacing="1" cellpadding="0">
					 <?php  
					if(sizeof($quantity_total_display)>0){
 					 for($incheck=0;$incheck<sizeof($quantity_total_display);$incheck++){ ?>
							 <tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total_display[$incheck]?></td></tr>
					 <?php }  }else{  
					    if(sizeof($optionid_num)==0) $rowtmp=1; 
						else $rowtmp=sizeof($optionid_num); 
					    for($incheck=0;$incheck<$rowtmp;$incheck++){ ?> 
					 		<tr bgcolor="#FFFFFF" ><td align="center" height="100%" ><?php echo $quantity_total?></td></tr>
					 <?php } }?>
					</table>					
                  </td>
                  <td align="center" valign="top" class="price" title="<?php echo TEXT_PRICE_FOR . $products_name . " - " . TEXT_YEAR; ?>">
                    <?php // echo $currencies->format($price_total); ?><br>
				 <table width="100%" border="0" cellspacing="1" cellpadding="0">
					 <?php
					 if(sizeof($quantity_total_display)>0){
					  for($incheck=0;$incheck<sizeof($price_total_display);$incheck++){ ?>
							 <tr bgcolor="#FFFFFF"><td  align="center"><?php echo $currencies->format($price_total_display[$incheck])?></td></tr>
					 <?php } }else{
					    if(sizeof($optionid_num)==0) $rowtmp=1; 
						else $rowtmp=sizeof($optionid_num); 
					    for($incheck=0;$incheck<$rowtmp;$incheck++){
					 ?> 
					 		<tr bgcolor="#FFFFFF"><td align="center"><?php echo $currencies->format($price_total)?></td></tr>
					 <?php } } ?>
					</table>					
                  </td>
            <?php
				 unset($quantity_total_display);
				 unset($price_total_display);
               if ($_GET['last_year'] == "1") { 
                   // The SQL Query for last year
				 if(sizeof($optionid_num)>0){
					 for($incheck=0;$incheck<sizeof($optionid_num);$incheck++){  
						$quantity_total = 0;
						$price_total = 0;
						$tax_total = 0;
						$quantity = 0;
						$price = 0;
						$tax = 0;
					   $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op,orders_products_attributes opa where". $sql_time_span_last_year . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id  AND o.orders_id=opa.orders_id  AND  opa.products_options_values_id=".$optionid_num[$incheck];
					  $result = $db->Execute($sql);
					  while (!$result->EOF)  {
						 $quantity = $result->fields['products_quantity'];
						 $price = $result->fields['final_price'];
						 $tax = $result->fields['products_tax'];
						 $tax_total = $tax_total + ($tax * $quantity);
						 $quantity_total = $quantity_total + $quantity;
						 $price_total = $price_total + ($price * $quantity);
					   $result->MoveNext();
					  }
					  
					     $quantity_total_display[$incheck]=$quantity_total;
					     $price_total_display[$incheck]=$price_total; 		
				    }	  
				 }else{
					   $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op where". $sql_time_span_last_year . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id";
					  $result = $db->Execute($sql);
					  while (!$result->EOF)  {
						 $quantity = $result->fields['products_quantity'];
						 $price = $result->fields['final_price'];
						 $tax = $result->fields['products_tax'];
						 $tax_total = $tax_total + ($tax * $quantity);
						 $quantity_total = $quantity_total + $quantity;
						 $price_total = $price_total + ($price * $quantity);
					   $result->MoveNext();
					  }				 
				 
				 }	  
               } else {
                  $quantity_total = TEXT_NOT_SELECTED; // Last year not selected
                  $tax_total = TEXT_NOT_SELECTED; // This month not selected
                  $price_total = TEXT_NOT_SELECTED; // This month not selected				  
               }
			 
               ?>
                  <td align="center" class="quantity" valign="top" title="<?php echo TEXT_QTY_FOR . $products_name . " - " . TEXT_LAST_YEAR; ?>">
                    <?php // echo $quantity_total;  // Display Quantity of selected product for this month  ?><br>
					  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						 <?php  
						if(sizeof($quantity_total_display)>0){
						 for($incheck=0;$incheck<sizeof($quantity_total_display);$incheck++){ ?>
								 <tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total_display[$incheck]?></td></tr>
						 <?php }  }else{  
							if(sizeof($optionid_num)==0) $rowtmp=1; 
							else $rowtmp=sizeof($optionid_num); 
							for($incheck=0;$incheck<$rowtmp;$incheck++){ ?> 
								<tr bgcolor="#FFFFFF" ><td align="center" height="100%" ><?php echo $quantity_total?></td></tr>
						 <?php } }?>
						</table>					
                  </td>
                  <td align="center" class="price" valign="top" title="<?php echo TEXT_PRICE_FOR . $products_name . " - " . TEXT_LAST_YEAR; ?>">
                    <?php // echo $currencies->format($price_total); ?><br>
					 <table width="100%" border="0" cellspacing="1" cellpadding="0">
						 <?php
						 if(sizeof($quantity_total_display)>0){
						  for($incheck=0;$incheck<sizeof($price_total_display);$incheck++){ ?>
								 <tr bgcolor="#FFFFFF"><td  align="center"><?php echo $currencies->format($price_total_display[$incheck])?></td></tr>
						 <?php } }else{
							if(sizeof($optionid_num)==0) $rowtmp=1; 
							else $rowtmp=sizeof($optionid_num); 
							for($incheck=0;$incheck<$rowtmp;$incheck++){
						 ?> 
								<tr bgcolor="#FFFFFF"><td align="center"><?php echo $currencies->format($price_total)?></td></tr>
						 <?php } } ?>
						</table>					
                  </td>
            <?php
				 unset($quantity_total_display);
				 unset($price_total_display);			
               if ($_GET['start_date'] != "" && $_GET['end_date'] != "") { 
                   // The SQL Query for custom date range
				 if(sizeof($optionid_num)>0){
					 for($incheck=0;$incheck<sizeof($optionid_num);$incheck++){  
						$quantity_total = 0;
						$price_total = 0;
						$tax_total = 0;
						$quantity = 0;
						$price = 0;
						$tax = 0;
					   $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op,orders_products_attributes opa where". $sql_time_span_custom . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id  AND o.orders_id=opa.orders_id  AND  opa.products_options_values_id=".$optionid_num[$incheck];
					  $result = $db->Execute($sql);
					  while (!$result->EOF)  {
						 $quantity = $result->fields['products_quantity'];
						 $price = $result->fields['final_price'];
						 $tax = $result->fields['products_tax'];
						 $tax_total = $tax_total + ($tax * $quantity);
						 $quantity_total = $quantity_total + $quantity;
						 $price_total = $price_total + ($price * $quantity);
					   $result->MoveNext();
					  }
 						 $quantity_total_display[$incheck]=$quantity_total;
					     $price_total_display[$incheck]=$price_total; 							  
				    }
				  }else{
					   $sql = "select o.orders_id, op.orders_id, " . $display_details . " FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op where". $sql_time_span_custom . " AND op.products_id = '" . $products_id . "' " . $sql_order_status . " AND o.orders_id = op.orders_id";
					  $result = $db->Execute($sql);
					  while (!$result->EOF)  {
						 $quantity = $result->fields['products_quantity'];
						 $price = $result->fields['final_price'];
						 $tax = $result->fields['products_tax'];
						 $tax_total = $tax_total + ($tax * $quantity);
						 $quantity_total = $quantity_total + $quantity;
						 $price_total = $price_total + ($price * $quantity);
					   $result->MoveNext();
					  }
				  } 
               } else {
                  $quantity_total = TEXT_NOT_SELECTED; // Last year not selected
                  $price_total = 0;
                  $tax_total = 0;
               }
               ?>
                  <td align="center" class="quantity" valign="top" title="<?php echo TEXT_QTY_FOR . $products_name . " - " . TEXT_CUSTOM_RANGE; ?>">
                    <?php // echo $quantity_total;  // Display Quantity of selected product for this month  ?><br>
					  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						 <?php  
						if(sizeof($quantity_total_display)>0){
						 for($incheck=0;$incheck<sizeof($quantity_total_display);$incheck++){ ?>
								 <tr bgcolor="#FFFFFF"><td align="center"><?php echo $quantity_total_display[$incheck]?></td></tr>
						 <?php }  }else{  
							if(sizeof($optionid_num)==0) $rowtmp=1; 
							else $rowtmp=sizeof($optionid_num); 
							for($incheck=0;$incheck<$rowtmp;$incheck++){ ?> 
								<tr bgcolor="#FFFFFF" ><td align="center" height="100%" ><?php echo $quantity_total?></td></tr>
						 <?php } }?>
						</table>					
                  </td>
                  <td align="center" class="price" title="<?php echo TEXT_PRICE_FOR . $products_name . " - " . TEXT_CUSTOM_RANGE; ?>">
                    <?php // echo $currencies->format($price_total); ?><br>
				 <table width="100%" border="0" cellspacing="1" cellpadding="0">
					 <?php
					 if(sizeof($quantity_total_display)>0){
					  for($incheck=0;$incheck<sizeof($price_total_display);$incheck++){ ?>
							 <tr bgcolor="#FFFFFF"><td  align="center"><?php echo $currencies->format($price_total_display[$incheck])?></td></tr>
					 <?php } }else{
					    if(sizeof($optionid_num)==0) $rowtmp=1; 
						else $rowtmp=sizeof($optionid_num); 
					    for($incheck=0;$incheck<$rowtmp;$incheck++){
					 ?> 
					 		<tr bgcolor="#FFFFFF"><td align="center"><?php echo $currencies->format($price_total)?></td></tr>
					 <?php } } ?>
					</table>					
                  </td>
          </tr>
                <?php } ?>
              </table>
            </td>
          </tr>
        </table>
        <?php } ?>
        <!-- eof results table  -->
        
      </td>
      </tr>
      </table>
      

<!-- footer //-->
<div class="footer-area">
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
</div>
<!-- footer_eof //-->
<br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

