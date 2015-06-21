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

 define('STATUS_ORDER_DELIVERED', 4);

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
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
    <td width="100%" valign="top"><table border="0" width="700" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE  . '<br>' . date('F d, Y'); ?></td>
            <td class="pageHeading" align="right"><?php echo  zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NUMBER; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_OUTSTANDING; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE_SHIPPED; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TERMS; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_AGED; ?>&nbsp;</td>
              </tr>
<?php
  if (isset($_GET['page']) && ($_GET['page'] > 1)) $rows = $_GET['page'] * MAX_DISPLAY_SEARCH_RESULTS_REPORTS - MAX_DISPLAY_SEARCH_RESULTS_REPORTS;
  $orders_query_raw = " SELECT orders_id, customers_id, customers_name, date_purchased, orders_status, payment_module_code, balance_due, (TO_DAYS(NOW()) - TO_DAYS(date_purchased)) AS age "
					  ." FROM " . TABLE_ORDERS 
					  ." WHERE balance_due <> 0 and orders_status >= " . STATUS_ORDER_DELIVERED
					  ." ORDER BY age DESC ";
  $orders_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_REPORTS, $orders_query_raw, $orders_query_numrows);
// fix counted customers
  $orders_query_m = $db->Execute("select orders_id
                                           from " . TABLE_ORDERS . " where balance_due <> 0 and orders_status >= " . STATUS_ORDER_DELIVERED );

  $orders_query_numrows = $orders_query_m->RecordCount();

  $rows = 0;
  $sum = 0;
  $orders = $db->Execute($orders_query_raw);
  while (!$orders->EOF) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
              <tr class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href='<?php echo zen_href_link(FILENAME_ORDERS, 'oID=' . $orders->fields['orders_id'] . '&action=edit', 'NONSSL'); ?>'">
                <td class="dataTableContent"><?php echo '<a href="' . zen_href_link(FILENAME_ORDERS, 'oID=' . $orders->fields['orders_id'] . '&action=edit', 'NONSSL') . '">' .$orders->fields['orders_id'] . '</a>'; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent"><?php echo '<a href="' . zen_href_link(FILENAME_CUSTOMERS, 'cID=' . $orders->fields['customers_id'], 'NONSSL') . '">' . $orders->fields['customers_name'] . '</a>'; ?></td>
                <td class="dataTableContent" align="right"><?php echo $currencies->format($orders->fields['balance_due']); ?>&nbsp;</td>
                <td class="dataTableContent"><?php echo zen_date_short($orders->fields['date_purchased']); ?>&nbsp;</td>
                <td class="dataTableContent"><?php $term = $orders->fields['payment_module_code']=='purchaseorder' ? 30 : 0 ; echo $term; ?>&nbsp;</td>
                <td class="dataTableContent" align="right"><?php echo ($orders->fields['age'] - $term); ?>&nbsp;</td>
              </tr>
<?php
    $sum += $orders->fields['balance_due'];
    $orders->MoveNext();
  }
?>
		       <tr class="dataTableRow">
                 <td bgcolor="#dddddd" ><b><?php echo TEXT_INFO_SUM; ?></b></td><td colspan="2" bgcolor="#dddddd" align="right"><b><?php echo $currencies->format($sum); ?></b> </td>
               </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_REPORTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_REPORTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?>&nbsp;</td>
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
