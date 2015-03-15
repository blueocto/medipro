<?php
//  Administrate API Order
class AdministrateAPIOrder extends AdministrateAPIObject {
	
	//  Properties
	protected $fields = array(
		'order_details'		=>	array(
			'api_field'	=>	'orderDetails'
		),
		'cart_entries'		=>	array(
			'api_field'	=>	'cartData'
		),
		'payment_details'	=>	array(
			'api_field'	=>	'paymentDetails'
		)
	);
	
	//  Add a new order
	public function add($fields = array()) {
		if (!$this->fields_have_errors($fields)) {
			try {
				AdministrateAPI::log('Place order', 'notice');
				$orderNum = AdministrateAPI::make_soap_call('placeOrder', $this->prepare_object($fields));
				return $orderNum;
			} catch (Exception $e) {
				AdministrateAPI::log($e->getMessage(), 'error');
				return false;
			}
		} else {
			return false;	
		}
	}
	
	//  Prepare fields
	public function prepare_fields($fields = array()) {
		
		//  Prepare order details
		$orderDetailsObj = new AdministrateAPIOrderDetails();
		$fields['order_details'] = $orderDetailsObj->prepare_fields($fields['order_details']);
		
		//  Prepare payment details
		$paymentDetailsObj = new AdministrateAPIOrderPayment();
		if (!isset($fields['payment_details'])) {
			$fields['payment_details'] = array();	
		}
		$fields['payment_details'] = $paymentDetailsObj->prepare_fields($fields['payment_details']);
		
		//  Prepare cart entries
		$delegateEntryObj = new AdministrateAPIDelegateCartEntry();
		for ($i = 0, $numEntries = count($fields['cart_entries']); $i < $numEntries; ++$i) {
			$fields['cart_entries'][$i] = new SoapVar(
				$delegateEntryObj->prepare_fields($fields['cart_entries'][$i]),
				XSD_ANYTYPE,
				'DelegateCartEntry',
				'urn:EglWdPublic'
			);
		}
		
		//  Return order object
		return parent::prepare_fields($fields);
	
	}
	
}
