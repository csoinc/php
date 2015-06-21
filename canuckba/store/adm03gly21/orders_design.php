<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2006 The zen-cart developers                           |
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
//  $Id: invoice.php 6251 2007-04-22 19:21:48Z wilt $
//

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $oID = zen_db_prepare_input($_GET['orders_id']);

  include(DIR_WS_CLASSES . 'order.php');
  $order = new order($oID);

  // prepare order-status pulldown list
  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status = $db->Execute("select orders_status_id, orders_status_name
                                 from " . TABLE_ORDERS_STATUS . "
                                 where language_id = '" . (int)$_SESSION['languages_id'] . "'");
  while (!$orders_status->EOF) {
    $orders_statuses[] = array('id' => $orders_status->fields['orders_status_id'],
                               'text' => $orders_status->fields['orders_status_name'] . ' [' . $orders_status->fields['orders_status_id'] . ']');
    $orders_status_array[$orders_status->fields['orders_status_id']] = $orders_status->fields['orders_status_name'];
    $orders_status->MoveNext();
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" type="text/javascript"><!--
function couponpopupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=280,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
<style>
#design_front{
position:relative;
}
#design_back{
position:relative;
}

P.breakhere {page-break-before: always}
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- body_text for printable table//-->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td><table border="0" width="90%" cellspacing="0" cellpadding="0">
      <tr>
        <td class="pageHeading"><?php echo nl2br(STORE_NAME_ADDRESS); ?></td>
        <td class="pageHeading" align="right"><?php echo zen_image(DIR_WS_IMAGES . HEADER_LOGO_IMAGE, HEADER_ALT_TEXT); ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="90%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td colspan="2"><?php echo zen_draw_separator(); ?></td>
      </tr>

<?php
      $order_check = $db->Execute("select cc_cvv, customers_name, customers_company, customers_street_address,
                                    customers_suburb, customers_city, customers_postcode,
                                    customers_state, customers_country, customers_telephone,
                                    customers_email_address, customers_address_format_id, delivery_name,
                                    delivery_company, delivery_street_address, delivery_suburb,
                                    delivery_city, delivery_postcode, delivery_state, delivery_country,
                                    delivery_address_format_id, billing_name, billing_company,
                                    billing_street_address, billing_suburb, billing_city, billing_postcode,
                                    billing_state, billing_country, billing_address_format_id,
                                    payment_method, cc_type, cc_owner, cc_number, cc_expires, currency,
                                    currency_value, date_purchased, orders_status, last_modified
                             from " . TABLE_ORDERS . "
                             where orders_id = '" . (int)$oID . "'");
  $show_customer = 'false';
  if ($order_check->fields['billing_name'] != $order_check->fields['delivery_name']) {
    $show_customer = 'true';
  }
  if ($order_check->fields['billing_street_address'] != $order_check->fields['delivery_street_address']) {
    $show_customer = 'true';
  }
  if ($show_customer == 'true') {
?>
      <tr>
        <td class="main"><b><?php echo ENTRY_CUSTOMER; ?></b></td>
      </tr>
      <tr>
        <td class="main"><?php echo zen_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>'); ?></td>
      </tr>
<?php } ?>
      <tr>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo ENTRY_SOLD_TO; ?></b></td>
          </tr>
          <tr>
            <td class="main"><?php echo zen_address_format($order->customer['format_id'], $order->billing, 1, '', '<br>'); ?></td>
          </tr>
          <tr>
            <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo $order->customer['telephone']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo '<a href="mailto:' . $order->customer['email_address'] . '">' . $order->customer['email_address'] . '</a>'; ?></td>
          </tr>
        </table></td>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo ENTRY_SHIP_TO; ?></b></td>
          </tr>
          <tr>
            <td class="main"><?php echo zen_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'); ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
    <td class="main"><b><?php echo ENTRY_ORDER_ID . $oID; ?></b></td>
  </tr>
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td class="main"><strong><?php echo ENTRY_DATE_PURCHASED; ?></strong></td>
        <td class="main"><?php echo zen_date_long($order->info['date_purchased']); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
        <td class="main"><?php echo $order->info['payment_method']; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
</table>

	
	<?php
	for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
	$design='';
	$design=unserialize(base64_decode($order->products[$i]["design"]));
	if(is_array($design)){
	?>

	<P CLASS="breakhere">
	
	<table border="1px" width="90%" cellspacing="0" cellpadding="3" style="border:1px solid #0066CC">
      <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent" colspan="4"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?>:<span style="font-size:1.5em; font-weight:bold;"><?php echo $order->products[$i]["model"];?></span><br /> products name:<span style="font-size:1.5em; font-weight:bold;"><?php echo $order->products[$i]["name"];?></span><br>
		background image: 
		<?php
		preg_match_all("/DIV\sid=bgfront_([0-9])\stitle=([a-z]+)/i",addslashes($design["html"]['front']),$bg_id, PREG_SET_ORDER);
		foreach ($bg_id as $val) {
			$design_image_name=$db->Execute("select design_products_name from ".DESIGN_PRODUCTS_IMAGES." where design_products_image_id=".$val[1]);
			echo $val[2] . ":" . $design_image_name->fields['design_products_name']." ";
		}
		?>
		</td>
      </tr>
	  <tr>
	  <td rowspan="3" style="background-color:#ddd; font-size:1.8em; font-weight:bold;width:20px;" height="350px">front</td>
	  <td width="30px">logo</td>
      <td width="50px"><?php if(zen_not_null($design["front_logo"]['logo'])) { ?>
	  <?php echo (strstr("images/tmp",$design['front_logo']['logo'])?"design logo:":"default logo:");?><a href="../<?php echo $design['front_logo']['logo'];?>" target="_blank"><?php echo zen_image('../'.$design['front_logo']['logo'],"",50);?></a><br>
	  position:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design['front_logo']['position']?></span><br>
	  embroidery:<span style="font-size:1.5em; font-weight:bold;"><?php echo ($design['front_logo']['embroidery']=="true"?"yes":"no")?></span><br>
	  corlor number:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design['front_logo']['color_num']?></span><br>
	  <?php } else echo"N/A";?></td>
      <td width="300px" rowspan="3" valign="top">
	  <?php echo $design["html"]['front'];?>
	  </td>
	  </tr>
	  <tr>
		  <td>team name</td>
		  <td>
			<?php if(zen_not_null($design["front_team_name"])) {
 		
				foreach(array_keys($design["front_team_name"]) as $div) { ?>
		  <?php if(zen_not_null($design["front_team_name"][$div]['front_insert_team_name_text'])) { ?>
		  text:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["front_team_name"][$div]['front_insert_team_name_text'];?></span><br />
		  size:<span style="font-size:1.5em; font-weight:bold;"><?php echo $team_name_size[$design["front_team_name"]['front_team_name_size']];?></span><br /> 
		  font:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["front_team_name"][$div]['front_team_name_font'];?></span><br />
		  color :<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["front_team_name"][$div]['front_team_name_color'];?> </span><br />
		  around color :<span style="font-size:1.5em; font-weight:bold;"><?php echo ($design["front_team_name"][$div]['front_team_name_out_color']!=''?$design["front_team_name"]['front_team_name_out_color']:"N/A");?></span><br> 
		  radian:<span style="font-size:1.5em; font-weight:bold;"><?php if($design["front_team_name"][$div]["front_team_name_distort"]!='no'){ echo $design["front_team_name"][$div]["front_team_name_distort"];?> <?php echo $design["front_team_name"][$div]["front_team_name_radian"]."&deg;";}else echo "N/A";?></span><br> 
		  font style:<span style="font-size:1.5em; font-weight:bold;"><?php if($design["front_team_name"][$div]["front_team_name_blod"]==1 or $design["front_team_name"][$div]["front_team_name_italic"]>0 or $design["front_team_name"][$div]["front_team_name_cavity"]>0){ echo ($design["front_team_name"][$div]["front_team_name_blod"]=1?"Blod":"");?><?php echo ($design["front_team_name"][$div]["front_team_name_cavity"]>0?"Cavity":"");?> <?php echo ($design["front_team_name"][$div]["front_team_name_italic"]>0?"italic":"");}else echo"N/A"; ?></span><br> 
		  position:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["front_team_name"][$div]["front_team_name_position"];?></span>
		  <?php } else echo"N/A";?>
	     <?php }?>
		  <?php } else echo"N/A";?>
		  </td>
		  </tr>
		  <tr>
	  <td>Num</td>
	  <td>
	  <?php if(zen_not_null($nums_size[$design["front_nums"]['team_nums_size']])) { ?>
	  size:<span style="font-size:1.5em; font-weight:bold;"><?php echo $nums_size[$design["front_nums"]['team_nums_size']];?></span><br />
	  main color: <span style="font-size:1.5em; font-weight:bold;"><?php echo $design["front_nums"]['team_nums_color'];?></span><br /> 
	  around color :<span style="font-size:1.5em; font-weight:bold;"><?php echo ($design["front_nums"]['team_nums_out_color']!=''?$design["front_nums"]['front_team_nums_out_color']:"N/A");?></span><br> 
	  position:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["front_nums"]["nums_position"];?></span>
	  <?php } else echo"N/A";?>
	  </td>
	  </tr>
 
	  <?php if(zen_not_null($design['html']['back'])) {?>
	  <tr>
		<td rowspan="4" height="350px" style="background-color:#ddd; font-size:1.8em; font-weight:bold;width:20px;">back</td>
		<td width="30px">logo</td>
		<td width="50px"><?php if(zen_not_null($design["back_logo"]['logo'])){ echo (strstr("images/tmp",$design['back_logo']['logo'])?"design logo:":"default logo:");?><a href="../<?php echo $design['back_logo']['logo'];?>" target="_blank"><?php echo zen_image('../'.$design['back_logo']['logo'],"",50);?></a><br> 
			position:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design['back_logo']["position"];?></span>
			embroidery:<span style="font-size:1.5em; font-weight:bold;"><?php echo ($design['back_logo']['embroidery']=="true"?"yes":"no")?></span><br>
			color number:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design['back_logo']["color_num"];?></span>
		<?php } else echo"N/A";?></td>
		<td rowspan="4" valign="top"  width="300px"><?php echo $design["html"]['back'];?></td>
	  </tr>
	  <tr>
		<td>team name </td>
		<td>
			<?php if(zen_not_null($design["back_team_name"])) { ?>
 			<?php
				foreach(array_keys($design["back_team_name"]) as $div) { ?>
		<?php if(zen_not_null($design["back_team_name"][$div]['front_insert_team_name_text'])) { ?>
		text:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["back_team_name"][$div]['front_insert_team_name_text'];?></span><br />
	  size:<span style="font-size:1.5em; font-weight:bold;"><?php echo $team_name_size[$design["back_team_name"][$div]['front_team_name_size']];?></span><br /> 
	  font:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["back_team_name"][$div]['front_team_name_font'];?></span><br />
	  color :<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["back_team_name"][$div]['front_team_name_color'];?> </span><br />
	  around color :<span style="font-size:1.5em; font-weight:bold;"><?php echo ($design["back_team_name"][$div]['front_team_name_out_color']!=''?$design["back_team_name"][$div]['front_team_name_out_color']:"N/A");?></span><br> 
	  radian:<span style="font-size:1.5em; font-weight:bold;"><?php if($design["back_team_name"][$div]["front_team_name_distort"]!='no'){ echo $design["back_team_name"][$div]["front_team_name_distort"];?> <?php echo $design["back_team_name"][$div]["front_team_name_radian"]."&deg;";}else echo "N/A";?></span><br> 
	  font style:<span style="font-size:1.5em; font-weight:bold;"><?php if($design["back_team_name"][$div]["front_team_name_blod"]==1 or $design["back_team_name"][$div]["front_team_name_italic"]>0 or $design["back_team_name"]["back_team_name_".$i]["front_team_name_cavity"]>0){echo ($design["back_team_name"][$div]["front_team_name_blod"]=1?"Blod":"");?><?php echo ($design["back_team_name"][$div]["front_team_name_cavity"]>0?"Cavity":"");?> <?php echo ($design["back_team_name"][$div]["front_team_name_italic"]>0?"italic":"");}else echo"N/A";?></span><br> 
	  position:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["back_team_name"][$div]["front_team_name_position"];?></span>
	  <?php } else echo"N/A";?>
	  <?php }?>
	  <?php } else echo"N/A";?>
		</td>
	  </tr>
	  <tr>
		<td>num</td>
		<td>front
		size:<span style="font-size:1.5em; font-weight:bold;"><?php echo $nums_size[$design["back_nums"]['team_nums_size']];?></span><br /> 
		main color:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["back_nums"]['team_nums_color'];?></span><br />
		around color:<span style="font-size:1.5em; font-weight:bold;"><?php echo ($design["back_nums"]['team_nums_out_color']!=''?$design["back_nums"]['back_team_nums_out_color']:"N/A");?></span><br> 
		position:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["back_nums"]["nums_position"];?></span>
		<?php } else echo"N/A";?>
		</td>
	  </tr>
	  <tr>
		<td>name</td>
		<td><?php if(zen_not_null($design["back_name"])){?>color:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["back_name"]['name_color'];?></span><br>
		position:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["back_name"]["name_position"];?>
		</td>
	  </tr>
	  <?php } else echo"N/A";?>
		</td>
	  </tr>
</table>
	  <P CLASS="breakhere">

	  <?php if(zen_not_null($design['html']['sleeve_left'])) {?>
	<table border="1px" width="90%" cellspacing="0" cellpadding="3" style="border:1px solid #0066CC">
	  <tr>
		<td rowspan="4" height="350px" style="background-color:#ddd; font-size:1.8em; font-weight:bold;width:20px;">left sleeve</td>
		<td width="30px">logo</td>
		<td width="50px"><?php if(zen_not_null($design["sleeve_left_logo"]['logo'])){ echo (strstr("images/tmp",$design['sleeve_left_logo']['logo'])?"design logo:":"default logo:");?><a href="../<?php echo $design['sleeve_left_logo']['logo'];?>" target="_blank"><?php echo zen_image('../'.$design['sleeve_left_logo']['logo'],"",50);?></a><br> 
		position:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design['sleeve_left_logo']["position"];?></span>
		embroidery:<span style="font-size:1.5em; font-weight:bold;"><?php echo ($design['sleeve_left_logo']['embroidery']=="true"?"yes":"no")?></span><br>
		color number:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design['sleeve_left_logo']["color_num"];?></span>
		<?php } else echo"N/A";?></td>
		<td rowspan="4" valign="top" width="300px"><?php echo $design["html"]['sleeve_left'];?></td>
	  </tr>
	  <tr>
		<td>team name </td>
		<td>
		<?php
			for ($i=0, $n=sizeof($design["sleeve_left_team_name"]); $i<$n; $i++) { ?>
		<?php if(zen_not_null($design["sleeve_left_team_name"]["sleeve_left_team_name_".$i]['front_insert_team_name_text'])) { ?>
		text:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["sleeve_left_team_name"]["sleeve_left_team_name_".$i]['front_insert_team_name_text'];?></span><br />
	  size:<span style="font-size:1.5em; font-weight:bold;"><?php echo $team_name_size[$design["sleeve_left_team_name"]["sleeve_left_team_name_".$i]['front_team_name_size']];?></span><br /> 
	  font:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["sleeve_left_team_name"]["sleeve_left_team_name_".$i]['front_team_name_font'];?></span><br />
	  color :<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["sleeve_left_team_name"]["sleeve_left_team_name_".$i]['front_team_name_color'];?> </span><br />
	  around color :<span style="font-size:1.5em; font-weight:bold;"><?php echo ($design["sleeve_left_team_name"]["sleeve_left_team_name_".$i]['front_team_name_out_color']!=''?$design["sleeve_team_name"]["sleeve_team_name_".$i]['front_team_name_out_color']:"N/A");?></span><br> 
	  radian:<span style="font-size:1.5em; font-weight:bold;"><?php if($design["sleeve_left_team_name"]["sleeve_left_team_name_".$i]["front_team_name_distort"]!='no'){ echo $design["sleeve_left_team_name"]["sleeve_left_team_name_".$i]["front_team_name_distort"];?> <?php echo $design["sleeve_left_team_name"]["sleeve_left_team_name_".$i]["front_team_name_radian"]."&deg;";}else echo "N/A";?></span><br> 
	  font style:<span style="font-size:1.5em; font-weight:bold;"><?php if($design["sleeve_left_team_name"]["sleeve_left_team_name_".$i]["front_team_name_blod"]==1 or $design["sleeve_left_team_name"]["sleeve_left_team_name_".$i]["front_team_name_italic"]>0 or $design["sleeve_left_team_name"]["sleeve_left_team_name_".$i]["front_team_name_cavity"]>0){echo ($design["sleeve_left_team_name"]["sleeve_left_team_name_".$i]["front_team_name_blod"]=1?"Blod":"");?><?php echo ($design["sleeve_left_team_name"]["sleeve_left_team_name_".$i]["front_team_name_cavity"]>0?"Cavity":"");?> <?php echo ($design["sleeve_left_team_name"]["sleeve_left_team_name_".$i]["front_team_name_italic"]>0?"italic":"");}else echo"N/A";?></span><br> 
	  position:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["sleeve_left_team_name"]["sleeve_left_team_name_".$i]["front_team_name_position"];?></span>
	  <?php } else echo"N/A";?>
	   <?php }?>
		</td>
	  </tr>
	  <tr>
		<td>num</td>
		<td>
		<?php if(zen_not_null($nums_size[$design["sleeve_left_nums"]['team_nums_size']])) { ?>
		size:<span style="font-size:1.5em; font-weight:bold;"><?php echo $nums_size[$design["sleeve_left_nums"]['team_nums_size']];?></span><br /> 
		main color:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["sleeve_left_nums"]['team_nums_color'];?></span><br />
		around color:<span style="font-size:1.5em; font-weight:bold;"><?php echo ($design["sleeve_left_nums"]['team_nums_out_color']!=''?$design["sleeve_left_nums"]['sleeve_team_nums_out_color']:"N/A");?></span><br> 
		position:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["sleeve_left_nums"]["nums_position"];?></span>
		<?php } else echo"N/A";?>
		</td>
	  </tr>
	  <tr>
		<td>name</td>
		<td><?php if(zen_not_null($design["sleeve_left_name"])){?>color:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["sleeve_left_name"]['name_color'];?></span><br>
		position:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["sleeve_left_name"]["name_position"];?>
		<?php } else echo"N/A";?>
		</td>
	  </tr>
	  <?php }?>

	  <P CLASS="breakhere">
	  
	  <?php if(zen_not_null($design['html']['sleeve_right'])) {?>
	  <tr>
		<td rowspan="4" height="350px" style="background-color:#ddd; font-size:1.8em; font-weight:bold;width:20px;">right sleeve</td>
		<td width="30px">logo</td>
		<td width="50px"><?php if(zen_not_null($design["sleeve_right_logo"]['logo'])){ echo (strstr("images/tmp",$design['sleeve_right_logo']['logo'])?"design logo:":"default logo:");?><a href="../<?php echo $design['sleeve_right_logo']['logo'];?>" target="_blank"><?php echo zen_image('../'.$design['sleeve_right_logo']['logo'],"",50);?></a><br> 
		position:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design['sleeve_right_logo']["position"];?></span>
		embroidery:<span style="font-size:1.5em; font-weight:bold;"><?php echo ($design['sleeve_right_logo']['embroidery']=="true"?"yes":"no")?></span><br>
		color number:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design['sleeve_right_logo']["color_num"];?></span>
		<?php } else echo"N/A";?></td>
		<td rowspan="4" valign="top" width="300px"><?php echo $design["html"]['sleeve_right'];?></td>
	  </tr>
	  <tr>
		<td>team name </td>
		<td>
		<?php
			for ($i=0, $n=sizeof($design["sleeve_right_team_name"]); $i<$n; $i++) { ?>
		<?php if(zen_not_null($design["sleeve_right_team_name"]["sleeve_right_team_name_".$i]['front_insert_team_name_text'])) { ?>
		text:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["sleeve_right_team_name"]["sleeve_right_team_name_".$i]['front_insert_team_name_text'];?></span><br />
	  size:<span style="font-size:1.5em; font-weight:bold;"><?php echo $team_name_size[$design["sleeve_right_team_name"]["sleeve_right_team_name_".$i]['front_team_name_size']];?></span><br /> 
	  font:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["sleeve_right_team_name"]["sleeve_right_team_name_".$i]['front_team_name_font'];?></span><br />
	  color :<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["sleeve_right_team_name"]["sleeve_right_team_name_".$i]['front_team_name_color'];?> </span><br />
	  around color :<span style="font-size:1.5em; font-weight:bold;"><?php echo ($design["sleeve_right_team_name"]["sleeve_right_team_name_".$i]['front_team_name_out_color']!=''?$design["sleeve_team_name"]["sleeve_team_name_".$i]['front_team_name_out_color']:"N/A");?></span><br> 
	  radian:<span style="font-size:1.5em; font-weight:bold;"><?php if($design["sleeve_right_team_name"]["sleeve_right_team_name_".$i]["front_team_name_distort"]!='no'){ echo $design["sleeve_right_team_name"]["sleeve_right_team_name_".$i]["front_team_name_distort"];?> <?php echo $design["sleeve_right_team_name"]["sleeve_right_team_name_".$i]["front_team_name_radian"]."&deg;";}else echo "N/A";?></span><br> 
	  font style:<span style="font-size:1.5em; font-weight:bold;"><?php if($design["sleeve_right_team_name"]["sleeve_right_team_name_".$i]["front_team_name_blod"]==1 or $design["sleeve_right_team_name"]["sleeve_right_team_name_".$i]["front_team_name_italic"]>0 or $design["sleeve_right_team_name"]["sleeve_right_team_name_".$i]["front_team_name_cavity"]>0){echo ($design["sleeve_right_team_name"]["sleeve_right_team_name_".$i]["front_team_name_blod"]=1?"Blod":"");?><?php echo ($design["sleeve_right_team_name"]["sleeve_right_team_name_".$i]["front_team_name_cavity"]>0?"Cavity":"");?> <?php echo ($design["sleeve_right_team_name"]["sleeve_right_team_name_".$i]["front_team_name_italic"]>0?"italic":"");}else echo"N/A";?></span><br> 
	  position:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["sleeve_right_team_name"]["sleeve_right_team_name_".$i]["front_team_name_position"];?></span>
	  <?php } else echo"N/A";?>
	   <?php }?>
		</td>
	  </tr>
	  <tr>
		<td>num</td>
		<td>
		<?php if(zen_not_null($nums_size[$design["sleeve_right_nums"]['team_nums_size']])) { ?>
		size:<span style="font-size:1.5em; font-weight:bold;"><?php echo $nums_size[$design["sleeve_right_nums"]['team_nums_size']];?></span><br /> 
		main color:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["sleeve_right_nums"]['team_nums_color'];?></span><br />
		around color:<span style="font-size:1.5em; font-weight:bold;"><?php echo ($design["sleeve_right_nums"]['team_nums_out_color']!=''?$design["sleeve_right_nums"]['sleeve_team_nums_out_color']:"N/A");?></span><br> 
		position:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["sleeve_right_nums"]["nums_position"];?></span>
		<?php } else echo"N/A";?>
		</td>
	  </tr>
	  <tr>
		<td>name</td>
		<td><?php if(zen_not_null($design["sleeve_right_name"])){?>color:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["sleeve_right_name"]['name_color'];?></span><br>
		position:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["sleeve_right_name"]["name_position"];?>
		<?php } else echo"N/A";?>
		</td>
	  </tr>
	  <?php }?>

	  <?php if(zen_not_null($design['color_options'])) {?>
	  <tr><td colspan="4">Colour Selection:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["color_options"];?></span></td></tr>
	  <?php }?>

	  <?php if(zen_not_null($design['body_color'])) {?>
	  <tr style="background-color:#ddd;"><td>Customized Colour Selection</td><td>Body</td><td>Side</td><td>Pipe</td></tr>
	  <tr><td> </td><td><?php echo (zen_not_null($design["body_color"])?$design["body_color"]:"N/A");?></td><td><?php echo (zen_not_null($design["side_color"])?$design["side_color"]:"N/A");?></td><td><?php echo (zen_not_null($design["trim_color"])?$design["trim_color"]:"N/A");?></td></tr>
	  <?php }?>

	  <tr><td colspan="4">design description:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["design_des"];?></span></td></tr>
	</table><br />
	<table border="1" width="90%" cellspacing="0">
	<tr><td colspan="4" style="font-size:1.5em; font-weight:bold; background-color:#ddd" align="center">Size,Qty,#s</td></tr>
	<tr style="background-color:#ddd;"><td>Name</td><td>Size</td><td>Num</td><td>Qty</td></tr>
	<?php for($j=0;$j<sizeof($design["member"]);$j++){?>
	<tr><td><?php echo (zen_not_null($design["member"][$j]["name"])?$design["member"][$j]["name"]:"N/A");?></td><td><?php echo $design["member"][$j]["size"];?></td><td><?php echo $design["member"][$j]["nums"];?></td><td><?php echo $design["member"][$j]["qty"];?></td></tr>
	<?php }?>
	<tr><td colspan="4">team member description:<span style="font-size:1.5em; font-weight:bold;"><?php echo $design["team_des"];?></span></td></tr>
	</table>
	
	<br />
	<?php
	} else {
	/*
	?>
	<table border="1" width="90%" cellspacing="0">
	<tr><td><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?>:<span style="font-size:1.5em; font-weight:bold;"><?php echo $order->products[$i]["model"];?></span></td><td> products name:<span style="font-size:1.5em; font-weight:bold;"><?php echo $order->products[$i]["name"];?></span></td><td>Qty:<?php echo $order->products[$i]["qty"];?>
	</td></tr>
	</table>
	<br />
	<?php
	*/
	}//ѭ
	?>
    </td>
  </tr>
<?php } ?>

<?php if (ORDER_COMMENTS_INVOICE > 0) { ?>
      <tr>
        <td class="main"><table border="1" cellspacing="0" cellpadding="5">
          <tr>
            <td class="smallText" align="center"><strong><?php echo TABLE_HEADING_DATE_ADDED; ?></strong></td>
            <td class="smallText" align="center"><strong><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></strong></td>
            <td class="smallText" align="center"><strong><?php echo TABLE_HEADING_STATUS; ?></strong></td>
            <td class="smallText" align="center"><strong><?php echo TABLE_HEADING_COMMENTS; ?></strong></td>
          </tr>
<?php
    $orders_history = $db->Execute("select orders_status_id, date_added, customer_notified, comments
                                    from " . TABLE_ORDERS_STATUS_HISTORY . "
                                    where orders_id = '" . zen_db_input($oID) . "'
                                    order by date_added");

    if ($orders_history->RecordCount() > 0) {
      $count_comments=0;
      while (!$orders_history->EOF) {
        $count_comments++;
        echo '          <tr>' . "\n" .
             '            <td class="smallText" align="center">' . zen_datetime_short($orders_history->fields['date_added']) . '</td>' . "\n" .
             '            <td class="smallText" align="center">';
        if ($orders_history->fields['customer_notified'] == '1') {
          echo zen_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
        } else {
          echo zen_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
        }
        echo '            <td class="smallText">' . $orders_status_array[$orders_history->fields['orders_status_id']] . '</td>' . "\n";
        echo '            <td class="smallText">' . ($orders_history->fields['comments'] == '' ? TEXT_NONE : nl2br(zen_db_output($orders_history->fields['comments']))) . '&nbsp;</td>' . "\n" .
             '          </tr>' . "\n";
        $orders_history->MoveNext();
        if (ORDER_COMMENTS_INVOICE == 1 && $count_comments >= 1) {
          break;
        }
      }
    } else {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
?>
        </table>
<?php } // order comments ?>


<!-- body_text_eof //-->

<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>