<div id="updateOrderStatus" class='noprint'>
<?php echo sprintf('<a href="%s/uniformorders/select/%d" title="Back"><img src="%s/images/buttons/button_back.gif" /></a>',WEB_CONTEXT,$orders_start,WEB_CONTEXT); ?><br>

<?php echo validation_errors(); ?>
<?php echo form_open('uniformorderitems/update_status/'.$orders_start.'/'.$order->orderid); ?>
<b>Order Status:&nbsp;</b><?php echo $order->status; ?>&nbsp;(Rev <?php echo $order->revision; ?>)&nbsp;&nbsp;
Created by <?php echo $order->createdby; ?>&nbsp;and&nbsp;Updated by&nbsp;<?php echo $order->updby; ?>&nbsp;at&nbsp;<?php echo $order->lastupddate; ?><br>

<b>New Order Status: </b><?php echo form_dropdown('orderstatus', $orderstatus_list, $order->orderstatus, $orderstatus_options); ?>&nbsp;&nbsp;
<b>Comment: </b><input type="text" name="updcomment" value="<?php echo set_value('updcomment', $order->updcomment); ?>" size="25" maxlength="100" />&nbsp;&nbsp; 
<img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_save.gif" /><input type="submit" value="Update Order Status" title="Update Order Status" />&nbsp;&nbsp;
<a href="javascript:onClick=printPage()" title="Order - Print"><img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_printer.gif" height="20px" /></a>
<?php echo form_close(); ?>
<br>
<!-- list feedbacks -->
<?php 
if ($feedbacks) {
?>
<div class="listbox_center">
<?php   
foreach($feedbacks->result() as $feedback) {
?>
<font color=red><?php echo $feedback->feedback; ?><br></font>
&nbsp;&nbsp;&nbsp;&nbsp;-- Created by&nbsp;<?php echo $feedback->createdby; ?>&nbsp;on&nbsp;<?php echo $feedback->createddate; ?><br>
<?php 
}
?>
</div>
<?php 
}  
?>

<!-- add feedback -->
<?php echo form_open('uniformorderitems/insert_feedback/'.$orders_start.'/'.$order->orderid); ?>

<b>Feebback: </b><input type="text" name="feedback" value="<?php echo set_value('feedback', ''); ?>" size="50" maxlength="250" />&nbsp;&nbsp;
<img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_save.gif" /><input type="submit" value="Add Feedback" title="Add Feedback" />&nbsp;&nbsp;

<?php echo form_close(); ?>

</div>
<!-- list contact -->
<!-- <b>Order #:&nbsp;</b><?php echo $order->orderid; ?>-->
<table class="printInfoTable">
<tr class="rowOdd"><th colspan="4">Customer Information</th><tr>
<tr class="rowEven"><td>Name</td><td><?php echo $order->contact; ?></td><td>Client #</td><td><?php echo $order->clientid; ?></td></tr>
<tr class="rowOdd"><td>Telephone</td><td><?php echo $order->telephone; ?></td><td>Other Phone</td><td><?php echo $order->cellphone; ?></td></tr>
<tr class="rowEven"><td>Email</td><td><?php echo $order->email; ?></td><td>Date In</td><td><?php echo substr($order->orderdate,0,10); ?></td></tr>
<tr class="rowOdd"><td>School/Team</td><td><?php echo $order->name; ?></td><td>Date Needed</td><td><?php echo substr($order->requireddate,0,10); ?></td></tr>
<tr class="rowEven"><td>Payment</td><td><?php echo $order->payment; ?></td><td>Expire Date</td><td><?php echo $order->expdate; ?></td></tr>
<tr class="rowOdd"><td>Address</td><td colspan="3"><?php echo $order->address; ?>&nbsp;<?php echo $order->zipcode; ?></td></tr>
<tr class="rowEven"><td>Shipping Addr</td><td colspan="3"><?php echo $order->shippingaddr; ?>&nbsp;<?php echo $order->shippingzip; ?></td></tr>
<tr class="rowOdd"><td>Comment</td><td colspan="3"><?php echo $order->comments; ?>&nbsp;<?php echo $order->updcomment; ?></td></tr>
</table>
<div style="page-break-after:always;">
<br/><br/>
</div>

<!-- list order items start -->
<?php
if ($stocks) {   
foreach($stocks->result() as $stock) {
  $style = $this->styles->select_style_by_id($stock->styleid);
  if ($style) {
    $colorname = $style->colorname;
  } else {
    $colorname = '';
  }
?>

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

<div style="page-break-after:always; font-size: large;">
<?php echo $order->contact; ?>&nbsp;&nbsp;<?php echo $order->telephone; ?>&nbsp;&nbsp;<?php echo $order->email; ?>&nbsp;&nbsp;<?php echo substr($order->requireddate,0,10); ?>&nbsp;&nbsp;
<?php echo sprintf('<a href="javascript:onClick=printPage()" title="Order - Print"><img src="%s/images/icons/icon_print.gif" /></a>',WEB_CONTEXT); ?>&nbsp;&nbsp;
<br>
<br>
</div>
<!-- page break  -->
<?php 
}
}
?>
        
<!-- list order items end -->

<div id="artwork" class='noprint'>

<table class="printInfoTable">

<tr><th colspan="2">Artworks</th></tr>

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
      $image = '';
    }
  }
?>
<tr><td><?php echo $image; ?></td>
<td>File: <a href="<?php echo WEB_CONTEXT;?>/artworks/<?php echo $artwork->filename; ?>" ><?php echo $artwork->filename; ?></a><br>
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
Comment: <?php echo $artwork->comment; ?>
</td></tr>
<?php 
}
}  
?>
</table>

<?php echo form_open_multipart('uniformorderitems/insert_artwork/'.$orders_start.'/'.$order->orderid); ?>
File(max 2m): <input type="file" name="filename" size="20" />&nbsp;
From: <?php echo form_dropdown('artworksource', $artworksource_list, '', $artworksource_options); ?>&nbsp;
Type: <?php echo form_dropdown('artworkstatus', $artworkstatus_list, '', $artworkstatus_options); ?>&nbsp;<br/>
Comment: <input type="text" name="comment" value="" size="25" maxlength="100" />&nbsp;<br/>
<img src="<?php echo WEB_CONTEXT;?>/images/icons/icon_save.gif" /><input type="submit" value="Add New Artwork" title="Add New Artwork" /><br>
<?php echo form_close(); ?>

</div>
