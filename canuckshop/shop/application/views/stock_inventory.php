<?php echo validation_errors(); ?>

<?php echo form_open('uniformstocks/insert/'.$items_start.'/'.$itemcode.'/'.$styleid); ?>
<?php if (isset($stocks_table)) echo $stocks_table; ?>
<?php echo form_close(); ?>

<?php echo sprintf('<a href="%s/uniformstocks/select/%d/%s/%d" title="Back"><img src="%s/images/buttons/button_back.gif" /></a>', WEB_CONTEXT, $items_start, $itemcode, $styleid, WEB_CONTEXT); ?>







