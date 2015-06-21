<?php echo validation_errors(); ?>

<?php echo form_open_multipart('uniformstyles/update/'.$items_start.'/'.$itemcode.'/'.$styles_edit); ?>
<?php if (isset($styles_table)) echo $styles_table; ?>
<?php echo form_close(); ?>

<table class="zebraTable" style="width:1000">
<tr class="rowEven">
<td>Select colour from the dropdown box and click Add New Style button. 
</td>
<td>Input colour and click Add New Colour button for insert into the dropdown box.
</td></tr>
<tr class="rowOdd">
<td>
<?php echo form_open('uniformstyles/insert_style/'.$items_start.'/'.$itemcode); ?>
<?php echo form_dropdown('colour', $colours, '', $colours_options); ?>
<img src="<?php echo WEB_CONTEXT; ?>/images/icons/icon_save.gif" /><input type="submit" value="Add New Style" title="Add New Style" />
<?php echo form_close(); ?>
</td>
<td>
<?php echo form_open('uniformstyles/insert_color/'.$items_start.'/'.$itemcode); ?>
<input type="text" name="colour" value="<?php echo set_value('colour', ''); ?>" size="30" maxlength="50" />
<img src="<?php echo WEB_CONTEXT; ?>/images/icons/icon_save.gif" /><input type="submit" value="Add New Colour" title="Add New Colour" />
<?php echo form_close(); ?>
</td></tr>
<tr class="rowEven">
<td>
<?php echo sprintf('<a href="%s/uniforms/select/%d" title="Back"><img src="%s/images/buttons/button_back.gif" /></a>', WEB_CONTEXT, $items_start, WEB_CONTEXT); ?>
</td><td></td>
</tr>
</tbody>
</table>







