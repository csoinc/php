<?php if ($clientid == 0) {?>
<?php echo sprintf('<a href="%s/uniformorders/select/%d/" title="Back"><img src="%s/images/buttons/button_back.gif" /></a>', WEB_CONTEXT, $clients_start, WEB_CONTEXT); ?><br>
<?php } else {?>
<?php echo sprintf('<a href="%s/clientorderitems/select/%d/%d/%s" title="Back"><img src="%s/images/buttons/button_back.gif" /></a>', WEB_CONTEXT, $clients_start, $clientid, $orderid, WEB_CONTEXT); ?><br>
<?php }?>

<?php echo validation_errors(); ?>
<?php echo form_open('clientorderitems/update_client/'.$clients_start.'/'.$clientid.'/'.$orderid.'/'.$stocks_edit.'/'.$artworks_edit); ?>
<b>Order #:&nbsp;</b><?php echo $order->orderid; ?> 
<table class="zebraTable">
<tr class="rowEven"><th colspan="4">Client Information</th><tr>

<tr class="rowOdd"><td>Name</td><td><input type="text" name="contact" value="<?php echo $order->contact; ?>" size="30" maxlength="50"></td>
<td>Email</td><td><input type="text" name="email" value="<?php echo $order->email; ?>" size="30" maxlength="50"></td></tr>

<tr class="rowEven"><td>Telephone</td><td><input id="telephone" type="text" name="telephone" value="<?php echo $order->telephone; ?>" size="30" maxlength="50"></td>
<td>Other Phone</td><td><input id="cellphone" type="text" name="cellphone" value="<?php echo $order->cellphone; ?>" size="30" maxlength="50"></td></tr>

<tr class="rowOdd"><td>Date In</td><td><input type="text" name="orderdate" id="update_orderdate" value="<?php echo substr($order->orderdate,0,10); ?>" size="30" maxlength="30"></td>
<td>Date Needed</td><td><input type="text" name="requireddate" id="update_requireddate" value="<?php echo substr($order->requireddate,0,10); ?>" size="30" maxlength="30"></td></tr>

<tr class="rowEven"><td>School/Team</td><td colspan="3"><input type="text" name="name" value="<?php echo $order->name; ?>" size="90" maxlength="200"></td>

<tr class="rowOdd"><td>Payment</td><td><input type="text" name="payment" value="<?php echo $order->payment; ?>" size="30" maxlength="50"></td>
<td>Expire Date</td><td><input type="text" name="expdate" value="<?php echo $order->expdate; ?>" size="20" maxlength="50"></td></tr>

<tr class="rowEven"><td>Address</td><td colspan="3"><input type="text" name="address" value="<?php echo $order->address; ?>" size="80" maxlength="100">
&nbsp;Zip Code&nbsp;<input type="text" name="zipcode" value="<?php echo $order->zipcode; ?>" size="10" maxlength="10"></td></tr>

<tr class="rowOdd"><td>ShippingAddr</td><td colspan="3"><input type="text" name="shippingaddr" value="<?php echo $order->shippingaddr; ?>" size="80" maxlength="100">
&nbsp;Zip Code&nbsp;<input type="text" name="shippingzip" value="<?php echo $order->shippingzip; ?>" size="10" maxlength="10"></td></tr>

<tr class="rowEven"><td>Comment</td><td colspan="3"><input type="text" name="comments" value="<?php echo $order->comments; ?>" size="90" maxlength="200">

</td></tr>

</table>
<img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_save.gif" />&nbsp;<input type="submit" value="Update Order Client Info" title="Update Order Client Info" /><br>
<?php echo form_close(); ?>

<br/>
<?php 
$add_item_button = sprintf('<a href="%s/clientorderitems/form_insert_order/%d/%d/%s/%d" title="Client Order Item - Add"><img src="%s/images/buttons/button_add.jpg" width="60" /></a>'
	,WEB_CONTEXT,$clients_start,$clientid,$orderid,0,WEB_CONTEXT);
		
?>
<!-- add item button/link -->
<?php echo $add_item_button; ?>New Item&nbsp;&nbsp;
<br/>
<!-- list/update order items start -->
<?php
if ($stocks) {   
foreach($stocks->result() as $stock) {
  $style = $this->styles->select_style_by_id($stock->styleid);
  if ($style) {
    $colorname = $style->colorname;
  } else {
    $colorname = '';
  }
  $edit_button = sprintf('<a href="%s/clientorderitems/form_update_order/%d/%d/%s/%d" title="Client Order Item - Edit"><img src="%s/images/buttons/small_edit.gif" /></a>'
      ,WEB_CONTEXT,$clients_start,$clientid,$orderid,$stock->stockid,WEB_CONTEXT);
  if ($stock->stockid == $stocks_edit) {
?>
<!-- update order item start -->
<?php echo form_open('clientorderitems/update_item/'.$clients_start.'/'.$clientid.'/'.$orderid.'/'.$stocks_edit); ?>
<table class="zebraTable">
<tr class="rowEven"><th colspan="4">Update Item</th><tr>
<tr class="rowOdd"><td>Item</td><td><?php echo form_dropdown('itemcode', $items_list, $stock->itemcode, $items_options); ?>&nbsp;(Select the item to update)</td></tr>
<tr class="rowEven"><td>Style</td><td><?php echo form_dropdown('styleid', $styles_list, $stock->styleid, $styles_options); ?>&nbsp;(Select the colour for this item)</td></tr>
<tr class="rowOdd"><td>Description</td><td colspan="3"><input type="text" name="description" value="<?php echo $stock->description; ?>" size="90" maxlength="250"></td></tr>
</table>


<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowEven"><th colspan="4">Update Front Design</th><tr>
<tr class="rowOdd"><td>
<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowEven"><th colspan="2">Logo</th></tr>
<tr class="rowOdd"><td>Logo Position</td><td><?php echo form_dropdown('frontlogopos', $frontlogopos_list, $stock->frontlogopos, $frontlogopos_options.' onclick="updateFrontStyle(\''.$stock->stockid.'\');"'); ?>(Select the position for team name or logo)</td></tr>
<tr class="rowEven"><td>Logo Team Name</td><td><input type="text" name="frontlogoname" value="<?php echo $stock->frontlogoname; ?>" size="40" maxlength="50"></td></tr>
<tr class="rowOdd"><td>Logo Top Color</td>

<?php 
 $frontlogocolor_list = $artworkcolour_list;
 $frontlogocolor_list[$stock->frontlogocolor] = $stock->frontlogocolor;
 $frontlogotrimcolor_list = $artworkcolour_list;
 $frontlogotrimcolor_list[$stock->frontlogotrimcolor] = $stock->frontlogotrimcolor;
?>

<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('frontlogocolor', $frontlogocolor_list, $stock->frontlogocolor, 'id="frontlogocolor" '.$artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Logo Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('frontlogotrimcolor', $frontlogotrimcolor_list, $stock->frontlogotrimcolor, 'id="frontlogotrimcolor" '.$artworkcolour_options); ?></td></tr>

<tr class="rowOdd"><th colspan="2"></th></tr>
<tr class="rowEven"><th colspan="2">Number</th></tr>
<tr class="rowOdd"><td>Number Position</td><td><?php echo form_dropdown('frontnumpos', $frontnumpos_list, $stock->frontnumpos, $frontnumpos_options.' onclick="updateFrontStyle(\''.$stock->stockid.'\');"'); ?>(Select the position for numbers)</td></tr>
<tr class="rowEven"><td>Number Size</td><td>
<?php 
    foreach ($numsize_list as $key => $value) {
      if ($stock->frontnumsize == $key) {  
?>
<input id="frontnumsize" type="radio" name="frontnumsize" title="<?php echo $value;?>" value="<?php echo $key;?>" checked="checked" onclick="updateFrontStyle('<?php echo $stock->stockid?>');"><?php echo $value;?>&nbsp;&nbsp;&nbsp;&nbsp;
<?php
      } else {
?>
<input id="frontnumsize" type="radio" name="frontnumsize" title="<?php echo $value;?>" value="<?php echo $key;?>" onclick="updateFrontStyle('<?php echo $stock->stockid?>');"><?php echo $value;?>&nbsp;&nbsp;&nbsp;&nbsp;
<?php
      }
    }      
?>
</td></tr>
<tr class="rowOdd"><td>Number Top Color</td>

<?php 
 $frontnumcolor_list = $artworkcolour_list;
 $frontnumcolor_list[$stock->frontnumcolor] = $stock->frontnumcolor;
 $frontnumtrimcolor_list = $artworkcolour_list;
 $frontnumtrimcolor_list[$stock->frontnumtrimcolor] = $stock->frontnumtrimcolor;
?>

<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('frontnumcolor', $frontnumcolor_list, $stock->frontnumcolor, 'id="frontnumcolor" '.$artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Number Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('frontnumtrimcolor', $frontnumtrimcolor_list, $stock->frontnumtrimcolor, 'id="frontnumtrimcolor" '.$artworkcolour_options); ?></td></tr>

</table></td>
    <td align="center" style="width: 300px; height: 300px;">
    <?php if (isset($style->frontimage) && $style->frontimage != '') { ?>
    <table id="frontDesign" style="width: 300px; height: 300px; background-image:url('<?php echo WEB_UPLOADS_PATH; ?>/<?php echo $style->frontimage; ?>'); border: 0px">
    <?php } else { ?>
    <table id="frontDesign" style="width: 300px; height: 300px; background-image:url('<?php echo WEB_IMAGES_PATH; ?>/styles/default-front.jpg'); border: 0px">
    <?php } ?>
	<tr><td id="<?php echo $stock->stockid; ?>f11" style="width: 100px; height: 60px; text-align: right; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f12" style="width: 100px; height: 60px; text-align: center; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f13" style="width: 100px; height: 60px; text-align: left; vertical-align: bottom; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>f21" style="width: 100px; height: 60px; text-align: right; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f22" style="width: 100px; height: 60px; text-align: center; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f23" style="width: 100px; height: 60px; text-align: left; vertical-align: bottom; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>f31" style="width: 100px; height: 60px; text-align: right; vertical-align: middle; font-size: xx-large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f32" style="width: 100px; height: 60px; text-align: center; vertical-align: middle; font-size: xx-large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f33" style="width: 100px; height: 60px; text-align: left; vertical-align: middle; font-size: xx-large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>f41" style="width: 100px; height: 60px; text-align: right; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f42" style="width: 100px; height: 60px; text-align: center; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f43" style="width: 100px; height: 60px; text-align: left; vertical-align: top; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>f51" style="width: 100px; height: 60px; text-align: right; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f52" style="width: 100px; height: 60px; text-align: center; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f53" style="width: 100px; height: 60px; text-align: left; vertical-align: top; font-size: large; border: 0px"></td>
	</tr>  
	</table> 
    <script>
    drawFrontStyle("<?php echo $stock->stockid;?>","<?php echo $stock->frontlogopos;?>","<?php echo $stock->frontnumpos;?>","<?php echo $stock->frontnumsize;?>");
    </script>
    </td>
</tr>
</table>

<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowEven"><th colspan="4">Update Back Design</th><tr>
<tr class="rowOdd"><td>
<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowEven"><th colspan="2">Logo</th></tr>
<tr class="rowOdd"><td>Logo Position</td><td><?php echo form_dropdown('rearlogopos', $rearlogopos_list, $stock->rearlogopos, $rearlogopos_options.' onclick="updateRearStyle(\''.$stock->stockid.'\');"'); ?>(Individual name position: Center)</td></tr>
<tr class="rowEven"><td>Logo Team Name</td><td><input type="text" name="rearlogoname" value="<?php echo $stock->rearlogoname; ?>" size="40" maxlength="50"></td></tr>
<tr class="rowOdd"><td>Logo Top Color</td>

<?php 
 $rearlogocolor_list = $artworkcolour_list;
 $rearlogocolor_list[$stock->rearlogocolor] = $stock->rearlogocolor;
 $rearlogotrimcolor_list = $artworkcolour_list;
 $rearlogotrimcolor_list[$stock->rearlogotrimcolor] = $stock->rearlogotrimcolor;
?>

<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('rearlogocolor', $rearlogocolor_list, $stock->rearlogocolor, 'id="rearlogocolor" '.$artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Logo Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('rearlogotrimcolor', $rearlogotrimcolor_list, $stock->rearlogotrimcolor, 'id="rearlogotrimcolor" '.$artworkcolour_options); ?></td></tr>

<tr class="rowOdd"><th colspan="2"></th></tr>
<tr class="rowEven"><th colspan="2">Number</th></tr>
<tr class="rowOdd"><td>Number Position</td><td><?php echo form_dropdown('rearnumpos', $rearnumpos_list, $stock->rearnumpos, $rearnumpos_options.' onclick="updateRearStyle(\''.$stock->stockid.'\');"'); ?>(Select the position for numbers)</td></tr>
<tr class="rowEven"><td>Number Size</td><td>

<?php foreach ($numsize_list as $key => $value) {
  if ($stock->rearnumsize == $key) {
?>
<input id="rearnumsize" type="radio" name="rearnumsize" title="<?php echo $value;?>" value="<?php echo $key;?>" checked="checked" onclick="updateRearStyle('<?php echo $stock->stockid?>');"><?php echo $value;?>&nbsp;&nbsp;&nbsp;&nbsp;
<?php
      } else {
?>
<input id="rearnumsize" type="radio" name="rearnumsize" title="<?php echo $value;?>" value="<?php echo $key;?>" onclick="updateRearStyle('<?php echo $stock->stockid?>');"><?php echo $value;?>&nbsp;&nbsp;&nbsp;&nbsp;
<?php
      }
    }      
?>
</td></tr>
<tr class="rowOdd"><td>Number Top Color</td>
<?php 
 $rearnumcolor_list = $artworkcolour_list;
 $rearnumcolor_list[$stock->rearnumcolor] = $stock->rearnumcolor;
 $rearnumtrimcolor_list = $artworkcolour_list;
 $rearnumtrimcolor_list[$stock->rearnumtrimcolor] = $stock->rearnumtrimcolor;
?>

<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('rearnumcolor', $rearnumcolor_list, $stock->rearnumcolor, 'id="rearnumcolor" '.$artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Number Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('rearnumtrimcolor', $rearnumtrimcolor_list, $stock->rearnumtrimcolor, 'id="rearnumtrimcolor" '.$artworkcolour_options); ?></td></tr>

</table></td>
    <td align="center" style="width: 300px; height: 300px;">
    <?php if (isset($style->rearimage) && $style->rearimage != '') { ?>
    <table id="rearDesign" style="width: 300px; height: 300px; background-image:url('<?php echo WEB_UPLOADS_PATH; ?>/<?php echo $style->rearimage; ?>'); border: 0px">
    <?php } else { ?>
    <table id="rearDesign" style="width: 300px; height: 300px; background-image:url('<?php echo WEB_IMAGES_PATH; ?>/styles/default-rear.jpg'); border: 0px">
    <?php } ?>
	<tr><td id="<?php echo $stock->stockid; ?>r11" style="width: 100px; height: 60px; text-align: right; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r12" style="width: 100px; height: 60px; text-align: center; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r13" style="width: 100px; height: 60px; text-align: left; vertical-align: bottom; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>r21" style="width: 100px; height: 60px; text-align: right; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r22" style="width: 100px; height: 60px; text-align: center; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r23" style="width: 100px; height: 60px; text-align: left; vertical-align: bottom; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>r31" style="width: 100px; height: 60px; text-align: right; vertical-align: middle; font-size: xx-large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r32" style="width: 100px; height: 60px; text-align: center; vertical-align: middle; font-size: xx-large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r33" style="width: 100px; height: 60px; text-align: left; vertical-align: middle; font-size: xx-large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>r41" style="width: 100px; height: 60px; text-align: right; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r42" style="width: 100px; height: 60px; text-align: center; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r43" style="width: 100px; height: 60px; text-align: left; vertical-align: top; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>r51" style="width: 100px; height: 60px; text-align: right; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r52" style="width: 100px; height: 60px; text-align: center; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r53" style="width: 100px; height: 60px; text-align: left; vertical-align: top; font-size: large; border: 0px"></td>
	</tr>  
    </table> 
    <script>
    drawRearStyle("<?php echo $stock->stockid;?>","<?php echo $stock->rearlogopos;?>","<?php echo $stock->rearnumpos;?>","<?php echo $stock->rearnumsize;?>");
    </script>
    </td>
</tr>

</table>

<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowEven"><th colspan="4">Update Shorts Design</th><tr>
<tr class="rowOdd"><td>
<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowEven"><th colspan="2">Logo</th></tr>
<tr class="rowOdd"><td>Logo Position</td><td><?php echo form_dropdown('shortlogopos', $shortlogopos_list, $stock->sidelogopos, $shortlogopos_options.' onclick="updateShortStyle(\''.$stock->stockid.'\');"'); ?>(Select the position for team name or logo)</td></tr>
<tr class="rowEven"><td>Logo Name</td><td><input type="text" name="shortlogoname" value="<?php echo $stock->sidelogoname; ?>" size="40" maxlength="50"></td></tr>
<tr class="rowOdd"><td>Logo Top Color</td>

<?php 
 $shortlogocolor_list = $artworkcolour_list;
 $shortlogocolor_list[$stock->sidelogocolor] = $stock->sidelogocolor;
 $shortlogotrimcolor_list = $artworkcolour_list;
 $shortlogotrimcolor_list[$stock->sidelogotrimcolor] = $stock->sidelogotrimcolor;
?>

<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('shortlogocolor', $shortlogocolor_list, $stock->sidelogocolor, 'id="shortlogocolor" '.$artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Logo Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('shortlogotrimcolor', $shortlogotrimcolor_list, $stock->sidelogotrimcolor, 'id="shortlogotrimcolor" '.$artworkcolour_options); ?></td></tr>

<tr class="rowOdd"><th colspan="2"></th></tr>
<tr class="rowEven"><th colspan="2">Number</th></tr>
<tr class="rowOdd"><td>Number Position</td><td><?php echo form_dropdown('shortnumpos', $shortnumpos_list, $stock->sidenumpos, $shortnumpos_options.' onclick="updateShortStyle(\''.$stock->stockid.'\');"'); ?>(Select the position for numbers)</td></tr>
<tr class="rowEven"><td>Number Size</td><td>

<?php foreach ($numsize_list as $key => $value) {
  if ($stock->sidenumsize == $key) {
?>
<input id="shortnumsize" type="radio" name="shortnumsize" title="<?php echo $value;?>" value="<?php echo $key;?>" checked="checked" onclick="updateShortStyle('<?php echo $stock->stockid?>');"><?php echo $value;?>&nbsp;&nbsp;&nbsp;&nbsp;
<?php
      } else {
?>
<input id="shortnumsize" type="radio" name="shortnumsize" title="<?php echo $value;?>" value="<?php echo $key;?>" onclick="updateShortStyle('<?php echo $stock->stockid?>');"><?php echo $value;?>&nbsp;&nbsp;&nbsp;&nbsp;
<?php
      }
    }      
?>

<tr class="rowOdd"><td>Number Top Color</td>
<?php 
 $shortnumcolor_list = $artworkcolour_list;
 $shortnumcolor_list[$stock->sidenumcolor] = $stock->sidenumcolor;
 $shortnumtrimcolor_list = $artworkcolour_list;
 $shortnumtrimcolor_list[$stock->sidenumtrimcolor] = $stock->sidenumtrimcolor;
?>

<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('shortnumcolor', $shortnumcolor_list, $stock->sidenumcolor, 'id="shortnumcolor" '.$artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Number Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('shortnumtrimcolor', $shortnumtrimcolor_list, $stock->sidenumtrimcolor, 'id="shortnumtrimcolor" '.$artworkcolour_options); ?></td></tr>

</table>
</td>
    <td align="center" style="width: 300px; height: 300px;">
    <?php if (isset($style->sideimage) && $style->sideimage != '') { ?>
    <table id="shortDesign" style="width: 300px; height: 300px; background-image:url('<?php echo WEB_UPLOADS_PATH; ?>/<?php echo $style->sideimage; ?>'); border: 0px">
    <?php } else { ?>
    <table id="shortDesign" style="width: 300px; height: 300px; background-image:url('<?php echo WEB_IMAGES_PATH; ?>/styles/default-short.jpg'); border: 0px">
    <?php } ?>
	<tr><td id="<?php echo $stock->stockid; ?>s11" style="width: 100px; height: 60px; text-align: right; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s12" style="width: 100px; height: 60px; text-align: center; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s13" style="width: 100px; height: 60px; text-align: left; vertical-align: bottom; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>s21" style="width: 100px; height: 60px; text-align: right; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s22" style="width: 100px; height: 60px; text-align: center; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s23" style="width: 100px; height: 60px; text-align: left; vertical-align: bottom; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>s31" style="width: 100px; height: 60px; text-align: right; vertical-align: middle; font-size: xx-large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s32" style="width: 100px; height: 60px; text-align: center; vertical-align: middle; font-size: xx-large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s33" style="width: 100px; height: 60px; text-align: left; vertical-align: middle; font-size: xx-large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>s41" style="width: 100px; height: 60px; text-align: right; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s42" style="width: 100px; height: 60px; text-align: center; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s43" style="width: 100px; height: 60px; text-align: left; vertical-align: top; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>s51" style="width: 100px; height: 60px; text-align: right; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s52" style="width: 100px; height: 60px; text-align: center; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s53" style="width: 100px; height: 60px; text-align: left; vertical-align: top; font-size: large; border: 0px"></td>
	</tr>  
	</table> 
    <script>
    drawShortStyle("<?php echo $stock->stockid;?>","<?php echo $stock->sidelogopos;?>","<?php echo $stock->sidenumpos;?>","<?php echo $stock->sidenumsize;?>");
    </script>
    </td>
</tr>
</table>

<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowEven"><th>Size</th><th>Stock</th><th>Update Qty</th><th>Update Numbers</th></tr>
<tr class="rowOdd"><td>XS</td><td><?php echo $style_total->xs; ?></td><td><input type="text" name="xsmall" value="<?php echo abs($stock->xsmall); ?>" size="10" maxlength="10" /></td>
<td><input type="text" name="xsmallnumbers" value="<?php echo $stock->xsmallnumbers; ?>" size="90" maxlength="250" /></td></tr>
<tr class="rowEven"><td>S</td><td><?php echo $style_total->s; ?></td><td><input type="text" name="small" value="<?php echo abs($stock->small); ?>" size="10" maxlength="10" /></td>
<td><input type="text" name="smallnumbers" value="<?php echo $stock->smallnumbers; ?>" size="90" maxlength="250" /></td></tr>
<tr class="rowOdd"><td>M</td><td><?php echo $style_total->m; ?></td><td><input type="text" name="medium" value="<?php echo abs($stock->medium); ?>" size="10" maxlength="10" /></td>
<td><input type="text" name="mediumnumbers" value="<?php echo $stock->mediumnumbers; ?>" size="90" maxlength="250" /></td></tr>
<tr class="rowEven"><td>L</td><td><?php echo $style_total->l; ?></td><td><input type="text" name="large" value="<?php echo abs($stock->large); ?>" size="10" maxlength="10" /></td>
<td><input type="text" name="largenumbers" value="<?php echo $stock->largenumbers; ?>" size="90" maxlength="250" /></td></tr>
<tr class="rowOdd"><td>XL</td><td><?php echo $style_total->xl; ?></td><td><input type="text" name="xlarge" value="<?php echo abs($stock->xlarge); ?>" size="10" maxlength="10" /></td>
<td><input type="text" name="xlargenumbers" value="<?php echo $stock->xlargenumbers; ?>" size="90" maxlength="250" /></td></tr>
<tr class="rowEven"><td>XXL</td><td><?php echo $style_total->xxl; ?></td><td><input type="text" name="xxlarge" value="<?php echo abs($stock->xxlarge); ?>" size="10" maxlength="10" /></td>
<td><input type="text" name="xxlargenumbers" value="<?php echo $stock->xxlargenumbers; ?>" size="90" maxlength="250" /></td></tr>
<tr class="rowOdd"><td colspan="2">Comment</td><td colspan="2"><input type="text" name="comments" value="<?php echo $stock->comments; ?>" size="90" maxlength="250" /></td></tr>
<tr class="rowEven"><td colspan="2"><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_save.gif" />&nbsp;<input type="submit" value="Update Order Item" title="Update Order Item" /></td></tr>
</table>
<br/>
<!-- update order item end -->
<?php 
	echo form_close(); 
  } else {
?>
<!-- list order item start -->
<table class="printTable" style="border=1px;text-align:left;">
<tr><th colspan="4">Custom Order</th></tr>
<tr><th colspan="4"><?php echo $stock->itemname; ?></th></tr>
<tr><td>Item Code</td><td><?php echo $stock->itemcode; ?></td><td>Style Colour</td><td><?php echo $colorname; ?></td></tr>
<tr><td>Comment</td><td colspan="3"><?php echo $stock->description; ?>&nbsp;<?php echo $stock->comments; ?></td></tr>
</table>

<?php 
  if (preg_match("/short/", strtolower($stock->itemname))) {
?>
<!-- design item style -->
<table class="printTable">
<tr><th width="140px"></th><th width="300px">Team Name or Logo</th><th width="300px">Number</th><th width="300px">Style</th></tr>
<tr><td style="width: 140px; height: 300px;">Shorts</td><td align="center" style="width: 300px; height: 300px;"><?php echo $stock->sidelogoname; ?><br><small>on</small>&nbsp;
    <?php echo $this->codes->select_name_by_value($stock->sidelogopos,"ShortLogoPos"); ?><br>
    <?php echo $stock->sidelogocolor; ?><br><?php echo $stock->sidelogotrimcolor; ?></td>
    <td align="center"><?php echo $this->codes->select_name_by_value($stock->sidenumsize,"NumSize"); ?><br><small>on</small>&nbsp;
    <?php echo $this->codes->select_name_by_value($stock->sidenumpos,"ShortNumPos"); ?><br>
    <?php echo $stock->sidenumcolor; ?><br><?php echo $stock->sidenumtrimcolor; ?></td>

    <td align="center" style="width: 300px; height: 300px;">
    <?php if (isset($style->sideimage) && $style->sideimage != '') { ?>
    <table id="shortDesign" style="width: 300px; height: 300px; background-image:url('<?php echo WEB_UPLOADS_PATH; ?>/<?php echo $style->sideimage; ?>'); border: 0px">
    <?php } else { ?>
    <table id="shortDesign" style="width: 300px; height: 300px; background-image:url('<?php echo WEB_IMAGES_PATH; ?>/styles/default-short.jpg'); border: 0px">
    <?php } ?>
	<tr><td id="<?php echo $stock->stockid; ?>s11" style="width: 100px; height: 60px; text-align: right; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s12" style="width: 100px; height: 60px; text-align: center; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s13" style="width: 100px; height: 60px; text-align: left; vertical-align: bottom; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>s21" style="width: 100px; height: 60px; text-align: right; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s22" style="width: 100px; height: 60px; text-align: center; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s23" style="width: 100px; height: 60px; text-align: left; vertical-align: bottom; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>s31" style="width: 100px; height: 60px; text-align: right; vertical-align: middle; font-size: xx-large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s32" style="width: 100px; height: 60px; text-align: center; vertical-align: middle; font-size: xx-large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s33" style="width: 100px; height: 60px; text-align: left; vertical-align: middle; font-size: xx-large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>s41" style="width: 100px; height: 60px; text-align: right; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s42" style="width: 100px; height: 60px; text-align: center; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s43" style="width: 100px; height: 60px; text-align: left; vertical-align: top; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>s51" style="width: 100px; height: 60px; text-align: right; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s52" style="width: 100px; height: 60px; text-align: center; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>s53" style="width: 100px; height: 60px; text-align: left; vertical-align: top; font-size: large; border: 0px"></td>
	</tr>  
	</table> 
    <script>
    drawShortStyle("<?php echo $stock->stockid;?>","<?php echo $stock->sidelogopos;?>","<?php echo $stock->sidenumpos;?>","<?php echo $stock->sidenumsize;?>");
    </script>
    </td>
</tr></table>
<?php 
  } else {
?> 
<table class="printTable">
<tr><th width="140px"></th><th width="300px">Team Name or Logo</th><th width="300px">Number</th><th width="300px">Style</th></tr>
<tr><td style="width: 140px; height: 300px;">Front</td><td align="center"  style="width: 300px; height: 300px;"><?php echo $stock->frontlogoname; ?><br><small>on</small>&nbsp;
    <?php echo $this->codes->select_name_by_value($stock->frontlogopos,"FrontLogoPos"); ?><br>
    <?php echo $stock->frontlogocolor; ?><br><?php echo $stock->frontlogotrimcolor; ?></td>
    <td align="center"><?php echo $this->codes->select_name_by_value($stock->frontnumsize,"NumSize"); ?><br><small>on</small>&nbsp;
    <?php echo $this->codes->select_name_by_value($stock->frontnumpos,"FrontNumPos"); ?><br>
    <?php echo $stock->frontnumcolor; ?><br><?php echo $stock->frontnumtrimcolor; ?></td>
    <td align="center" style="width: 300px; height: 300px;">
    <?php if (isset($style->frontimage) && $style->frontimage != '') { ?>
    <table id="frontDesign" style="width: 300px; height: 300px; background-image:url('<?php echo WEB_UPLOADS_PATH; ?>/<?php echo $style->frontimage; ?>'); border: 0px">
    <?php } else { ?>
    <table id="frontDesign" style="width: 300px; height: 300px; background-image:url('<?php echo WEB_IMAGES_PATH; ?>/styles/default-front.jpg'); border: 0px">
    <?php } ?>
	<tr><td id="<?php echo $stock->stockid; ?>f11" style="width: 100px; height: 60px; text-align: right; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f12" style="width: 100px; height: 60px; text-align: center; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f13" style="width: 100px; height: 60px; text-align: left; vertical-align: bottom; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>f21" style="width: 100px; height: 60px; text-align: right; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f22" style="width: 100px; height: 60px; text-align: center; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f23" style="width: 100px; height: 60px; text-align: left; vertical-align: bottom; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>f31" style="width: 100px; height: 60px; text-align: right; vertical-align: middle; font-size: xx-large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f32" style="width: 100px; height: 60px; text-align: center; vertical-align: middle; font-size: xx-large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f33" style="width: 100px; height: 60px; text-align: left; vertical-align: middle; font-size: xx-large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>f41" style="width: 100px; height: 60px; text-align: right; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f42" style="width: 100px; height: 60px; text-align: center; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f43" style="width: 100px; height: 60px; text-align: left; vertical-align: top; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>f51" style="width: 100px; height: 60px; text-align: right; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f52" style="width: 100px; height: 60px; text-align: center; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>f53" style="width: 100px; height: 60px; text-align: left; vertical-align: top; font-size: large; border: 0px"></td>
	</tr>  
	</table> 
    <script>
    drawFrontStyle("<?php echo $stock->stockid;?>","<?php echo $stock->frontlogopos;?>","<?php echo $stock->frontnumpos;?>","<?php echo $stock->frontnumsize;?>");
    </script>
    </td>
</tr>
<tr><td style="width: 140px; height: 300px;">Back</td><td align="center"><?php echo $stock->rearlogoname; ?><br><small>on</small>&nbsp;
    <?php echo $this->codes->select_name_by_value($stock->rearlogopos,"RearLogoPos"); ?><br><?php echo $stock->rearlogocolor; ?><br>
    <?php echo $stock->rearlogotrimcolor; ?><br><?php echo $this->codes->select_name_by_value($stock->rearname,"RearLogPos"); ?></td>
    <td align="center"><?php echo $this->codes->select_name_by_value($stock->rearnumsize,"NumSize"); ?><br><small>on</small>&nbsp;
    <?php echo $this->codes->select_name_by_value($stock->rearnumpos,"RearNumPos"); ?><br>
    <?php echo $stock->rearnumcolor; ?><br><?php echo $stock->rearnumtrimcolor; ?></td>
    <td align="center" style="width: 300px; height: 300px;">
    <?php if (isset($style->rearimage) && $style->rearimage != '') { ?>
    <table id="rearDesign" style="width: 300px; height: 300px; background-image:url('<?php echo WEB_UPLOADS_PATH; ?>/<?php echo $style->rearimage; ?>'); border: 0px">
    <?php } else { ?>
    <table id="rearDesign" style="width: 300px; height: 300px; background-image:url('<?php echo WEB_IMAGES_PATH; ?>/styles/default-rear.jpg'); border: 0px">
    <?php } ?>
	<tr><td id="<?php echo $stock->stockid; ?>r11" style="width: 100px; height: 60px; text-align: right; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r12" style="width: 100px; height: 60px; text-align: center; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r13" style="width: 100px; height: 60px; text-align: left; vertical-align: bottom; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>r21" style="width: 100px; height: 60px; text-align: right; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r22" style="width: 100px; height: 60px; text-align: center; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r23" style="width: 100px; height: 60px; text-align: left; vertical-align: bottom; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>r31" style="width: 100px; height: 60px; text-align: right; vertical-align: middle; font-size: xx-large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r32" style="width: 100px; height: 60px; text-align: center; vertical-align: middle; font-size: xx-large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r33" style="width: 100px; height: 60px; text-align: left; vertical-align: middle; font-size: xx-large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>r41" style="width: 100px; height: 60px; text-align: right; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r42" style="width: 100px; height: 60px; text-align: center; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r43" style="width: 100px; height: 60px; text-align: left; vertical-align: top; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="<?php echo $stock->stockid; ?>r51" style="width: 100px; height: 60px; text-align: right; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r52" style="width: 100px; height: 60px; text-align: center; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="<?php echo $stock->stockid; ?>r53" style="width: 100px; height: 60px; text-align: left; vertical-align: top; font-size: large; border: 0px"></td>
	</tr>  
    </table> 
    <script>
    drawRearStyle("<?php echo $stock->stockid;?>","<?php echo $stock->rearlogopos;?>","<?php echo $stock->rearnumpos;?>","<?php echo $stock->rearnumsize;?>");
    </script>
    </td>
</tr></table>
<?php 
  }
?>
<!-- design item size -->
<table class="printTable" style="border=1px;">
<tr><th width="120">Qty</th><th width="120">Size</th><th>Numbers</th></tr>
<tr><td><?php echo abs($stock->xsmall);?></td><td>XS</td><td><?php echo $stock->xsmallnumbers;?></td></tr>
<tr><td><?php echo abs($stock->small);?></td><td>S</td><td><?php echo $stock->smallnumbers;?></td></tr>
<tr><td><?php echo abs($stock->medium);?></td><td>M</td><td><?php echo $stock->mediumnumbers;?></td></tr>
<tr><td><?php echo abs($stock->large);?></td><td>L</td><td><?php echo $stock->largenumbers;?></td></tr>
<tr><td><?php echo abs($stock->xlarge);?></td><td>XL</td><td><?php echo $stock->xlargenumbers;?></td></tr>
<tr><td><?php echo abs($stock->xxlarge);?></td><td>XXL</td><td><?php echo $stock->xxlargenumbers;?></td></tr>
<tr><td><b>Total:&nbsp;<?php echo abs($stock->subtotal);?></b></td><td></td><td></td></tr>
</table>

<?php echo $order->contact; ?>&nbsp;&nbsp;<?php echo $order->telephone; ?>&nbsp;&nbsp;<?php echo $order->email; ?>&nbsp;&nbsp;
<?php echo $edit_button; ?>&nbsp;&nbsp;
<br/><br/>
<?php 
  }
}
}
?>

<!-- add artwork button/link -->
<?php echo $add_item_button; ?>New Artwork&nbsp;&nbsp;
<?php 
if ($order_artworks) {
foreach($order_artworks->result() as $artwork) {
  $edit_artwork = sprintf('<a href="%s/clientorderitems/form_update_order/%d/%d/%s/%d/%d" title="Client Order Artwork - Edit"><img src="%s/images/buttons/small_edit.gif" /></a>'
  ,WEB_CONTEXT,$clients_start,$clientid,$orderid,$stocks_edit,$artwork->artworkid,WEB_CONTEXT);
  if ($artwork->artworkid == $artworks_edit) {
?>
<!-- update artwork -->
<?php echo form_open_multipart('clientorderitems/update_artwork/'.$clients_start.'/'.$clientid.'/'.$orderid.'/'.$stocks_edit.'/'.$artworks_edit); ?>
<table class="zebraTable">
<tr class="rowEven"><th colspan="5">Update Artwork</th><tr>
<tr class="rowOdd"><td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_save.gif" /><input type="submit" value="Update Artwork" title="Update Artwork" /></td>
<td>From: <?php echo form_dropdown('artworksource', $artworksource_list, $artwork->artworksource, $artworksource_options); ?></td>
<td>Type: <?php echo form_dropdown('artworkstatus', $artworkstatus_list, $artwork->artworkstatus, $artworkstatus_options); ?></td>
<td>File(max 2m): <input type="file" name="filename" size="20" /><br>
<a href="<?php echo WEB_CONTEXT;?>/artworks/<?php echo $artwork->filename; ?>" ><?php echo $artwork->filename; ?></a></td></tr> 
<tr class="rowOdd"><td colspan="5">Comment: <input type="text" name="comment" value="<?php echo $artwork->comment; ?>" size="50" maxlength="100" /></td></tr> 
</table>

<?php echo form_close(); ?>

<?php 
  } else {
    $filename = $artwork->filename;
    $image = '';
    if ($filename != '') {
      $index = strrpos($filename, '.');
      $ext = substr($filename, $index+1);
      if (strtolower($ext) == 'jpg' || strtolower($ext) == 'gif' || strtolower($ext) == 'png') {
        $image = '<img src="'.WEB_CONTEXT.'/artworks/'.$filename.'" width="500" />';
      } else {
        $image = '<img src="'.WEB_IMAGES_PATH.'/styles/default-front.jpg" width="500" />';
      }
    }
?>
<!-- list artworks -->
<table class="zebraTable">
<tr class="rowEven"><th colspan="2">Artwork</th><tr>
<tr class="rowOdd"><td>
Art #: <?php echo $artwork->artworkid; ?>&nbsp;&nbsp;<br/>

<?php 
if ($artwork->artworksource == 'Canuckstuff') {
?>
From: <?php echo $artwork->artworksource; ?><br/> 
Type: <?php echo $this->codes->select_name_by_value($artwork->artworkstatus, 'CanuckArtworkType'); ?><br/> 
<?php 
} else {
?>
From: Client<br/> 
Type: <?php echo $this->codes->select_name_by_value($artwork->artworkstatus, 'CustomerArtworkType'); ?><br/> 
<?php 
}
?>

Uploaded by: <?php echo $artwork->uploadby; ?><br/>
File: <a href="<?php echo WEB_CONTEXT;?>/artworks/<?php echo $artwork->filename; ?>" ><?php echo $artwork->filename; ?></a><br/>
Comment: <?php echo $artwork->comment; ?>
</td><td width="500">
<?php echo $image; ?>
</td></tr> 
</table>

<?php echo $edit_artwork; ?>

<?php 
  }
}
}  

?>
<br/>

