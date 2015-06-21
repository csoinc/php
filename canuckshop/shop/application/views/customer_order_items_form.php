<?php echo sprintf('<a href="%s/customerorderitems/select/%d/%s" title="Previous"><img src="%s/images/icons/icon_previous.gif" height="20px" /></a>', WEB_CONTEXT, $clientid, $orderid, WEB_CONTEXT); ?>&nbsp;&nbsp;
<?php echo sprintf('<a href="%s/customerorderitems/select/%d/%s" title="Preview"><img src="%s/images/icons/icon_preview.gif" height="20px" /></a>', WEB_CONTEXT, $clientid, $orderid, WEB_CONTEXT); ?><br>

<?php echo validation_errors(); ?>
<?php echo form_open('customerorderitems/update_status/'.$clientid.'/'.$orderid); ?>
<b>Order Status: </b><?php echo form_dropdown('orderstatus', $orderstatus_list, $order->orderstatus, $orderstatus_options); ?>&nbsp;&nbsp;
<?php 
if ($order->orderstatus == '0') {
?>
<img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_save.gif" /><input type="submit" value="Submit to Canuckstuff for Mockup" title="Submit to Canuckstuff for Mockup" />&nbsp;&nbsp;<br/>
<?php 
} else if ($order->orderstatus == '1') {
?>
<img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_save.gif" /><input type="submit" value="Confirm to Canuckstuff for Printing" title="Confirm to Canuckstuff for Printing" />&nbsp;&nbsp;<br/>
<?php 
}
?>

<?php echo form_close(); ?>

<?php echo form_open('customerorderitems/update_customer/'.$clientid.'/'.$orderid.'/'.$stocks_edit.'/'.$artworks_edit); ?>
<b>Order #:&nbsp;</b><?php echo $order->orderid; ?> 
<table class="zebraTable">
<tr class="rowEven"><th colspan="4">Customer Information</th><tr>

<tr class="rowOdd"><td>Name</td><td><input type="text" name="contact" value="<?php echo $order->contact; ?>" size="30" maxlength="50"></td>
<td>Email</td><td><input type="text" name="email" value="<?php echo $order->email; ?>" size="30" maxlength="50"></td></tr>

<tr class="rowEven"><td>Telephone</td><td><input id="telephone" type="text" name="telephone" value="<?php echo $order->telephone; ?>" size="30" maxlength="50">(###-###-####)</td>
<td>Cell Phone</td><td><input id="cellphone" type="text" name="cellphone" value="<?php echo $order->cellphone; ?>" size="30" maxlength="50">(###-###-####)</td></tr>

<tr class="rowOdd"><td>Date Ordered</td><td><input type="text" name="orderdate" id="update_orderdate" value="<?php echo substr($order->orderdate,0,10); ?>" size="30" maxlength="30"></td>
<td>Date Needed</td><td><input type="text" name="requireddate" id="update_requireddate" value="<?php echo substr($order->requireddate,0,10); ?>" size="30" maxlength="30"></td></tr>

<tr class="rowEven"><td>School</td><td colspan="3"><input type="text" name="name" value="<?php echo $order->name; ?>" size="120" maxlength="200"></td>

<!-- 
<tr class="rowOdd"><td>Payment</td><td><input type="text" name="payment" value="<?php echo $order->payment; ?>" size="30" maxlength="50"></td>
<td>Expire Date</td><td><input type="text" name="expdate" value="<?php echo $order->expdate; ?>" size="20" maxlength="50"></td></tr>

<tr class="rowEven"><td>Address</td><td colspan="3"><input type="text" name="address" value="<?php echo $order->address; ?>" size="80" maxlength="100">
&nbsp;Zip Code&nbsp;<input type="text" name="zipcode" value="<?php echo $order->zipcode; ?>" size="10" maxlength="10"></td></tr>
-->

<tr class="rowOdd"><td>ShippingAddr</td><td colspan="3"><input type="text" name="shippingaddr" value="<?php echo $order->shippingaddr; ?>" size="80" maxlength="100">
&nbsp;Zip Code&nbsp;<input type="text" name="shippingzip" value="<?php echo $order->shippingzip; ?>" size="10" maxlength="10"></td></tr>

<tr class="rowEven"><td>Comment</td><td colspan="3"><input type="text" name="comments" value="<?php echo $order->comments; ?>" size="120" maxlength="200">

</td></tr>

</table>
<img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_save.gif" />&nbsp;<input type="submit" value="Update Customer Info" title="Update Customer Info" /><br>
<?php echo form_close(); ?>


<?php 
if ($order_stocks) {
foreach($order_stocks as $stock) {
  $style = $this->styles->select_style_by_id($stock->styleid);
  $edit_col = sprintf('<a href="%s/customerorderitems/form_order/%d/%s/%d" title="Client Order Item - Edit"><img src="%s/images/buttons/small_edit.gif" /></a>'
  ,WEB_CONTEXT,$clientid,$orderid,$stock->stockid,WEB_CONTEXT);
  if ($stock->stockid == $stocks_edit) {
    $check_item_col = sprintf('<a href="%s/customerorderitems/check_item/%d/%s/%d" title="Check Item Online" target=_canuckstuff>Check this item online</a>'
    ,WEB_CONTEXT,$clientid,$orderid,$stock->stockid,WEB_CONTEXT);
    $select_item_col = sprintf('<a href="%s/customerorderitems/form_update_item/%d/%s/%d/%d" title="Update Item">Change item for this order</a>'
    ,WEB_CONTEXT,$clientid,$orderid,$stocks_edit,$artworks_edit,WEB_CONTEXT);
    
?>
<?php echo form_open('customerorderitems/update_stock/'.$clientid.'/'.$orderid.'/'.$stocks_edit.'/'.$stock->itemcode); ?>

<!-- update item -->
<table class="zebraTable">
<tr class="rowEven"><th colspan="2">Update Item</th><tr>
<tr class="rowOdd"><td>Item</td><td><?php echo form_dropdown('itemcode', $items_edit_list, $stock->itemcode, $items_edit_options); ?>&nbsp;<?php echo $select_item_col; ?> | <?php echo $check_item_col; ?></td></tr>
<tr class="rowEven"><td>Style</td><td><?php echo form_dropdown('styleid', $styles_edit_list, $stock->styleid, $styles_edit_options); ?>&nbsp;(Select the colour for this item)</td></tr>
<tr class="rowOdd"><td>Description</td><td colspan="3">
<input type="text" name="description" value="<?php echo $stock->description; ?>" size="120" maxlength="250"></td></tr>
</table>

<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowEven"><th colspan="2">Update Front Design</th><tr>
<tr class="rowOdd"><td>
<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowOdd"><td width="180">Logo Position</td><td><?php echo form_dropdown('frontlogopos', $frontlogopos_list, $stock->frontlogopos, $frontlogopos_options); ?>(Select the position for team name or logo)</td></tr>
<tr class="rowEven"><td>Logo Team Name</td><td><input type="text" name="frontlogoname" value="<?php echo $stock->frontlogoname; ?>" size="40" maxlength="50"></td></tr>
<tr class="rowOdd"><td>Logo Top Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('frontlogocolor', $artworkcolour_list, $stock->frontlogocolor, $artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Logo Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('frontlogotrimcolor', $artworkcolour_list, $stock->frontlogotrimcolor, $artworkcolour_options); ?></td></tr>
<tr class="rowOdd"><td>Number Position</td><td><?php echo form_dropdown('frontnumpos', $frontnumpos_list, $stock->frontnumpos, $frontnumpos_options); ?>(Select the position for numbers)</td></tr>
<tr class="rowEven"><td>Number Size</td><td>
<?php 
    foreach ($numsize_list as $key => $value) {
      if ($stock->frontnumsize == $key) {  
?>
<input type="radio" name="frontnumsize" title="<?php echo $value;?>" value="<?php echo $key;?>" checked="checked" ><?php echo $value;?>&nbsp;&nbsp;&nbsp;&nbsp;
<?php
      } else {
?>
<input type="radio" name="frontnumsize" title="<?php echo $value;?>" value="<?php echo $key;?>" ><?php echo $value;?>&nbsp;&nbsp;&nbsp;&nbsp;
<?php
      }
    }      
?>
</td></tr>
<tr class="rowOdd"><td>Number Top Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('frontnumcolor', $artworkcolour_list, $stock->frontnumcolor, $artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Number Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('frontnumtrimcolor', $artworkcolour_list, $stock->frontnumtrimcolor, $artworkcolour_options); ?></td></tr>

</table></td>
<td width="200">
<?php 
if (isset($style->frontimage) && $style->frontimage != '') {
?>  
<img src="<?php echo WEB_UPLOADS_PATH; ?>/<?php echo $style->frontimage; ?>" width="200" >
<?php
} else {
?>
<img src="<?php echo WEB_IMAGES_PATH; ?>/styles/default-front.jpg" width="200" >
<?php 
}
?>

</td>
</tr>

</table>

<table class="zebraTable" style="width:100%;border=1px">

<tr class="rowEven"><th colspan="2">Update Back Design</th><tr>

<tr class="rowOdd"><td>
<table class="zebraTable" style="width:100%;border=1px">

<tr class="rowOdd"><td width="180">Logo Position</td><td><?php echo form_dropdown('rearlogopos', $rearlogopos_list, $stock->rearlogopos, $rearlogopos_options); ?>(Individual name position: Center)</td></tr>
<tr class="rowEven"><td>Logo Team Name</td><td><input type="text" name="rearlogoname" value="<?php echo $stock->rearlogoname; ?>" size="40" maxlength="50"></td></tr>
<tr class="rowOdd"><td>Logo Top Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('rearlogocolor', $artworkcolour_list, $stock->rearlogocolor, $artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Logo Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('rearlogotrimcolor', $artworkcolour_list, $stock->rearlogotrimcolor, $artworkcolour_options); ?></td></tr>
<tr class="rowOdd"><td>Number Position</td><td><?php echo form_dropdown('rearnumpos', $rearnumpos_list, $stock->rearnumpos, $rearnumpos_options); ?>(Select the position for numbers)</td></tr>
<tr class="rowEven"><td>Number Size</td><td>

<?php foreach ($numsize_list as $key => $value) {
  if ($stock->rearnumsize == $key) {
?>
<input type="radio" name="rearnumsize" title="<?php echo $value;?>" value="<?php echo $key;?>" checked="checked" ><?php echo $value;?>&nbsp;&nbsp;&nbsp;&nbsp;
<?php
      } else {
?>
<input type="radio" name="rearnumsize" title="<?php echo $value;?>" value="<?php echo $key;?>" ><?php echo $value;?>&nbsp;&nbsp;&nbsp;&nbsp;
<?php
      }
    }      
?>
</td></tr>
<tr class="rowOdd"><td>Number Top Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('rearnumcolor', $artworkcolour_list, $stock->rearnumcolor, $artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Number Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('rearnumtrimcolor', $artworkcolour_list, $stock->rearnumtrimcolor, $artworkcolour_options); ?></td></tr>

</table></td>
<td width="200">
<?php 
if (isset($style->frontrear) && $style->rearimage != '') {
?>  
<img src="<?php echo WEB_UPLOADS_PATH; ?>/<?php echo $style->rearimage; ?>" width="200" >
<?php
} else {
?>
<img src="<?php echo WEB_IMAGES_PATH; ?>/styles/default-rear.jpg" width="200" >
<?php 
}
?>

</td>
</tr>

</table>

<table class="zebraTable" style="width:100%;border=1px">

<tr class="rowEven"><th colspan="2">Update Shorts Design</th><tr>

<tr class="rowOdd"><td>
<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowOdd"><td width="180">Logo Position</td><td><?php echo form_dropdown('shortlogopos', $shortlogopos_list, $stock->sidelogopos, $shortlogopos_options); ?>(Select the position for team name or logo)</td></tr>
<tr class="rowEven"><td width="180">Number Position</td><td><?php echo form_dropdown('shortnumpos', $shortnumpos_list, $stock->sidenumpos, $shortnumpos_options); ?>(Select the position for numbers)</td></tr>
</table></td>
<td width="200">
<?php 
if (isset($style->sideimage) && $style->sideimage != '') {
?>  
<img src="<?php echo WEB_UPLOADS_PATH; ?>/<?php echo $style->sideimage; ?>" width="200" >
<?php
} else {
?>
<img src="<?php echo WEB_IMAGES_PATH; ?>/styles/default-short.jpg" width="200" >
<?php 
}
?>
</td></tr>
</table>

<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowEven"><th>Size</th><th>Update Qty</th><th>Update Numbers</th></tr>
<tr class="rowOdd"><td>XS</td><td><input type="text" name="xsmall" value="<?php echo abs($stock->xsmall); ?>" size="10" maxlength="10" /></td>
<td><input type="text" name="xsmallnumbers" value="<?php echo $stock->xsmallnumbers; ?>" size="100" maxlength="250" /></td></tr>
<tr class="rowEven"><td>S</td><td><input type="text" name="small" value="<?php echo abs($stock->small); ?>" size="10" maxlength="10" /></td>
<td><input type="text" name="smallnumbers" value="<?php echo $stock->smallnumbers; ?>" size="100" maxlength="250" /></td></tr>
<tr class="rowOdd"><td>M</td><td><input type="text" name="medium" value="<?php echo abs($stock->medium); ?>" size="10" maxlength="10" /></td>
<td><input type="text" name="mediumnumbers" value="<?php echo $stock->mediumnumbers; ?>" size="100" maxlength="250" /></td></tr>
<tr class="rowEven"><td>L</td><td><input type="text" name="large" value="<?php echo abs($stock->large); ?>" size="10" maxlength="10" /></td>
<td><input type="text" name="largenumbers" value="<?php echo $stock->largenumbers; ?>" size="100" maxlength="250" /></td></tr>
<tr class="rowOdd"><td>XL</td><td><input type="text" name="xlarge" value="<?php echo abs($stock->xlarge); ?>" size="10" maxlength="10" /></td>
<td><input type="text" name="xlargenumbers" value="<?php echo $stock->xlargenumbers; ?>" size="100" maxlength="250" /></td></tr>
<tr class="rowEven"><td>XXL</td><td><input type="text" name="xxlarge" value="<?php echo abs($stock->xxlarge); ?>" size="10" maxlength="10" /></td>
<td><input type="text" name="xxlargenumbers" value="<?php echo $stock->xxlargenumbers; ?>" size="100" maxlength="250" /></td></tr>
</table>

<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowOdd"><td>Comment</td><td><input type="text" name="comments" value="<?php echo $stock->comments; ?>" size="120" maxlength="250" /></td></tr>
<tr class="rowEven"><td colspan="2"><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_save.gif" />&nbsp;<input type="submit" value="Update Order Item" title="Update Order Item" /></td></tr>
</table>

<?php echo form_close(); ?>


<?php 
  } else {
?>
<!-- read only item -->
<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowEven"><th colspan="2">Item</th><tr>
<tr class="rowOdd"><td width="180px">Item</td><td><?php echo $stock->itemcode.' - '.$this->items->select_itemname($stock->itemcode); ?></td></tr>
<tr class="rowEven"><td>Style</td><td><?php echo $style->colorname; ?></td></tr>
<tr class="rowOdd"><td>Description</td><td><?php echo $stock->description; ?></td></tr>
</table>

<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowEven"><th colspan="2">Front Design</th><tr>
<tr class="rowOdd"><td>
<table style="width:100%;border=1px">
<tr class="rowOdd"><td width="180px">Logo Position</td><td><?php echo $this->codes->select_name_by_value($stock->frontlogopos, 'FrontLogoPos'); ?></td></tr>
<tr class="rowEven"><td>Logo Team Name</td><td><?php echo $stock->frontlogoname; ?></td></tr>
<tr class="rowOdd"><td>Logo Top Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo $stock->frontlogocolor; ?></td></tr>
<tr class="rowEven"><td>Logo Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo $stock->frontlogotrimcolor; ?></td></tr>
<tr class="rowOdd"><td>Number Position</td><td><?php echo $this->codes->select_name_by_value($stock->frontnumpos, 'FrontNumPos'); ?></td></tr>
<tr class="rowEven"><td>Number Size</td><td><?php echo $this->codes->select_name_by_value($stock->frontnumsize, 'NumSize'); ?></td></tr>
<tr class="rowOdd"><td>Number Top Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo $stock->frontnumcolor; ?></td></tr>
<tr class="rowEven"><td>Number Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo $stock->frontnumtrimcolor; ?></td></tr>
</table></td>
<td width="200">
<?php 
if (isset($style->frontimage) && $style->frontimage != '') {
?>  
<img src="<?php echo WEB_UPLOADS_PATH; ?>/<?php echo $style->frontimage; ?>" width="190" >
<?php
} else {
?>
<img src="<?php echo WEB_IMAGES_PATH; ?>/styles/default-front.jpg" width="190" >
<?php 
}
?>
</td></tr>
</table>

<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowEven"><th colspan="2">Back Design</th><tr>
<tr class="rowOdd"><td>
<table style="width:100%;border=1px">
<tr class="rowOdd"><td width="180px">Logo Position</td><td><?php echo $this->codes->select_name_by_value($stock->rearlogopos, 'RearLogoPos'); ?></td></tr>
<tr class="rowEven"><td>Logo Team Name</td><td><?php echo $stock->rearlogoname; ?></td></tr>
<tr class="rowOdd"><td>Logo Top Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo $stock->rearlogocolor; ?></td></tr>
<tr class="rowEven"><td>Logo Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo $stock->rearlogotrimcolor; ?></td></tr>
<tr class="rowOdd"><td>Number Position</td><td><?php echo $this->codes->select_name_by_value($stock->rearnumpos, 'RearNumPos'); ?></td></tr>
<tr class="rowEven"><td>Number Size</td><td><?php echo $this->codes->select_name_by_value($stock->rearnumsize, 'NumSize'); ?></td></tr>
<tr class="rowOdd"><td>Number Top Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo $stock->rearnumcolor; ?></td></tr>
<tr class="rowEven"><td>Number Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo $stock->rearnumtrimcolor; ?></td></tr>
</table></td>
<td style="width:200px">
<?php 
if (isset($style->rearimage) && $style->rearimage != '') {
?>  
<img src="<?php echo WEB_UPLOADS_PATH; ?>/<?php echo $style->rearimage; ?>" width="190" >
<?php
} else {
?>
<img src="<?php echo WEB_IMAGES_PATH; ?>/styles/default-rear.jpg" width="190" >
<?php 
}
?>

</td></tr>

</table>

<table class="zebraTable" style="width:100%;border=1px">

<tr class="rowEven"><th colspan="2">Shorts Design</th><tr>
<tr class="rowOdd"><td>
<table style="width:100%;border=1px">
<tr class="rowOdd"><td width="180px">Logo Position</td><td><?php echo $this->codes->select_name_by_value($stock->sidelogopos, 'ShortLogoPos'); ?></td></tr>
<tr class="rowEven"><td>Number Position</td><td><?php echo $this->codes->select_name_by_value($stock->sidenumpos, 'ShortNumPos'); ?></td></tr>
</table></td>
<td width="200">
<?php 
if (isset($style->sideimage) && $style->sideimage != '') {
?>  
<img src="<?php echo WEB_UPLOADS_PATH; ?>/<?php echo $style->sideimage; ?>" width="190" >
<?php
} else {
?>
<img src="<?php echo WEB_IMAGES_PATH; ?>/styles/default-short.jpg" width="190" >
<?php 
}
?>
</td></tr>

</table>

<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowEven"><th>Size</th><th>Qty</th><th>Numbers</th></tr>
<tr class="rowOdd"><td>XS</td><td><?php echo abs($stock->xsmall); ?></td>
<td><?php echo $stock->xsmallnumbers; ?></td></tr>
<tr class="rowEven"><td>S</td><td><?php echo abs($stock->small); ?></td>
<td><?php echo $stock->smallnumbers; ?></td></tr>
<tr class="rowOdd"><td>M</td><td><?php echo abs($stock->medium); ?></td>
<td><?php echo $stock->mediumnumbers; ?></td></tr>
<tr class="rowEven"><td>L</td><td><?php echo abs($stock->large); ?></td>
<td><?php echo $stock->largenumbers; ?></td></tr>
<tr class="rowOdd"><td>XL</td><td><?php echo abs($stock->xlarge); ?></td>
<td><?php echo $stock->xlargenumbers; ?></td></tr>
<tr class="rowEven"><td>XXL</td><td><?php echo abs($stock->xxlarge); ?></td>
<td><?php echo $stock->xxlargenumbers; ?></td></tr>
</table>

<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowOdd"><td>Comment</td><td><?php echo $stock->comments; ?></td></tr>
</table>

<?php echo $edit_col; ?>

<?php 
  }
}
}  
?>

<?php 
if ($order_artworks) {
foreach($order_artworks->result() as $artwork) {
  $edit_col = sprintf('<a href="%s/customerorderitems/form_order/%d/%s/%d/%d" title="Client Order Artwork - Edit"><img src="%s/images/buttons/small_edit.gif" /></a>'
  ,WEB_CONTEXT,$clientid,$orderid,$stocks_edit,$artwork->artworkid,WEB_CONTEXT);
  if ($artwork->artworkid == $artworks_edit && $artwork->artworksource != 'Canuckstuff') {
?>
<!-- update artwork -->
<?php echo form_open_multipart('customerorderitems/update_artwork/'.$clientid.'/'.$orderid.'/'.$stocks_edit.'/'.$artworks_edit); ?>

<table class="zebraTable">
<tr class="rowEven"><th colspan="5">Update Artwork</th><tr>
<tr class="rowOdd"><td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_save.gif" /><input type="submit" value="Update Artwork" title="Update Artwork" /></td>
<td>From: Customer</td>
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
        $image = '<img src="'.WEB_CONTEXT.'/artworks/'.$filename.'" height="300px" />';
      } else {
        $image = '<img src="'.WEB_IMAGES_PATH.'/styles/default-front.jpg" height="300px" />';
      }
    }
?>
<!-- read only artwork -->
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
From: <?php echo $order->contact; ?><br/> 
Type: <?php echo $this->codes->select_name_by_value($artwork->artworkstatus, 'CustomerArtworkType'); ?><br/> 
<?php 
}
?>
Uploaded by: <?php echo $artwork->uploadby; ?><br/>
File: <a href="<?php echo WEB_CONTEXT;?>/artworks/<?php echo $artwork->filename; ?>" ><?php echo $artwork->filename; ?></a><br/>
Comment: <?php echo $artwork->comment; ?>
</td><td width="300">
<?php echo $image; ?>
</td></tr> 
</table>

<?php echo $edit_col; ?>

<?php 
  }
}
}  
?>

<!-- add new item -->
<table class="zebraTable">
<tr class="rowEven"><th colspan="2">Add Item</th><tr>
<?php 
$select_item_col = sprintf('<a href="%s/customerorderitems/form_add_item/%d/%s/%d/%d" title="Select Item">Select/Add item to this order</a>'
,WEB_CONTEXT,$clientid,$orderid,$stocks_edit,$artworks_edit,WEB_CONTEXT);
?>

<tr class="rowOdd"><td width="180">Item</td><td><?php echo form_dropdown('itemcode', $items_list, $itemcode, $items_options); ?>&nbsp;&nbsp;<?php echo $select_item_col; ?>
</td></tr>

<?php if(isset($itemcode) && $itemcode != '') {?>

<?php echo form_open('customerorderitems/select_style/'.$clientid.'/'.$orderid.'/'.$stocks_edit.'/'.$artworks_edit.'/'.$itemcode); ?>
<tr class="rowEven"><td>Style</td><td>

<?php echo form_dropdown('styleid', $styles_list, $styleid, $styles_options); ?>&nbsp;(Select the colour for this item)</td></tr>
<?php echo form_close(); ?>

<?php }?>

<?php if(isset($styleid) && $styleid != '') {

  $style = $this->styles->select_style_by_id($styleid);
  
?>

<?php echo form_open('customerorderitems/insert_stock/'.$clientid.'/'.$orderid.'/'.$stocks_edit.'/'.$artworks_edit.'/'.$itemcode.'/'.$styleid); ?>

<tr class="rowOdd"><td>Comment</td><td colspan="3">
<input type="text" name="description" value="" size="120" maxlength="200"></td></tr>

</table>

<table class="zebraTable" style="width:100%;border=1px">

<tr class="rowEven"><th colspan="2">Add Front Design</th><tr>
<tr class="rowOdd"><td>
<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowOdd"><td width="180">Logo Position</td><td><?php echo form_dropdown('frontlogopos', $frontlogopos_list, $frontlogopos, $frontlogopos_options); ?>(Select the position for team name or logo)</td></tr>
<tr class="rowEven"><td>Logo Team Name</td><td><input type="text" name="frontlogoname" value="" size="40" maxlength="50"></td></tr>
<tr class="rowOdd"><td>Logo Top Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('frontlogocolor', $artworkcolour_list, $frontlogocolor, $artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Logo Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('frontlogotrimcolor', $artworkcolour_list, $frontlogotrimcolor, $artworkcolour_options); ?></td></tr>
<tr class="rowOdd"><td>Number Position</td><td><?php echo form_dropdown('frontnumpos', $frontnumpos_list, $frontnumpos, $frontnumpos_options); ?>(Select the position for numbers)</td></tr>
<tr class="rowEven"><td>Number Size</td><td>
<?php foreach ($numsize_list as $key => $value) {?>
<input type="radio" name="frontnumsize" title="<?php echo $value;?>" value="<?php echo $key;?>"><?php echo $value;?>&nbsp;&nbsp;&nbsp;&nbsp;
<?php } ?>
</td></tr>
<tr class="rowOdd"><td>Number Top Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('frontnumcolor', $artworkcolour_list, $frontnumcolor, $artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Number Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('frontnumtrimcolor', $artworkcolour_list, $frontnumtrimcolor, $artworkcolour_options); ?></td></tr>
</table></td>
<td width="200">
<?php 
if (isset($style->frontimage) && $style->frontimage != '') {
?>  
<img src="<?php echo WEB_UPLOADS_PATH; ?>/<?php echo $style->frontimage; ?>" width="200" >
<?php
} else {
?>
<img src="<?php echo WEB_IMAGES_PATH; ?>/styles/default-front.jpg" width="200" >
<?php 
}
?>

</td>
</tr>

</table>

<table class="zebraTable" style="width:100%;border=1px">

<tr class="rowEven"><th colspan="2">Add Back Design</th><tr>
<tr class="rowOdd"><td>
<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowOdd"><td width="180">Logo Position</td><td><?php echo form_dropdown('rearlogopos', $rearlogopos_list, $rearlogopos, $rearlogopos_options); ?>(Individual name position: Center)</td></tr>
<tr class="rowEven"><td>Logo Team Name</td><td><input type="text" name="rearlogoname" value="" size="40" maxlength="50"></td></tr>
<tr class="rowOdd"><td>Logo Top Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('rearlogocolor', $artworkcolour_list, $rearlogocolor, $artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Logo Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('rearlogotrimcolor', $artworkcolour_list, $rearlogotrimcolor, $artworkcolour_options); ?></td></tr>
<tr class="rowOdd"><td>Number Position</td><td><?php echo form_dropdown('rearnumpos', $rearnumpos_list, $rearnumpos, $rearnumpos_options); ?>(Select the position for numbers)</td></tr>
<tr class="rowEven"><td>Number Size</td><td>
<?php foreach ($numsize_list as $key => $value) {?>
<input type="radio" name="rearnumsize" title="<?php echo $value;?>" value="<?php echo $key;?>"><?php echo $value;?>&nbsp;&nbsp;&nbsp;&nbsp;
<?php } ?>
</td></tr>
<tr class="rowOdd"><td>Number Top Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('rearnumcolor', $artworkcolour_list, $rearnumcolor, $artworkcolour_options); ?></td></tr>
<tr class="rowEven"><td>Number Bottom Color</td>
<td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_colour.gif" /><?php echo form_dropdown('rearnumtrimcolor', $artworkcolour_list, $rearnumtrimcolor, $artworkcolour_options); ?></td></tr>
</table></td>
<td width="200">
<?php 
if (isset($style->frontrear) && $style->rearimage != '') {
?>  
<img src="<?php echo WEB_UPLOADS_PATH; ?>/<?php echo $style->rearimage; ?>" width="200" >
<?php
} else {
?>
<img src="<?php echo WEB_IMAGES_PATH; ?>/styles/default-rear.jpg" width="200" >
<?php 
}
?>

</td>
</tr>

</table>

<table class="zebraTable" style="width:100%;border=1px">

<tr class="rowEven"><th colspan="2">Add Shorts Design</th><tr>
<tr class="rowOdd"><td>
<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowOdd"><td width="180">Logo Position</td><td><?php echo form_dropdown('shortlogopos', $shortlogopos_list, $shortlogopos, $shortlogopos_options); ?>(Select the position for team name or logo)</td></tr>
<tr class="rowEven"><td>Number Position</td><td><?php echo form_dropdown('shortnumpos', $shortnumpos_list, $shortnumpos, $shortnumpos_options); ?>(Select the position for numbers)</td></tr>
</table></td>
<td width="200">
<?php 
if (isset($style->sideimage) && $style->sideimage != '') {
?>  
<img src="<?php echo WEB_UPLOADS_PATH; ?>/<?php echo $style->sideimage; ?>" width="200" >
<?php
} else {
?>
<img src="<?php echo WEB_IMAGES_PATH; ?>/styles/default-short.jpg" width="200" >
<?php 
}
?>
</td>
</tr></table>

<table class="zebraTable" style="width:100%;border=1px">
<tr class="rowEven"><th>Size</th><th>Add Qty</th><th>Add Numbers</th></tr>
<tr class="rowOdd"><td>XS</td><td><input type="text" name="xsmall" size="10" maxlength="10" /></td><td><input type="text" name="xsmallnumbers" size="80" maxlength="250" /></td></tr>
<tr class="rowEven"><td>S</td><td><input type="text" name="small" size="10" maxlength="10" /></td><td><input type="text" name="smallnumbers" size="80" maxlength="250" /></td></tr>
<tr class="rowOdd"><td>M</td><td><input type="text" name="medium" size="10" maxlength="10" /></td><td><input type="text" name="mediumnumbers" size="80" maxlength="250" /></td></tr>
<tr class="rowEven"><td>L</td><td><input type="text" name="large" size="10" maxlength="10" /></td><td><input type="text" name="largenumbers" size="80" maxlength="250" /></td></tr>
<tr class="rowOdd"><td>XL</td><td><input type="text" name="xlarge" size="10" maxlength="10" /></td><td><input type="text" name="xlargenumbers" size="80" maxlength="250" /></td></tr>
<tr class="rowEven"><td>XXL</td><td><input type="text" name="xxlarge" size="10" maxlength="10" /></td><td><input type="text" name="xxlargenumbers" size="80" maxlength="250" /></td></tr>
</table>

<table class="zebraTable" style="width:100%;border=1px">

<tr class="rowOdd"><td><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_save.gif" />&nbsp;<input type="submit" value="Add New Order Item" title="Add New Order Item" /></td></tr>
<?php echo form_close(); ?>

<?php }?>

</table>

<!-- add new artwork -->
<?php echo form_open_multipart('customerorderitems/insert_artwork/'.$clientid.'/'.$orderid.'/'.$stocks_edit.'/'.$artworks_edit); ?>

<table class="zebraTable">
<tr class="rowEven"><th colspan="4">Add Artwork</th><tr>
<tr class="rowOdd">
<td>From: </td><td><?php echo $order->contact; ?></td>
<td>Type: </td><td><?php echo form_dropdown('artworkstatus', $artworkstatus_list, '', $artworkstatus_options); ?></td></tr>
<tr class="rowOdd">
<td>File(max 2m): </td><td><input type="file" name="filename" size="20" /></td>
<td>Comment: </td><td><input type="text" name="comment" value="" size="40" maxlength="100" /></td></tr>
<tr class="rowOdd">
<td colspan="4"><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_save.gif" />&nbsp;<input type="submit" value="Add Artwork" title="Add Artwork" /></td></tr> 
</table>

<?php echo form_close(); ?>

