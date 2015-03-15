<?php extract($params); ?>

<h2><?php _e('Thank You - your order has been placed!', 'administrate'); ?></h2>
<p><?php printf(__('Your order number is <strong>#%d</strong>. Please reference this order number if you contact us with an enquiry.', 'administrate'), $order_num); ?></p>
<?php if ($payment_type == 'C') { ?>
	<?php if ($payment_processor == 'PayPal') { ?>
		<p><?php _e('You should receive a payment confirmation from PayPal&trade; shortly.', 'administrate'); ?></p>
	<?php } else if ($payment_processor == 'SagePay') { ?>
		<p><?php _e('You should receive a payment confirmation from Sage Pay shortly.', 'administrate'); ?></p>
	<?php } ?>
<?php } ?>
<p><?php _e('We will be in touch regarding your course in due time.  In the meantime, if you have any enquiries, please do not hesitate to contact us.', 'administrate'); ?></p>

<?= $this->plugin->get_option('conversion_tracking', 'checkout'); ?>