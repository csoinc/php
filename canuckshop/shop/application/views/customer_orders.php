<?php echo sprintf('<a href="%s/customerorders/form_confirm_customer/%d" title="Customer Order - New">
<img src="%s/images/icons/icon_file.gif" height="20px" /></a>',WEB_CONTEXT,$clientid,WEB_CONTEXT); ?>

<?php if (isset($customer_orders_table)) echo $customer_orders_table; ?>
