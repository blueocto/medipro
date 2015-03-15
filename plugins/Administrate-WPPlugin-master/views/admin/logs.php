<?php 
//  Set order statuses
$orderStatuses = array(
	'I'	=>	__('incomplete', 'administrate'),
	'C'	=>	__('complete', 'administrate'),
	'P'	=>	__('pending', 'administrate')
);

//  Payment types
$paymentTypes = array(
	'I'	=>	__('invoice', 'administrate'),
	'C'	=>	__('credit card', 'administrate')
);

//  If an order ID is set, display the order information
if (isset($_REQUEST[$this->plugin->add_namespace(array('order', 'id'))])) { 
	include($this->plugin->get_path('/views/admin/order.php'));

//  Otherwise display the orders table
} else {
	include($this->plugin->get_path('/views/admin/orders_table.php'));
}
?>
