<?php extract($params); ?>

<?php 
$event = $this->get_order_event(); 
$priceObj = $event->get_price_by_currency($default_currency);

if(is_object($priceObj)) {
	$netPrice = $priceObj->get_display_price('net');
	$grossPrice = $priceObj->get_display_price('gross');
	$taxPrice = $grossPrice - $netPrice;
}

$courseNameClass = $this->add_namespace(array('course', 'name'), '-');
$taxClass = $this->add_namespace(array('tax'), '-');
?>

<?php $this->plugin->display_errors($errors); ?>

<h3><?php _e('Please confirm the following order:', 'administrate'); ?></h3>
<p><?php _e('If you need to change any details, please use the navigation above to return to the section you need to modify.', 'administrate'); ?></p>

<h3><?php _e('Order Summary', 'administrate'); ?></h3>
<table>
	<thead>
		<tr>
			<th class="<?= $courseNameClass; ?>"><?php _e('Course', 'administrate'); ?></th>
			<th>
				<?php _e('Price per Person', 'administrate'); ?>
				<span class="<?= $this->add_namespace(array('price', 'qualifier'), '-'); ?>">
					<?= $default_currency; ?>
				</span>
			</th>
			<th><?php _e('Attendees', 'administrate'); ?></th>
			<th><?php _e('Subtotal', 'administrate'); ?></th>
			<th class="<?= $taxClass; ?>"><?= $tax_label; ?></th>
			<th><?php _e('Total', 'administrate'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="<?= $courseNameClass; ?>"><?= $course; ?></th>
			<td><?= $this->plugin->format_currency($netPrice, $default_currency, $show_currency_indicator, $currency_indicator); ?></td>
			<td><?= $num_attendees; ?></td>
			<td><?= $this->plugin->format_currency(($netPrice * $num_attendees), $default_currency, $show_currency_indicator, $currency_indicator); ?></td>
			<td class="<?= $taxClass; ?>"><?= $this->plugin->format_currency(($taxPrice * $num_attendees), $default_currency, $show_currency_indicator, $currency_indicator); ?></td>
			<td><?= $this->plugin->format_currency(($grossPrice * $num_attendees), $default_currency, $show_currency_indicator, $currency_indicator); ?></td>
		</tr>
	</tbody>
</table>	

<h3><?php _e('Your Details', 'administrate'); ?></h3>
<dl class="<?= $this->plugin->add_namespace(array('field', 'values'), '-'); ?>">
	<?php foreach ($buyer_fields as $field) { ?>
		<?php if (!empty($buyer_values[$field])) { ?>
			<dt><?= $this->plugin->get_data_label('orders', 'order_buyer_details', $field); ?></dt>
				<dd><?= $buyer_values[$field]; ?></dd>
		<?php } ?>
	<?php } ?>
</dl>

<h3><?php _e('Invoice Address', 'administrate'); ?></h3>
<address>
	<span><?= $invoice_values['address']; ?></span>
	<span><?= $invoice_values['city']; ?>, <?= $invoice_values['territory']; ?> <?= $invoice_values['postal_code']; ?></span>
	<span><?= $invoice_values['country']; ?></span>
</address>

<?php for ($i = 1; $i <= $num_attendees; ++$i) { ?> 
	<h3><?php _e('Attendee', 'administrate'); ?> <?= $i; ?></h3>
	<dl class="<?= $this->plugin->add_namespace(array('field', 'values'), '-'); ?>">
		<?php foreach ($attendee_fields as $field) { ?>
			<?php if($field == 'notes' && !$show_notes) { continue; } ?>
			<dt><?= $this->plugin->get_data_label('orders', 'order_attendee_details', $field); ?></dt>
				<dd><?= $attendee_values[$field][$i-1]; ?></dd>
		<?php } ?>
	</dl>
<?php } ?>

<?php if (isset($course_fields) && !empty($course_fields)) { ?>
	<h3><?php _e('Course Information', 'administrate'); ?></h3>
	<?php include($this->plugin->get_path('/views/public/course_fields.php')); ?>
<?php } ?>

<div id="<?= $this->add_namespace('payment', '-'); ?>">
	
	<?php if ($this->get_option('payment_by_invoice') && $this->get_option('payment_by_cc')) { ?>
		<h3><?php _e('Payment Options', 'administrate'); ?></h3>
		<p><?php _e('Please select your method of payment:', 'administrate'); ?></p>
		<div class="<?= $this->add_namespace('accordion', '-'); ?>">
	<?php } else { ?>
		<h3><?php _e('Payment', 'administrate'); ?></h3>
	<?php } ?>
	
	<?php if ($this->get_option('payment_by_invoice')) { ?>
		<?php if ($this->get_option('payment_by_cc')) { ?>
			<h3><label><input type="radio" name="order_payment_type" value="I" id="order-payment-type-invoice"> <?php _e('Pay By Invoice', 'administrate'); ?></label></h3>
		<?php } else { ?>
			<input type="hidden" name="order_payment_type" value="I">
		<?php } ?>
		<div class="<?= $this->add_namespace(array('payment', 'option'), '-'); ?> <?= $this->add_namespace(array('payment', 'option', 'invoice'), '-'); ?>">
			<p><?php _e('To pay by invoice, please click "Place Order" to complete your transaction.', 'administrate'); ?></p>
			<p><?= $this->get_option('invoice_instructions'); ?></p>
			<address><?= nl2br($billing_address); ?></address>
			<p><?php _e('By clicking "Place order", you confirm that you are authorised to place this order and agree to and accept our Terms &amp; Conditions.', 'administrate'); ?></p>
			<input type="submit" value="<?php _e('Place Order', 'administrate'); ?>" class="<?= $this->plugin->add_namespace('btn', '-'); ?>">
		</div>
	<?php } ?>
	
	<?php if ($this->get_option('payment_by_cc')) { ?>
		<?php if ($this->get_option('payment_by_invoice')) { ?>
			<h3><label><input type="radio" name="order_payment_type" value="C" id="order-payment-type-cc"> <?php _e('Pay by Credit Card', 'administrate'); ?></label></h3>
		<?php } else { ?>
			<input type="hidden" name="order_payment_type" value="C">
		<?php } ?>
		<div class="<?= $this->add_namespace(array('payment', 'option'), '-'); ?> <?= $this->add_namespace(array('payment', 'option', 'cc'), '-'); ?>">
			<p><?= $this->get_option('cc_instructions'); ?></p>
			<?php $this->display_payment_form(); ?>
		</div>
	<?php } ?>			
			
	<?php if ($this->get_option('payment_by_invoice') && $this->get_option('payment_by_cc')) { ?>
		</div>
	<?php } ?>
	
</div>
