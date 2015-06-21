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
//  $Id: stats_products_lowstock.php 1969 2005-09-13 06:57:21Z drbyte $
//  modified to CURRENT STOCK REPORT by Loutka 31.12.2007

  require('includes/application_top.php');
	$maxDisplay = 100;
	
	
	
	
	if ($sort=='nameA') {
	$sOrder = 'pd.products_name ASC, p.products_id';
	}	
	elseif ($sort=='modelA') {
	$sOrder = 'p.products_model ASC, pd.products_name';
	}	
	elseif ($sort=='idA') {
	$sOrder = 'p.products_id ASC, pd.products_name';
	}	
	elseif ($sort=='qtyA') {
	$sOrder = 'p.products_quantity ASC, pd.products_name';
	}	
	elseif ($sort=='nameD') {
	$sOrder = 'pd.products_name DESC, p.products_id';
	}	
	elseif ($sort=='modelD') {
	$sOrder = 'p.products_model DESC, pd.products_name';
	}	
	elseif ($sort=='idD') {
	$sOrder = 'p.products_id DESC, pd.products_name';
	}	
	elseif ($sort=='qtyD') {
	$sOrder = 'p.products_quantity DESC, pd.products_name';
	}		
	else{
	$sOrder = 'p.products_id, pd.products_name';
	}
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right">Sort by: 
            <form action="<?php echo zen_href_link(FILENAME_STATS_STOCK); ?>" method="get">
            <select name="sort" onChange="this.form.submit();">
            	<option value="">Choose...</option>
            	<option value="idA">ID - ASC</option>
            	<option value="idD">ID - DESC</option>
            	<option value="modelA"><?php echo TABLE_HEADING_MODEL; ?> - ASC</option>
            	<option value="modelD"><?php echo TABLE_HEADING_MODEL; ?> - DESC</option>
            	<option value="nameA"><?php echo TABLE_HEADING_PRODUCTS; ?> - ASC</option>
            	<option value="nameD"><?php echo TABLE_HEADING_PRODUCTS; ?> - DESC</option>
            	<option value="qtyA"><?php echo TABLE_HEADING_VIEWED; ?> - ASC</option>
                <option value="qtyD"><?php echo TABLE_HEADING_VIEWED; ?> - DESC</option>                
            </select>  
            </form>
            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="50" align="center"><?php echo  TABLE_HEADING_NUMBER; ?></td>
                <td class="dataTableHeadingContent" width="70" align="center"><?php echo  TABLE_HEADING_MODEL; ?></td>
                <td class="dataTableHeadingContent"><?php echo  TABLE_HEADING_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" width="170" align="center"><?php echo TABLE_HEADING_REAL; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" width="170" align="center"><?php echo TABLE_HEADING_TOBE; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" width="170" align="center"><?php echo  TABLE_HEADING_VIEWED; ?>&nbsp;</td>
              </tr>
<?php
  if (isset($_GET['page']) && ($_GET['page'] > 1)) $rows = $_GET['page'] * $maxDisplay - $maxDisplay;
  $rows = 0;
  $products_query_raw = "select p.products_id, p.products_model, pd.products_name, p.products_quantity, p.products_type from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id='" . $_SESSION['languages_id'] . "' order by ".$sOrder." ";
  $products_split = new splitPageResults($_GET['page'], $maxDisplay, $products_query_raw, $products_query_numrows);
  $products = $db->Execute($products_query_raw);
  while (!$products->EOF) {

// only show low stock on products that can be added to the cart
    if ($zc_products->get_allow_add_to_cart($products->fields['products_id']) == 'Y') {

      $rows++;

      if (strlen($rows) < 2) {
        $rows = '0' . $rows;
      }

      $type_handler = $zc_products->get_admin_handler($products->fields['products_type']);

      $cPath = zen_get_product_path($products->fields['products_id']);
	  
	  $real = $db->Execute("SELECT SUM(op.products_quantity) AS qty
	  				FROM  " . TABLE_ORDERS . " o,  " . TABLE_ORDERS_PRODUCTS . " op
					WHERE o.orders_status<>'3'
						AND o.orders_id=op.orders_id
						AND op.products_id='". $products->fields['products_id'] ."' ");
?>
              <tr class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href='<?php echo zen_href_link($type_handler, '&product_type=' . $products->fields['products_type'] . '&cPath=' . $cPath . '&pID=' . $products->fields['products_id'] . '&action=new_product'); ?>'">
                <td class="dataTableContent" align="right"><?php echo $products->fields['products_id']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent" align="right"><?php echo $products->fields['products_model']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent"><?php echo '<a href="' . zen_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products->fields['products_id']) . '">' . $products->fields['products_name'] . '</a>'; ?></td>
                <td class="dataTableContent" align="center"><?php echo $products->fields['products_quantity'] + $real->fields[qty]; ?>&nbsp;</td>
                <td class="dataTableContent" align="center"><b><?php echo $real->fields[qty]; ?></b>&nbsp;</td>
                <td class="dataTableContent" align="center"><?php
					$level=$products->fields['products_quantity'];
					if ($level<'1'){
						echo ('<font color="#FF0000"><b>'.$level.'</b></font>'); 
						}
						else
						{
						echo $level; 
						}?>
                        &nbsp;</td>
              </tr>
<?php
    }
    $products->MoveNext();
  }
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, $maxDisplay, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, $maxDisplay, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
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