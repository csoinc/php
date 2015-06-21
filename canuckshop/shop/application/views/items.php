<?php echo validation_errors(); ?>
<?php echo form_open('uniforms/search/'.$items_start); ?>
<input type="text" name="items_what" value="<?php echo set_value('items_what', $items_what); ?>" size="20" maxlength="50" />
<input type="submit" value="Search Items" title="Search Items" />
<?php echo form_close(); ?>

<?php echo form_open('uniforms/update/'.$items_start.'/'.$items_edit); ?>
<?php if (isset($items_table)) echo $items_table; ?>
<?php echo form_close(); ?>

<?php echo $this->pagination->create_links(); ?>

<?php echo form_open('uniforms/insert/'.$items_start); ?>
<input type="hidden" name="items_start" id="items_start" value="<?php echo set_value('items_start', $items_start); ?>" size="5" />
Item Code: 
<input type="text" name="itemcode" value="<?php echo set_value('itemcode', ''); ?>" size="8" maxlength="12" />
&nbsp;&nbsp;Item Name: 
<input type="text" name="itemname" value="<?php echo set_value('itemname', ''); ?>" size="40" maxlength="50" />
&nbsp;<img src="<?php echo WEB_CONTEXT; ?>/images/icons/icon_save.gif" /><input type="submit" value="Add New Item" title="Add New Item" />
<?php echo form_close(); ?>
