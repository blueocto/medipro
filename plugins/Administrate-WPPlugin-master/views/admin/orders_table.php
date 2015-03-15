<?

$_RESULTS_PER_PAGE = 100;

//  Set time increments
$timeIncrements = array(
	1	=>	__('1 day', 'administrate'),
	3	=>	__('3 days', 'administrate'),
	7	=>	__('week', 'administrate'),
	14	=>	__('2 weeks', 'administrate'),
	31	=>	__('month', 'administrate'),
	92	=>	__('3 months', 'administrate'),
	182	=>	__('6 months', 'administrate'),
	365	=>	__('year', 'administrate'),
);

// Array to build to generate 'where' part of get_orders call
$orders_where_array = array();

// Saved status
$savedStatus = '';
$status_field = $this->plugin->add_namespace(array('order', 'status'));
if (isset($_POST[$status_field]) && $_POST[$status_field] != '') {
	$savedStatus = $_POST[$status_field];
	$orders_where_array['order_status'] = $savedStatus;
}

// Payment types
$savedPayment = '';
$payment_field = $this->plugin->add_namespace(array('order', 'payment'));
if(isset($_POST[$payment_field]) && $_POST[$payment_field] != '') {
	$savedPayment = $_POST[$payment_field];
	$orders_where_array['order_payment'] = $savedPayment;
}

// Set saved event ID
$savedEvent = 0;
$event_field = $this->plugin->add_namespace(array('order', 'event'));
if(isset($_POST[$event_field]) && $_POST[$event_field] != '') {
	$savedEvent = $_POST[$event_field];
	$orders_where_array['order_event_id'] = $savedEvent;
}

// ** PAGING ** //

// First, fetch page # from post request if available.
$currentPage = 0;
$page_field = $this->plugin->add_namespace(array('order', 'page'));
if (isset($_POST[$page_field]) && $_POST[$page_field] != '') {
	$currentPage = $_POST[$page_field];
}

// Calculate the number of results we will have in this query.
$results_this_query = $this->plugin->count_orders(
	$orders_where_array
);

// From that work out the number of pages that we need.
$pages_needed = ceil($results_this_query / $_RESULTS_PER_PAGE);

/*
 * If we've filtered further on the last pageload,
 * we may need to drop current page to the max page
 */
if($currentPage > $pages_needed) {

	/*
	 * At this point, currentPage is 0 based,
	 * so subtract 1 from pages_needed.
	 */
	$currentPage = $pages_needed - 1;
}

/*
 * Finally load the orders, using the $where we
 * generated, the page number we calculated, and
 * let it know the total results per page.
 */
$orders = $this->plugin->get_orders(
	$orders_where_array,
	($currentPage) * $_RESULTS_PER_PAGE,
	$_RESULTS_PER_PAGE
);

$ids = array();
foreach($orders as $order) {
	$ids[] = $order->get_event_id();
}

// Build an array of pages for the dropdown.
$pages_array = array();
for($i = 1; $i <= $pages_needed; $i++) {
	$pages_array[$i - 1] = 'page ' . $i;
}

/*
 * If we're on the last page, calculate the 'max'
 * result as the actual highest result, otherwise
 * calculate it based on page.
 */
$last_page_highest = (($currentPage + 1) * $_RESULTS_PER_PAGE);
if($results_this_query < $last_page_highest) {
	$last_on_page = $results_this_query;
} else {
	$last_on_page = $last_page_highest;
}

//  Loop through orders and add unique events and courses
$events = array();
$courses = array();

/*
 * $this->plugin->get_ordered_event_ids() Returns in following format:
 * array(x) {
 *   array(1) {
 *     "order_event_id" =>  "5794"
 *   }
 * ...
 */
foreach($ids as $event_id) {
	$events[$event_id] = $this->plugin->make_api_call('get_event', $event_id);

	$courseCode = $events[$event_id]->get_course_code();
	if(!isset($courses[$courseCode])) {
		$courses[$courseCode] = $this->plugin->make_api_call('get_course_by_code', $courseCode);
	}
}

// Sort these
ksort($events);
ksort($courses);

?>

<h3><?php _e('Order Logs', 'administrate'); ?></h3>

<div id="<?= $this->plugin->add_namespace(array('order', 'logs'), '-'); ?>" class="<?= $this->plugin->add_namespace(array('sort', 'filter', 'table'), '-'); ?>">

	<form method="post" action="">
		<p>
			<label>
				<?php _e('Showing ', 'administrate'); ?>
				<?php _e(($currentPage * $_RESULTS_PER_PAGE) + 1) ?>
				<?php _e(' to ', 'administrate'); ?>
				<?php _e($last_on_page); ?>
				<?php _e(' out of ', 'administrate'); ?>
				<?php _e($results_this_query); ?>.
				<?php _e('Page', 'administrate'); ?>
				<select name="<?= $this->plugin->add_namespace(array('order', 'page')); ?>" id="<?= $this->plugin->add_namespace(array('order', 'page'), '-'); ?>">
					<?php foreach ($pages_array as $page=>$label) { ?>
						<option
							value="<?= $page; ?>"
							<?php if ($page == $currentPage) { ?>
								selected="selected"
							<?php } ?>
							><?= $label; ?></option>
					<?php } ?>
				</select>
				<?php _e('of', 'administrate'); ?> <?php _e($pages_needed); ?>
			</label>
			<br><br>
			<label>
				<?php _e('Include', 'administrate'); ?>
				<select name="<?= $this->plugin->add_namespace(array('order', 'status')); ?>" id="<?= $this->plugin->add_namespace(array('order', 'status'), '-'); ?>">
					<option value=""><?php _e('all', 'administrate'); ?></option>
					<?php foreach ($orderStatuses as $code=>$label) { ?>
						<option
							value="<?= $code; ?>"
							<?php if ($code == $savedStatus) { ?>
								selected="selected"
							<?php } ?>
							><?= $label; ?></option>
					<?php } ?>
				</select>
				<?php _e('orders', 'administrate'); ?>
			</label>
			<label>
				<?php _e('for ', 'administrate'); ?>
				<select name="<?= $this->plugin->add_namespace(array('order', 'event')); ?>" id="<?= $this->plugin->add_namespace(array('order', 'event'), '-'); ?>">
					<option value=''><?php _e('any event', 'administrate'); ?></option>
					<?php foreach ($events as $eventId=>$event) { ?>
						<option
							value="<?= $eventId; ?>"
							<?php if ($eventId == $savedEvent) { ?>
								selected="selected"
							<?php } ?>
							><?= $courses[$event->get_course_code()]->get_title(); ?> (<?= $this->plugin->format_date_span($event->get_dates()); ?>)</option>
					<?php } ?>
				</select>
			</label>
			<label>
				<?php _e('with ', 'administrate'); ?>
				<select name="<?= $this->plugin->add_namespace(array('order', 'payment')); ?>" id="<?= $this->plugin->add_namespace(array('order', 'payment'), '-'); ?>">
					<option value=""><?php _e('any', 'administrate'); ?></option>
					<?php foreach ($paymentTypes as $code=>$label) { ?>
						<option
							value="<?= $code; ?>"
							<?php if ($code == $savedPayment) { ?>
								selected="selected"
							<?php } ?>
							><?= $label; ?></option>
					<?php } ?>
				</select>
				<?php _e('payment.', 'administrate'); ?>
			</label>
			<input type="submit" value="<?php _e('Go', 'administrate'); ?>" class="<?= $this->plugin->add_namespace('btn', '-'); ?>">
		</p>
	</form>

	<table cellspacing="0">
		<thead>
		<tr>
			<th scope="col"><?php _e('#', 'administrate'); ?></th>
			<th scope="col"><?php _e('Status', 'administrate'); ?></th>
			<th scope="col"><?php _e('Buyer Name', 'administrate'); ?></th>
			<th scope="col"><?php _e('# Attendees', 'administrate'); ?></th>
			<th scope="col"><?php _e('Payment Type', 'administrate'); ?></th>
			<th scope="col"><?php _e('Time Started', 'administrate'); ?></th>
			<th scope="col"><?php _e('Time Completed', 'administrate'); ?></th>
			<th scope="col"><?php _e('Invoice ID', 'administrate'); ?></th>
			<th scope="col"><?php _e('Course Name', 'administrate'); ?></th>
			<th scope="col"><?php _e('Event Date(s)', 'administrate'); ?></th>
			<th scope="col"><?php _e('View Details', 'administrate'); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($orders as $order) { ?>
			<?php
			$dates = $events[$order->get_event_id()]->get_dates();
			$daysAgo = ceil((time() - $order->get_time_started()) / 60 / 60 / 24);
			$buyerFirstName = $order->get_buyer_first_name();
			$buyerLastName = $order->get_buyer_last_name();
			?>
			<tr data-status="<?= $order->get_status(); ?>" data-days-ago="<?= $daysAgo; ?>" data-event="<?= $order->get_event_id(); ?>" data-payment="<?= $order->get_payment_type(); ?>" class="<?= $this->plugin->add_namespace(array('order', 'step', $order->get_max_step()), '-'); ?> <?= $this->plugin->add_namespace(array('order', $orderStatuses[$order->get_status()]), '-'); ?>">
				<td><?= $order->get_id(); ?></td>
				<td class="<?= $this->plugin->add_namespace(array('order', 'status'), '-'); ?>">
					<?= $orderStatuses[$order->get_status()]; ?>
					<?php if ($order->get_max_step() < 5) { ?>
						(<?php _e('step', 'administrate'); ?> <?= $order->get_max_step(); ?>)
					<?php } ?>
				</td>
				<td>
					<?php if (!empty($buyerLastName)) { ?>
						<?= $buyerLastName; ?>,
					<?php } else { ?>
						<?php _e('n/a', 'administrate'); ?>,
					<?php } ?>
					<?php if (!empty($buyerFirstName)) { ?>
						<?= $buyerFirstName; ?>
					<?php } else { ?>
						<?php _e('n/a', 'administrate'); ?>
					<?php } ?>
				</td>
				<td><?= $order->get_num_attendees(); ?></td>
				<td><?= $paymentTypes[$order->get_payment_type()]; ?></td>
				<td data-time-started="<?= $order->get_time_started(); ?>"><?= $this->plugin->format_datetime($order->get_time_started()); ?></td>
				<td data-time-completed="<?= $order->get_time_completed(); ?>">
					<?php if ($order->is_complete()) { ?>
						<?= $this->plugin->format_datetime($order->get_time_completed()); ?>
					<?php } else { ?>
						<?php _e('n/a', 'administrate'); ?>
					<?php } ?>
				</td>
				<td><?= $order->get_api_invoice_id(); ?></td>
				<td><?= $courses[$events[$order->get_event_id()]->get_course_code()]->get_title(); ?></td>
				<td data-event-time="<?= $dates['start']; ?>"><?= $this->plugin->format_date_span($dates); ?></td>
				<td><a href="?page=<?= $_GET['page']; ?>&<?= $this->plugin->add_namespace(array('order', 'id')); ?>=<?= $order->get_id(); ?>"><?php _e('View Details', 'administrate'); ?></a></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>

	<p class="<?= $this->plugin->add_namespace(array('no', 'rows'), '-'); ?>"><?php _e('There were no orders with the selected criteria.', 'administrate'); ?></p>

</div>
