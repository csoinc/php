<?php echo sprintf('<a href="%s/uniformclients/select/%d" title="Back"><img src="%s/images/buttons/button_back.gif" /></a>',WEB_CONTEXT,$clients_start,WEB_CONTEXT); ?>&nbsp;&nbsp;
<?php echo sprintf('<a href="%s/clientorders/form_confirm_client/%d/%d" title="Client Order - New"><img src="%s/images/icons/icon_order.jpg" /></a>',WEB_CONTEXT,$clients_start,$clientid,WEB_CONTEXT); ?>

<?php if (isset($client_orders_table)) echo $client_orders_table; ?>
