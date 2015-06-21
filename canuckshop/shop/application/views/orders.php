<?php echo validation_errors(); ?>
<?php echo form_open('uniformorders/search/0/0/'); ?>
<input type="text" name="orders_what" value="<?php echo set_value('orders_what', $orders_what); ?>" size="20" maxlength="50" />
<?php echo form_dropdown('select_orders_status', $orders_status_list, $orders_status, $orders_status_options); ?>
<?php echo form_dropdown('select_query_sort', $query_sort_list, $query_sort, $query_sort_options); ?>

From Date:<input type="text" name="orders_fromdate" id="orders_fromdate" value="<?php echo set_value('orders_fromdate', $orders_fromdate); ?>" size="12" maxlength="12">
To Date:<input type="text" name="orders_todate" id="orders_todate" value="<?php echo set_value('orders_todate', $orders_todate); ?>" size="12" maxlength="12">

<input type="submit" value="Search Orders" title="Search Orders" />&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo sprintf('<a href="%s/uniformclients/" title="Client Order - New" ><img src="%s/images/icons/icon_order.jpg" height="20px" /></a>',WEB_CONTEXT,WEB_CONTEXT); ?>
<?php echo form_close(); ?><br>

<?php echo form_open('uniformorders/update/'.$orders_start.'/'.$orders_edit); ?>
<?php if (isset($orders_table)) echo $orders_table; ?>
<?php echo form_close(); ?>

<?php echo $this->pagination->create_links(); ?>

<b>Total Orders: <?php echo $totalrow->ordertotal; ?>&nbsp;&nbsp;Total Items: <?php echo $totalrow->stocktotal; ?></b>
