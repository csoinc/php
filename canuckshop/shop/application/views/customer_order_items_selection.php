<b>Order #:&nbsp;</b><?php echo $order->orderid; ?>

<?php if (isset($select_item_table)) echo $select_item_table; ?>

<?php echo $this->pagination->create_links(); ?>
