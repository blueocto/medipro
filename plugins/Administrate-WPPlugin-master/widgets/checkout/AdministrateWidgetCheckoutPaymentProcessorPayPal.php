<?php
//  PayPal Administrate Widget Checkout Payment Processor
class AdministrateWidgetCheckoutPaymentProcessorPayPal extends AdministrateWidgetCheckoutPaymentProcessor {
	
	//  Properties
	protected $key = 'paypal';
	protected $apiUrl = '';
	
	//  Constructor
	public function __construct(&$plugin, &$widget, &$order = false) {
		
		//  Call parent class
		parent::__construct($plugin, $widget, $order);
		
		//  If the order isn't defined yet and an item number is in the request, initialize order
		if (!$this->order && isset($_REQUEST['item_number'])) {
			$this->set_order($this->get_order());
		}
		
		if($this->plugin->get_option('paypal_mode', 'checkout') == 'Sandbox') {
			$this->apiUrl = 'https://api-3t.sandbox.paypal.com/nvp';
		} else {
			$this->apiUrl = 'https://api-3t.paypal.com/nvp';
		}
	}
	
	//  Display payment form
	public function display_form() {
		
		//  Set the params used to generate the PayPal button
		$params = array(
			"METHOD"		=>	"BMCreateButton",
			"VERSION"		=>	"65.2",
			"USER"			=>	$this->_get_option('user'),
			"PWD"			=>	$this->_get_option('password'),
			"SIGNATURE"		=>	$this->_get_option('signature'),
			"BUTTONCODE"	=>	"ENCRYPTED",
			"BUTTONTYPE"	=>	"BUYNOW",
			"BUTTONSUBTYPE"	=>	"SERVICES",
			"BUTTONCOUNTRY"	=>	"GB",
			"BUTTONIMAGE"	=>	"reg",
			"BUYNOWTEXT"	=>	"BUYNOW",
			"L_BUTTONVAR1"	=>	"item_number=" . $this->order->get_id(),
			"L_BUTTONVAR2"	=>	"item_name=" . $this->order->get_course_title(),
			"L_BUTTONVAR4"	=>	"quantity=" . $this->order->get_num_attendees(),
			"L_BUTTONVAR5"	=>	"currency_code=" . $this->order->get_currency(),
			"L_BUTTONVAR6"	=>	"no_shipping=1",
			"L_BUTTONVAR7"	=>	"no_note=1",
			"L_BUTTONVAR8"	=>	"notify_url=" . $this->widget->get_step_url(6, true),		// IPN
			"L_BUTTONVAR9"	=>	"cancel_return=" . $this->widget->get_step_url(4, true) . '&' . $this->plugin->add_namespace(array('processor', 'cancel')) . '=true',	// Cancel
			"L_BUTTONVAR10"	=>	"return=" . $this->widget->get_step_url(5, true),			// After completion
			"L_BUTTONVAR11"	=>	"rm=2",
			"L_BUTTONVAR12"	=>	"custom=" . $this->order->get_session_id()
		);
		
		//  Set the gross price
		$params["L_BUTTONVAR3"]	= "amount=" . $this->order->get_event_gross_price();
		
		//  Get the button variables from PayPal
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_URL, $this->apiUrl . '?'.http_build_query($params));
		$response = curl_exec($curl);
		curl_close($curl);
		parse_str($response);

		
		//  If the button generation was successful, display the PayPal form
		if (isset($ACK) && ($ACK == 'Success')) {
			require_once($this->widget->get_path('/views/checkout_step4_payment_paypal.php'));
		} else {
			$this->plugin->log('Unable to access PayPal button: ' . $L_LONGMESSAGE0);
		}
		
	}
	
	//  Validate payment
	protected function _payment_has_errors() {
		
		//  Check for errors with the payment
		$errors = false;
		if (
			!isset($_REQUEST['txn_type']) ||  //  Transaction type was not set
			!isset($_REQUEST['txn_id']) ||  //  Transaction ID was not set
			!isset($_REQUEST['item_number']) ||  //  Order ID was not set
			!isset($_REQUEST['custom']) ||  //  Session ID was not set
			!isset($_REQUEST['payment_status']) ||  //  Payment status was not set
			(($_REQUEST['payment_status'] != 'Completed') && ($_REQUEST['payment_status'] != 'Pending'))  //  Payment status is not complete or at least pending
		) {
			$errors =  array(__('There was an error processing your PayPal payment. Please try again or contact us for further details.', 'administrate'));
		
		//  Check to see if we can find the right order
		} else {
			$order = $this->get_order();
			if (!$this->order->exists()) {
				$errors =  array(__('There was an error matching your payment to your order. Please contact us for further details.', 'administrate'));
			}
		}
		
		//  Return the errors
		return $errors;	
		
	}
	
	//  Process the payment
	protected function _process_payment() {
		return parent::_process_payment($_REQUEST['txn_id']);
	}
	
	//  Set the order
	public function get_order() {
		if (!$this->order || !property_exists($this, 'order')) {
			$where = array(
				'order_id'			=>	$_REQUEST['item_number'],
				'order_session_id'	=>	$_REQUEST['custom']
			);
			$this->order = new AdministrateWidgetCheckoutOrder($this->plugin, $where);	
		}
		return $this->order;
	}
	
}
?>
