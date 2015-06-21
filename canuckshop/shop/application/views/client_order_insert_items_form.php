<?php if ($clientid == 0) {?>
<?php echo sprintf('<a href="%s/uniformorders/select/%d/" title="Back"><img src="%s/images/buttons/button_back.gif" /></a>', WEB_CONTEXT, $clients_start, WEB_CONTEXT); ?><br>
<?php } else {?>
<?php echo sprintf('<a href="%s/clientorderitems/select/%d/%d/%s" title="Back"><img src="%s/images/buttons/button_back.gif" /></a>', WEB_CONTEXT, $clients_start, $clientid, $orderid, WEB_CONTEXT); ?><br>
<?php }?>

<?php echo validation_errors(); ?>
<!-- <b>Order #:&nbsp;</b><?php echo $order->orderid; ?>--> 
<table class="zebraTable">
<tr class="rowEven"><th colspan="4">Client Information</th><tr>
<tr class="rowOdd"><td>Name</td><td><?php echo $order->contact; ?></td>
<td>Email</td><td><?php echo $order->email; ?></td></tr>
<tr class="rowEven"><td>Telephone</td><td><?php echo $order->telephone; ?></td>
<td>Other Phone</td><td><?php echo $order->cellphone; ?></td></tr>

<tr class="rowOdd"><td>Date In</td><td><?php echo substr($order->orderdate,0,10); ?></td>
<td>Date Needed</td><td><?php echo substr($order->requireddate,0,10); ?></td></tr>

<tr class="rowEven"><td>School/Team</td><td colspan="3"><?php echo $order->name; ?></td>

<tr class="rowOdd"><td>Payment</td><td><?php echo $order->payment; ?></td>
<td>Expire Date</td><td><?php echo $order->expdate; ?></td></tr>

<tr class="rowEven"><td>Address</td><td colspan="3"><?php echo $order->address; ?>
&nbsp;Zip Code&nbsp;<?php echo $order->zipcode; ?></td></tr>

<tr class="rowOdd"><td>ShippingAddr</td><td colspan="3"><?php echo $order->shippingaddr; ?>
&nbsp;Zip Code&nbsp;<?php echo $order->shippingzip; ?></td></tr>

<tr class="rowEven"><td>Comment</td><td colspan="3"><?php echo $order->comments; ?>

</td></tr>

</table>
<br>

<!-- add order item start -->
<table class="zebraTable">
<tr class="rowEven"><th colspan="4">Add Item</th><tr>
<?php echo form_open('clientorderitems/select_insert_item/'.$clients_start.'/'.$clientid.'/'.$orderid.'/'.$stocks_edit.'/'.$artworks_edit); ?>
<tr class="rowOdd"><td>Item</td><td><?php echo form_dropdown('itemcode', $items_list, $itemcode, $items_options); ?>&nbsp;(Select the item to add)</td></tr>
<?php echo form_close(); ?>


<?php if(isset($itemcode) && $itemcode != '') {?>

<?php echo form_open('clientorderitems/select_insert_style/'.$clients_start.'/'.$clientid.'/'.$orderid.'/'.$stocks_edit.'/'.$artworks_edit.'/'.$itemcode); ?>
<tr class="rowEven"><td>Style</td><td>

<?php echo form_dropdown('styleid', $styles_list, $styleid, $styles_options); ?>&nbsp;(Select the colour for this item)</td></tr>
<?php echo form_close(); ?>

<?php }?>

<?php if(isset($styleid) && $styleid != '') {?>

<?php echo form_open('clientorderitems/insert_item/'.$clients_start.'/'.$clientid.'/'.$orderid.'/'.$stocks_edit.'/'.$artworks_edit.'/'.$itemcode.'/'.$styleid); ?>

<tr class="rowOdd"><td>Comment</td><td colspan="3">
<input type="text" name="description" value="" size="100" maxlength="200"></td></tr>

</table>

<table class="zebraTable" style="width:100%;border=1px">

<tr class="rowEven"><th colspan="2">Add Front Design</th><tr>
<tr class="rowOdd"><td>
<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowEven"><th colspan="2">Logo</th></tr>
<tr class="rowOdd"><td width="180">Logo Position</td><td><?php echo form_dropdown('frontlogopos', $frontlogopos_list, $frontlogopos, $frontlogopos_options.' onclick="updateFrontStyle(\'\');"'); ?>(Select the position for team name or logo)</td></tr>
<tr class="rowEven"><td>Logo Team Name</td><td><input type="text" name="frontlogoname" value="" size="40" maxlength="50"></td></tr>
<tr class="rowOdd"><td>Logo Top Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('frontlogocolor', $artworkcolour_list, $frontlogocolor, 'id="frontlogocolor" '.$artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Logo Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('frontlogotrimcolor', $artworkcolour_list, $frontlogotrimcolor, 'id="frontlogotrimcolor" '.$artworkcolour_options); ?></td></tr>

<tr class="rowOdd"><th colspan="2"></th></tr>
<tr class="rowEven"><th colspan="2">Number</th></tr>
<tr class="rowOdd"><td>Number Position</td><td><?php echo form_dropdown('frontnumpos', $frontnumpos_list, $frontnumpos, $frontnumpos_options.' onclick="updateFrontStyle(\'\');"'); ?>(Select the position for numbers)</td></tr>
<tr class="rowEven"><td>Number Size</td><td>
<?php 
foreach ($numsize_list as $key => $value) {
  if ($key == 0) {
?>
<input id="frontnumsize" type="radio" name="frontnumsize" title="<?php echo $value;?>" value="<?php echo $key;?>" checked="checked" onclick="updateFrontStyle('');" ><?php echo $value;?>&nbsp;&nbsp;&nbsp;&nbsp;
<?php
      } else {
?>
<input id="frontnumsize" type="radio" name="frontnumsize" title="<?php echo $value;?>" value="<?php echo $key;?>" onclick="updateFrontStyle('');"><?php echo $value;?>&nbsp;&nbsp;&nbsp;&nbsp;
<?php }
} 

?>
</td></tr>
<tr class="rowOdd"><td>Number Top Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('frontnumcolor', $artworkcolour_list, $frontnumcolor, 'id="frontnumcolor" '.$artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Number Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('frontnumtrimcolor', $artworkcolour_list, $frontnumtrimcolor, 'id="frontnumtrimcolor" '.$artworkcolour_options); ?></td></tr>
</table></td>
    <td align="center" style="width: 300px; height: 300px;">
    <?php if (isset($style->frontimage) && $style->frontimage != '') { ?>
    <table id="frontDesign" style="width: 300px; height: 300px; background-image:url('<?php echo WEB_UPLOADS_PATH; ?>/<?php echo $style->frontimage; ?>'); border: 0px">
    <?php } else { ?>
    <table id="frontDesign" style="width: 300px; height: 300px; background-image:url('<?php echo WEB_IMAGES_PATH; ?>/styles/default-front.jpg'); border: 0px">
    <?php } ?>
	<tr><td id="f11" style="width: 100px; height: 60px; text-align: right; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="f12" style="width: 100px; height: 60px; text-align: center; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="f13" style="width: 100px; height: 60px; text-align: left; vertical-align: bottom; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="f21" style="width: 100px; height: 60px; text-align: right; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="f22" style="width: 100px; height: 60px; text-align: center; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="f23" style="width: 100px; height: 60px; text-align: left; vertical-align: bottom; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="f31" style="width: 100px; height: 60px; text-align: right; vertical-align: middle; font-size: xx-large; border: 0px"></td>
		<td id="f32" style="width: 100px; height: 60px; text-align: center; vertical-align: middle; font-size: xx-large; border: 0px"></td>
		<td id="f33" style="width: 100px; height: 60px; text-align: left; vertical-align: middle; font-size: xx-large; border: 0px"></td>
	</tr>  
	<tr><td id="f41" style="width: 100px; height: 60px; text-align: right; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="f42" style="width: 100px; height: 60px; text-align: center; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="f43" style="width: 100px; height: 60px; text-align: left; vertical-align: top; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="f51" style="width: 100px; height: 60px; text-align: right; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="f52" style="width: 100px; height: 60px; text-align: center; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="f53" style="width: 100px; height: 60px; text-align: left; vertical-align: top; font-size: large; border: 0px"></td>
	</tr>  
	</table> 
    </td>
</tr>
</table>

<table class="zebraTable" style="width:100%;border=1px">

<tr class="rowEven"><th colspan="2">Add Back Design</th><tr>
<tr class="rowOdd"><td>
<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowEven"><th colspan="2">Logo</th></tr>
<tr class="rowOdd"><td width="180">Logo Position</td><td><?php echo form_dropdown('rearlogopos', $rearlogopos_list, $rearlogopos, $rearlogopos_options.' onclick="updateRearStyle(\'\');"'); ?>(Individual name position: Center)</td></tr>
<tr class="rowEven"><td>Logo Team Name</td><td><input type="text" name="rearlogoname" value="" size="40" maxlength="50"></td></tr>
<tr class="rowOdd"><td>Logo Top Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('rearlogocolor', $artworkcolour_list, $rearlogocolor, 'id="rearlogocolor" '.$artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Logo Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('rearlogotrimcolor', $artworkcolour_list, $rearlogotrimcolor, 'id="rearlogotrimcolor" '.$artworkcolour_options); ?></td></tr>

<tr class="rowOdd"><th colspan="2"></th></tr>
<tr class="rowEven"><th colspan="2">Number</th></tr>
<tr class="rowOdd"><td>Number Position</td><td><?php echo form_dropdown('rearnumpos', $rearnumpos_list, $rearnumpos, $rearnumpos_options.' onclick="updateRearStyle(\'\');"'); ?>(Select the position for numbers)</td></tr>
<tr class="rowEven"><td>Number Size</td><td>
<?php 
foreach ($numsize_list as $key => $value) {
  if ($key == 0) {
?>
<input id="rearnumsize" type="radio" name="rearnumsize" title="<?php echo $value;?>" value="<?php echo $key;?>" checked="checked" onclick="updateRearStyle('');"><?php echo $value;?>&nbsp;&nbsp;&nbsp;&nbsp;
<?php
      } else {
?>
<input id="rearnumsize" type="radio" name="rearnumsize" title="<?php echo $value;?>" value="<?php echo $key;?>" onclick="updateRearStyle('');"><?php echo $value;?>&nbsp;&nbsp;&nbsp;&nbsp;
<?php }
} 
?>
</td></tr>
<tr class="rowOdd"><td>Number Top Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('rearnumcolor', $artworkcolour_list, $rearnumcolor, 'id="rearnumcolor" '.$artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Number Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('rearnumtrimcolor', $artworkcolour_list, $rearnumtrimcolor, 'id="rearnumtrimcolor" '.$artworkcolour_options); ?></td></tr>
</table></td>
    <td align="center" style="width: 300px; height: 300px;">
    <?php if (isset($style->rearimage) && $style->rearimage != '') { ?>
    <table id="rearDesign" style="width: 300px; height: 300px; background-image:url('<?php echo WEB_UPLOADS_PATH; ?>/<?php echo $style->rearimage; ?>'); border: 0px">
    <?php } else { ?>
    <table id="rearDesign" style="width: 300px; height: 300px; background-image:url('<?php echo WEB_IMAGES_PATH; ?>/styles/default-rear.jpg'); border: 0px">
    <?php } ?>
	<tr><td id="r11" style="width: 100px; height: 60px; text-align: right; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="r12" style="width: 100px; height: 60px; text-align: center; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="r13" style="width: 100px; height: 60px; text-align: left; vertical-align: bottom; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="r21" style="width: 100px; height: 60px; text-align: right; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="r22" style="width: 100px; height: 60px; text-align: center; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="r23" style="width: 100px; height: 60px; text-align: left; vertical-align: bottom; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="r31" style="width: 100px; height: 60px; text-align: right; vertical-align: middle; font-size: xx-large; border: 0px"></td>
		<td id="r32" style="width: 100px; height: 60px; text-align: center; vertical-align: middle; font-size: xx-large; border: 0px"></td>
		<td id="r33" style="width: 100px; height: 60px; text-align: left; vertical-align: middle; font-size: xx-large; border: 0px"></td>
	</tr>  
	<tr><td id="r41" style="width: 100px; height: 60px; text-align: right; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="r42" style="width: 100px; height: 60px; text-align: center; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="r43" style="width: 100px; height: 60px; text-align: left; vertical-align: top; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="r51" style="width: 100px; height: 60px; text-align: right; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="r52" style="width: 100px; height: 60px; text-align: center; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="r53" style="width: 100px; height: 60px; text-align: left; vertical-align: top; font-size: large; border: 0px"></td>
	</tr>  
    </table> 
    </td>
</tr>
</table>

<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowEven"><th colspan="2">Add Shorts Design</th><tr>
<tr class="rowOdd"><td>
<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowEven"><th colspan="2">Logo</th></tr>
<tr class="rowEven"><td width="180">Logo Position</td><td><?php echo form_dropdown('shortlogopos', $shortlogopos_list, $shortlogopos, $shortlogopos_options.' onclick="updateShortStyle(\'\');"'); ?>(Select the position for team name or logo)</td></tr>
<tr class="rowOdd"><td>Logo Top Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('shortlogocolor', $artworkcolour_list, $shortlogocolor, 'id="shortlogocolor" '.$artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Logo Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('shortlogotrimcolor', $artworkcolour_list, $shortlogotrimcolor, 'id="shortlogotrimcolor" '.$artworkcolour_options); ?></td></tr>

<tr class="rowOdd"><th colspan="2"></th></tr>
<tr class="rowEven"><th colspan="2">Number</th></tr>
<tr class="rowOdd"><td>Number Position</td><td><?php echo form_dropdown('shortnumpos', $shortnumpos_list, $shortnumpos, $shortnumpos_options.' onclick="updateShortStyle(\'\');"'); ?>(Select the position for numbers)</td></tr>
<tr class="rowEven"><td>Number Size</td><td>
<?php 
foreach ($numsize_list as $key => $value) {
  if ($key == 0) {
?>
<input id="shortnumsize" type="radio" name="shortnumsize" title="<?php echo $value;?>" value="<?php echo $key;?>" checked="checked" onclick="updateShortStyle('');"><?php echo $value;?>&nbsp;&nbsp;&nbsp;&nbsp;
<?php
      } else {
?>
<input id="shortnumsize" type="radio" name="shortnumsize" title="<?php echo $value;?>" value="<?php echo $key;?>" onclick="updateShortStyle('');"><?php echo $value;?>&nbsp;&nbsp;&nbsp;&nbsp;
<?php }
} 
?>
</td></tr>
<tr class="rowOdd"><td>Number Top Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('shortnumcolor', $artworkcolour_list, $shortnumcolor, 'id="shortnumcolor" '.$artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Number Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('shortnumtrimcolor', $artworkcolour_list, $shortnumtrimcolor, 'id="shortnumtrimcolor" '.$artworkcolour_options); ?></td></tr>
</table>
</td>
    <td align="center" style="width: 300px; height: 300px;">
    <?php if (isset($style->sideimage) && $style->sideimage != '') { ?>
    <table id="shortDesign" style="width: 300px; height: 300px; background-image:url('<?php echo WEB_UPLOADS_PATH; ?>/<?php echo $style->sideimage; ?>'); border: 0px">
    <?php } else { ?>
    <table id="shortDesign" style="width: 300px; height: 300px; background-image:url('<?php echo WEB_IMAGES_PATH; ?>/styles/default-short.jpg'); border: 0px">
    <?php } ?>
	<tr><td id="s11" style="width: 100px; height: 60px; text-align: right; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="s12" style="width: 100px; height: 60px; text-align: center; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="s13" style="width: 100px; height: 60px; text-align: left; vertical-align: bottom; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="s21" style="width: 100px; height: 60px; text-align: right; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="s22" style="width: 100px; height: 60px; text-align: center; vertical-align: bottom; font-size: large; border: 0px"></td>
		<td id="s23" style="width: 100px; height: 60px; text-align: left; vertical-align: bottom; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="s31" style="width: 100px; height: 60px; text-align: right; vertical-align: middle; font-size: xx-large; border: 0px"></td>
		<td id="s32" style="width: 100px; height: 60px; text-align: center; vertical-align: middle; font-size: xx-large; border: 0px"></td>
		<td id="s33" style="width: 100px; height: 60px; text-align: left; vertical-align: middle; font-size: xx-large; border: 0px"></td>
	</tr>  
	<tr><td id="s41" style="width: 100px; height: 60px; text-align: right; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="s42" style="width: 100px; height: 60px; text-align: center; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="s43" style="width: 100px; height: 60px; text-align: left; vertical-align: top; font-size: large; border: 0px"></td>
	</tr>  
	<tr><td id="s51" style="width: 100px; height: 60px; text-align: right; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="s52" style="width: 100px; height: 60px; text-align: center; vertical-align: top; font-size: large; border: 0px"></td>
		<td id="s53" style="width: 100px; height: 60px; text-align: left; vertical-align: top; font-size: large; border: 0px"></td>
	</tr>  
	</table> 
    </td>
</tr>
</table>

<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowEven"><th>Size</th><th>Stock</th><th>Add Qty</th><th>Add Numbers</th></tr>
<tr class="rowOdd"><td>XS</td><td><?php echo $style_total->xs; ?></td><td><input type="text" name="xsmall" size="10" maxlength="10" /></td><td><input type="text" name="xsmallnumbers" size="90" maxlength="250" /></td></tr>
<tr class="rowEven"><td>S</td><td><?php echo $style_total->s; ?></td><td><input type="text" name="small" size="10" maxlength="10" /></td><td><input type="text" name="smallnumbers" size="90" maxlength="250" /></td></tr>
<tr class="rowOdd"><td>M</td><td><?php echo $style_total->m; ?></td><td><input type="text" name="medium" size="10" maxlength="10" /></td><td><input type="text" name="mediumnumbers" size="90" maxlength="250" /></td></tr>
<tr class="rowEven"><td>L</td><td><?php echo $style_total->l; ?></td><td><input type="text" name="large" size="10" maxlength="10" /></td><td><input type="text" name="largenumbers" size="90" maxlength="250" /></td></tr>
<tr class="rowOdd"><td>XL</td><td><?php echo $style_total->xl; ?></td><td><input type="text" name="xlarge" size="10" maxlength="10" /></td><td><input type="text" name="xlargenumbers" size="90" maxlength="250" /></td></tr>
<tr class="rowEven"><td>XXL</td><td><?php echo $style_total->xxl; ?></td><td><input type="text" name="xxlarge" size="10" maxlength="10" /></td><td><input type="text" name="xxlargenumbers" size="90" maxlength="250" /></td></tr>
<tr class="rowOdd"><td colspan="4"><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_save.gif" />&nbsp;<input type="submit" value="Add New Order Item" title="Add New Order Item" /></td></tr>
<?php echo form_close(); ?>

<?php }?>

</table>
<script>
  updateFrontStyle('');
  updateRearStyle('');
  updateShortStyle('');
</script>
<!-- add order item end -->

<br><br>

<!-- list order items start -->
<?php
if ($stocks) {   
foreach($stocks->result() as $stock) {
  $style = $this->styles->select_style_by_id($stock->styleid);
  
?>

<table class="printTable" style="border=1px;text-align:left;">
<tr><th colspan="4">Custom Order Item</th></tr>
<tr><th colspan="4"><?php echo $stock->itemname; ?></th></tr>
<tr><td>Item Code</td><td><?php echo $stock->itemcode; ?></td><td>Style Colour</td><td><?php echo $style->colorname; ?></td></tr>
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

<?php echo $order->contact; ?>&nbsp;&nbsp;<?php echo $order->telephone; ?>&nbsp;&nbsp;<?php echo $order->email; ?>
<br/>
<br/>

<?php 
}
}
?>
        
<!-- list order items end -->
<br>

<!-- add artwork -->
<?php echo form_open_multipart('clientorderitems/insert_artwork/'.$clients_start.'/'.$clientid.'/'.$orderid.'/'.$stocks_edit.'/'.$artworks_edit); ?>

<table class="zebraTable">
<tr class="rowEven"><th colspan="4">Add Artwork</th><tr>
<tr class="rowOdd">
<td>From: </td><td><?php echo form_dropdown('artworksource', $artworksource_list, '', $artworksource_options); ?></td>
<td>Type: </td><td><?php echo form_dropdown('artworkstatus', $artworkstatus_list, '', $artworkstatus_options); ?></td></tr>
<tr class="rowOdd">
<td>File(max 2m): </td><td><input type="file" name="filename" size="20" /></td>
<td>Comment: </td><td><input type="text" name="comment" value="" size="40" maxlength="100" /></td></tr>
<tr class="rowOdd">
<td colspan="4"><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_save.gif" />&nbsp;<input type="submit" value="Add Artwork" title="Add Artwork" /></td></tr> 
</table>

<?php echo form_close(); ?>
<br>

<!-- list artwork start -->

<?php 
if ($order_artworks) {
foreach($order_artworks->result() as $artwork) {
    $filename = $artwork->filename;
    $image = '';
    if ($filename != '') {
      $index = strrpos($filename, '.');
      $ext = substr($filename, $index+1);
      if (strtolower($ext) == 'jpg' || strtolower($ext) == 'gif' || strtolower($ext) == 'png') {
        $image = '<img src="'.WEB_CONTEXT.'/artworks/'.$filename.'" width="500px" />';
      } else {
        $image = '<img src="'.WEB_IMAGES_PATH.'/styles/default-front.jpg" width="500px" />';
      }
    }
?>
<table class="zebraTable">
<tr class="rowEven"><th colspan="2">Custom Artwork</th><tr>
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

<?php 
}
}
?>

<!-- list artwork end -->
