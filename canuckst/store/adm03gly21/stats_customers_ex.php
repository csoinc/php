<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
//  $Id: stats_customers.php 1969 2005-09-13 06:57:21Z drbyte $
//
  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  
 define('STATUS_ORDER_DELIVERED', 4);
   
  $janfirst = mktime(0, 0, 0, 01, 01, date("y"));
  $_GET['start_date'] = (!isset($_GET['start_date']) ? date('01-01-2000') : $_GET['start_date']);
  $_GET['end_date'] = (!isset($_GET['end_date']) ? date("m-d-Y",(time())) : $_GET['end_date']);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
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
  
  <?php 
	$str_today = date("m-d-Y",(time()));
	$date_today = explode("-", $str_today);

?>  
  var day0 = new Date(<?php echo $date_today[2] . ', ' . ($date_today[0] - 1) . ', ' . $date_today[1] ?>);
  var day_y = new Date(day0 - 1);
  var day_lm1 = new Date(new Date( <?php echo $date_today[2] . ', ' . ($date_today[0] - 1) . ', 1' ?>) - 1);
  var day_lm0 = new Date(new Date( day_lm1.getYear(), day_lm1.getMonth(), 1));
//  alert(day_y);
  function setToday()
  {
//	var str = (today.getMonth()) + '-' + today.getDate() + '-' + today.getYear();
  	document.all.start_date.value='<?php echo $str_today; ?>';
  	document.all.end_date.value='<?php echo $str_today; ?>';
  }
  function setYesterday()
  {
	var str = (day_y.getMonth()+1) + '-' + day_y.getDate() + '-' + day_y.getYear();
  	document.all.start_date.value= str;
  	document.all.end_date.value= str;
  }
  function setThisMonth()
  {
  	document.all.start_date.value='<?php echo $date_today[0] . '-01-' . $date_today[2]; ?>';
  	document.all.end_date.value='<?php echo $str_today; ?>';
  }
  function setLastMonth()
  {
  	document.all.start_date.value=(day_lm0.getMonth()+1) + '-' + day_lm0.getDate() + '-' + day_lm0.getYear();
  	document.all.end_date.value=(day_lm1.getMonth()+1) + '-' + day_lm1.getDate() + '-' + day_lm1.getYear();
  }
  function setThisYear()
  {
  	document.all.start_date.value='<?php echo '01-01-' . $date_today[2]; ?>';
  	document.all.end_date.value='<?php echo $str_today; ?>';
  }
  function setLastYear()
  {
  	document.all.start_date.value='<?php echo '01-01-' . ($date_today[2]-1); ?>';
  	document.all.end_date.value='<?php echo '12-31-' . ($date_today[2]-1); ?>';
  }
  // -->
</script>
</head>
<body onLoad="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
      <td>
        <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table border="0" width="100%" cellspacing="2" cellpadding="2">
              <tr><?php echo zen_draw_form('search', FILENAME_STATS_CUSTOMERS_EX, '', 'get'); ?>
                <td class="main" align="right"><?php echo TEXT_INFO_START_DATE . ' ' . zen_draw_input_field('start_date', $_GET['start_date']); ?></td>
                <td class="main" align="right"><?php echo TEXT_INFO_END_DATE . ' ' . zen_draw_input_field('end_date', $_GET['end_date']) . zen_hide_session_id(); ?></td>
                <td class="main" align="right"><?php echo TEXT_INFO_CUSTOMER_NAME . ' ' . zen_draw_input_field('c_name', $_GET['c_name']); ?></td>
								<td class="main" align="right"><?php echo zen_image_submit('button_display.gif', IMAGE_DISPLAY); ?></td></tr>
              <tr>
				<td class="main" colspan="4">Report for: 
                <input type="button" onClick="javascript:setToday(); search.submit();" value="Today"> <input type="button" onClick="javascript:setYesterday(); search.submit();" value="Yesterday"> <input type="button" onClick="javascript:setThisMonth(); search.submit();" value="This Month"> <input type="button" onClick="javascript:setLastMonth(); search.submit();" value="Last Month"> <input type="button" onClick="javascript:setThisYear(); search.submit();" value="This Year"> <input type="button" onClick="javascript:setLastYear(); search.submit();" value="Last Year"></td>
              </tr>
            </table></form>
      </td>
      </tr>
      <tr><td><br/><b><?php echo $_GET['start_date'] . " thru " . $_GET['end_date'] ?></b> for orders <B>DELIVERED</B> or <B>PAID</B>. Orders under other status are not counted in.</td>
      </tr>
      <TR><TD>
<?php
  if (isset($_GET['c_name']) && zen_not_null($_GET['c_name'])) {
    $c_name = zen_db_input(zen_db_prepare_input($_GET['c_name']));
    echo '<br/ >' . TEXT_INFO_CUSTOMER_NAME . ': ' . $c_name;
  }
?>      
      </TD></TR>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="520" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="100px"><?php echo TABLE_HEADING_NUMBER; ?></td>
                <td class="dataTableHeadingContent" width="200"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
                <td class="dataTableHeadingContent" width="120px"><?php echo "Total Purchased"; ?></td>
                <td class="dataTableHeadingContent" width="100px"><?php echo "Balance Due"; ?></td>
              </tr>
<?php
// reverse date from m-d-y to y-m-d
    $date1 = explode("-", $_GET['start_date']);
    $m1 = $date1[0];
    $d1 = $date1[1];
    $y1 = $date1[2];

    $date2 = explode("-", $_GET['end_date']);
    $m2 = $date2[0];
    $d2 = $date2[1];
    $y2 = $date2[2];

    $sd = $y1 . '-' . $m1 . '-' . $d1;
    $ed = $y2. '-' . $m2 . '-' . $d2;

//  $sd = $_GET['start_date'];
//  $ed = $_GET['end_date'];


  if (isset($_GET['page']) && ($_GET['page'] > 1)) $rows = $_GET['page'] * MAX_DISPLAY_SEARCH_RESULTS_REPORTS - MAX_DISPLAY_SEARCH_RESULTS_REPORTS;
  $customers_query_raw = "select c.customers_id, c.customers_firstname, c.customers_lastname, sum(ot.value) as orders_total, sum(ot.value - o.amount_paid) as balance_due from " . TABLE_CUSTOMERS . " c, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o LEFT JOIN ". TABLE_ORDERS_TOTAL ." ot on (o.orders_id = ot.orders_id) where ot.class = 'ot_total' and o.orders_status >= " . STATUS_ORDER_DELIVERED . "  and c.customers_id = o.customers_id and o.orders_id = op.orders_id AND (o.date_purchased >= '" . $sd . " 0:00:00' and o.date_purchased <= '" . $ed . " 23:59:59') and o.customers_name like '%" . $c_name . "%' group by c.customers_firstname, c.customers_lastname order by balance_due DESC";
  $customers_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_REPORTS, $customers_query_raw, $customers_query_numrows);
// fix counted customers
  $customers_query_m = $db->Execute("select customers_id
                                           from " . TABLE_ORDERS . " where customers_name like '%" . $c_name . "%' group by customers_id");

  $customers_query_numrows = $customers_query_m->RecordCount();

  $rows = 0;
  $customers = $db->Execute($customers_query_raw);
  while (!$customers->EOF) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
              <tr class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href='<?php echo zen_href_link(FILENAME_CUSTOMERS, 'cID=' . $customers->fields['customers_id'], 'NONSSL'); ?>'">
                <td class="dataTableContent" align="right"><?php echo $customers->fields['customers_id']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent"><?php echo '<a href="' . zen_href_link(FILENAME_ORDERS, 'cID=' . $customers->fields['customers_id'], 'NONSSL') . '">' . $customers->fields['customers_firstname'] . ' ' . $customers->fields['customers_lastname'] . '</a>'; ?></td>
                
<?php 
				$bal_due = $customers->fields['balance_due'];
				$bal_color = "red";
				if ($bal_due == 0) {
					$bal_color = "green";
				}
				else if ($bal_due > 0) {
				    $bal_color =  "red";
				}
				else {
					$bal_color =  "blue";
				}
?>                
                
                <td class="dataTableContent" align="right"><?php echo $currencies->format($customers->fields['orders_total']); ?>&nbsp;</td>
                <td class="dataTableContent" style="color: white; background-color:<?php echo $bal_color; ?>;" align="right"><?php echo $currencies->format($bal_due); ?>&nbsp;</td>
              </tr>
<?php
    $customers->MoveNext();
  }
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_REPORTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                <td class="smallText" align="right"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_REPORTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
