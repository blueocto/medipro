<?php
//  Get the order
require_once($this->plugin->get_path('/widgets/checkout/AdministrateWidgetCheckoutOrder.php'));
$order = new AdministrateWidgetCheckoutOrder($this->plugin, $_REQUEST[$this->plugin->add_namespace(array('order', 'id'))]);
?>

<div id="<?= $this->plugin->add_namespace('order', '-'); ?>">

	<h3><?php _e('Order #', 'administrate'); ?> <?= $order->get_id(); ?></h3>
	
	<a href="?page=<?= $_GET['page']; ?>"><?php _e('Go Back', 'administrate'); ?></a>
	
	<h4><?php _e('Order Details', 'administrate'); ?></h4>
	<dl>
		<dt><?php _e('Status:', 'administrate'); ?></dt>
			<dd><?= $orderStatuses[$order->get_status()]; ?></dd>
		<dt><?php _e('Time Started:', 'administrate'); ?></dt>
			<dd><?= $this->plugin->format_datetime($order->get_time_started()); ?></dd>
		<?php if ($order->get_time_completed() > 0) { ?>
			<dt><?php _e('Time Completed:', 'administrate'); ?></dt>
				<dd><?= $this->plugin->format_datetime($order->get_time_completed()); ?></dd>
		<?php } else { ?>
			<dt><?php _e('Max Step Complete:', 'administrate'); ?></dt>
				<dd><?= $order->get_max_step(); ?></dd>
		<?php } ?>
		<dt><?php _e('# Attendees:', 'administrate'); ?></dt>
			<dd><?= $order->get_num_attendees(); ?></dd>
		<dt><?php _e('Currency:', 'administrate'); ?></dt>
			<dd><?= $order->get_currency(); ?></dd>
		<dt><?php _e('Payment Type:', 'administrate'); ?></dt>
			<dd><?= $paymentTypes[$order->get_payment_type()]; ?></dd>
		<dt><?php _e('API Invoice ID:', 'administrate'); ?></dt>
			<dd><?= $order->get_api_invoice_id(); ?></dd>
		<?php if ($order->get_payment_type() == 'C') { ?>
			<dt><?php _e('Transaction ID:', 'administrate'); ?></dt>
				<dd>
					<?php if (strlen($order->get_processor_transaction_id()) > 0) { ?>
						<?= $order->get_processor_transaction_id(); ?>
					<?php } else { ?>
						<?php _e('n/a', 'administrate'); ?>
					<?php } ?>
				</dd>
		<?php } ?>
		<dt><?php _e('Session ID:', 'administrate'); ?></dt>
			<dd><?= $order->get_session_id(); ?></dd>
	</dl>
	
	<h4><?php _e('Event Details', 'administrate'); ?></h4>
	<dl>
		<dt><?php _e('Course Code:', 'administrate'); ?></dt>
			<dd><?= $order->get_course_code(); ?></dd>
		<dt><?php _e('Course Title:', 'administrate'); ?></dt>
			<dd><?= $order->get_course_title(); ?></dd>
		<dt><?php _e('Event Dates:', 'administrate'); ?></dt>
			<dd><?= $this->plugin->format_date_span($order->get_event_dates()); ?></dd>
	</dl>
	
	<h4><?php _e('Buyer Details', 'administrate'); ?></h4>
	<dl>
		<dt><?php _e('First Name:', 'administrate'); ?></dt>
			<dd><?= $order->get_buyer_first_name(); ?></dd>
		<dt><?php _e('Last Name:', 'administrate'); ?></dt>
			<dd><?= $order->get_buyer_last_name(); ?></dd>
		<dt><?php _e('Company:', 'administrate'); ?></dt>
			<dd><?= $order->get_buyer_company(); ?></dd>
		<dt><?php _e('Email:', 'administrate'); ?></dt>
			<dd><a href="mailto: <?= $order->get_buyer_email(); ?>"><?= $order->get_buyer_email(); ?></a></dd>
		<dt><?php _e('Phone:', 'administrate'); ?></dt>
			<dd><?= $order->get_buyer_phone(); ?></dd>
	</dl>
	
	<h4><?php _e('Invoice Address', 'administrate'); ?></h4>
	<dl>
		<dt><?php _e('Address:', 'administrate'); ?></dt>
			<dd><?= $order->get_invoice_street(); ?></dd>
		<dt><?php _e('City:', 'administrate'); ?></dt>
			<dd><?= $order->get_invoice_city(); ?></dd>
		<dt><?php _e('County/State:', 'administrate'); ?></dt>
			<dd><?= $order->get_invoice_territory(); ?></dd>
		<dt><?php _e('Postal Code:', 'administrate'); ?></dt>
			<dd><?= $order->get_invoice_postal_code(); ?></dd>
		<dt><?php _e('Country:', 'administrate'); ?></dt>
			<dd><?= $order->get_invoice_country(); ?></dd>
	</dl>
	
	<?php $i = 1; ?>
	<?php foreach ($order->get_attendees() as $attendee) { ?>
		<h4><?php _e('Attendee #', 'administrate'); ?><?= $i; ?></h4>
		<dl>
			<dt><?php _e('First Name:', 'administrate'); ?></dt>
				<dd><?= $attendee['first_name']; ?></dd>
			<dt><?php _e('Last Name:', 'administrate'); ?></dt>
				<dd><?= $attendee['last_name']; ?></dd>
			<dt><?php _e('Email:', 'administrate'); ?></dt>
				<dd><a href="mailto: <?= $attendee['email']; ?>"><?= $attendee['email']; ?></a></dd>
		</dl>
		<?php ++$i; ?>
	<?php } ?>
	
	<h4><?php _e('User Action Log', 'administrate'); ?></h4>
	<dl>
		<?php foreach ($order->get_logs() as $log) { ?>
			<dt><?= $this->plugin->format_datetime($log->get_time()); ?></dt>
				<dd><?= $log->get_message(); ?></dd>
		<?php } ?>
	</dl>

</div>