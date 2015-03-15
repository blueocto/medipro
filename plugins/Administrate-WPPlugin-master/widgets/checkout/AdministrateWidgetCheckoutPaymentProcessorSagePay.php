<?php
//  SagePay Administrate Widget Checkout Payment Processor
class AdministrateWidgetCheckoutPaymentProcessorSagePay extends AdministrateWidgetCheckoutPaymentProcessor {
	
	//  Properties
	protected $key = 'sagepay';
	protected $apiUrl = '';
	protected $decryptedParams = array();
	
	//  Constructor
	public function __construct(&$plugin, &$widget, &$order = false) {
		
		//  Call parent class
		parent::__construct($plugin, $widget, $order);
		
		//  Parse the params
		if (isset($_GET['crypt'])) {
			parse_str($this->_decrypt_str($_GET['crypt']), $this->decryptedParams);
		}
		
		//  If the order isn't defined yet and an item number is in the request, initialize order
		if (!$this->order && isset($this->decryptedParams['VendorTxCode'])) {
			$this->set_order($this->get_order());
		}
		
		if($this->plugin->get_option('sagepay_mode', 'checkout') == 'Test') {
			// URL for 'Form' transactions as defined within
			// the simulator environment once registered.
			$this->apiUrl = 'https://test.sagepay.com/Simulator/VSPFormGateway.asp';
		} else {
			$this->apiUrl = 'https://live.sagepay.com/gateway/service/vspform-register.vsp';
		}
	}
	
	//  Display payment form
	public function display_form() {
		
		//  Get the required options
		$vendor = $this->plugin->get_option('sagepay_vendor_username', 'checkout');
		$emailMessage = $this->plugin->get_option('sagepay_email_message', 'checkout');
		$notifyUser = $this->plugin->to_boolean($this->plugin->get_option('cc_send_email', 'checkout'));
		
		// Use the session ID for the transaction Code. Append a timestamp
		// so that TX code is changed if we cancel then re-submit the form.
		$txCode= $this->plugin->get_namespace() . '-wp-' . $this->order->get_id() . '-' . time();
		
		//  Figure out what emails to send
		if (!$notifyUser) {
			$sendEmail = 0;	
		} else {
			$sendEmail = 1;
		}
		
		//  Generate the basket string
		$basketParts = array(
			1,																			// always 1 line of detail
			$this->order->get_course_title(),											// item name
			$this->order->get_num_attendees(),											// quantity
			$this->order->get_event_net_price(),										// price excluding tax
			$this->order->get_event_gross_price()-$this->order->get_event_net_price(),	// tax
			$this->order->get_event_gross_price(),										// price including tax
			$this->order->get_event_gross_price()*$this->order->get_num_attendees()		// total
		);
		$basket = implode(':', $basketParts);
		
		//  If the billing country is UK, change to GB
		if ($this->order->get_invoice_country() == 'UK') {
			$country = 'GB';
		} else {
			$country = $this->order->get_invoice_country();
		}
		
		//  If the billing country is US, SagePay requires the state abbreviation to be 2 characters (sigh)
		if ($this->order->get_invoice_country() == 'US') {
			
			//  Strip everything but characters
			$territory = preg_replace("/[^A-Za-z ]/", '', $this->order->get_invoice_territory());
			
			//  If we don't have a match already, search for the full state name
			if (!isset($this->usStates[strtoupper($territory)])) {
				if ($tmpKey = array_search(strtolower($territory), $this->usStates)) {
					$territory = $tmpKey;
				}
			
			//  Otherwise just make sure the state is upper case
			} else {
				$territory = strtoupper($territory);
			}
			
		}
		
		//  Set the parameters to pass
		$params = array(
			
			//  Transaction fields
			'VendorTxCode'		=>	$txCode,
			'SuccessURL'		=>	$this->widget->get_step_url(5, true),
			'FailureURL'		=>	$this->widget->get_step_url(4, true),
			
			//  Order info
			'Amount'			=>	floatval($this->order->get_event_gross_price() * $this->order->get_num_attendees()),
			'Currency'			=>	$this->order->get_currency(),
			'Description'		=>	$this->order->get_course_title() . ' ' . __('Course Registration', 'administrate'),
			'CustomerName'		=>	$this->order->get_buyer_first_name() . ' ' . $this->order->get_buyer_last_name(),
			'Basket'			=>	$basket,
			
			//  Email options
			'SendEMail'			=>	$sendEmail,
			'CustomerEMail'		=>	$this->order->get_buyer_email(),
			//'VendorEMail'		=>	$adminEmail,
			'eMailMessage'		=>	$emailMessage,
			
			//  Billing fields
			'BillingFirstnames'	=>	$this->order->get_buyer_first_name(),
			'BillingSurname'	=>	$this->order->get_buyer_last_name(),
			'BillingAddress1'	=>	$this->order->get_invoice_street(),
			//'BillingAddress2'	=>	'',
			'BillingCity'		=>	$this->order->get_invoice_city(),
			'BillingPostCode'	=>	$this->order->get_invoice_postal_code(),
			'BillingCountry'	=>	$country,
			'BillingState'		=>	$territory,
			'BillingPhone'		=>	$this->order->get_buyer_phone(),
			
			//  Shipping fields
			'DeliveryFirstnames'=>	$this->order->get_buyer_first_name(),
			'DeliverySurname'	=>	$this->order->get_buyer_last_name(),
			'DeliveryAddress1'	=>	$this->order->get_invoice_street(),
			//'DeliveryAddress2'	=>	'',
			'DeliveryCity'		=>	$this->order->get_invoice_city(),
			'DeliveryPostCode'	=>	$this->order->get_invoice_postal_code(),
			'DeliveryCountry'	=>	$country,
			'DeliveryState'		=>	$territory,
			'DeliveryPhone'		=>	$this->order->get_buyer_phone(),
			
			//  Payment options
			'AllowGiftAid'		=>	0,
			'ApplyAVSCV2'		=>	1,
			'Apply3DSecure'		=>	1
		
		);

		//  Encrypt the parameters -- NOTE: SagePay blows up if we encode the parameters, so decode them before passing them along
		$encryptedStr = $this->_encrypt_str(urldecode(http_build_query($params)));

		//  Include the form
		require_once($this->widget->get_path('/views/checkout_step4_payment_sagepay.php'));
	
	}
	
	//  Encrypt a string
	protected function _encrypt_str($str) {
		
		//  Add PKCS5 padding to the text to be encypted
    	$blockSize = 16;
		$padLength = $blockSize - (strlen($str) % $blockSize);
		for($i = 1; $i <= $padLength; $i++) {
			$str .= chr($padLength);
		}
		
    	//  Perform encryption with PHP's MCRYPT module
		$encryptedStr = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->_get_password(), $str, MCRYPT_MODE_CBC, $this->_get_password());
		
		//  Perform hex encoding and return
		return "@" . bin2hex($encryptedStr);
		
	}
	
	//  Decrypt a string
	protected function _decrypt_str($str) {
		
		//  Remove the first char which is @ to flag this is AES encrypted
		$str = substr($str, 1); 
		
		//  HEX decoding
		$str = pack('H*', $str);
		
		//  Perform decryption with PHP's MCRYPT module
		$decryptedStr = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->_get_password(), $str, MCRYPT_MODE_CBC, $this->_get_password());
		$padChar = ord($decryptedStr[strlen($decryptedStr) - 1]);
		return substr($decryptedStr, 0, -$padChar); 
		
	}
	
	//  Get the encryption password
	protected function _get_password() {
		if (!property_exists($this, 'password')) {
			$this->password = $this->plugin->get_option('sagepay_encryption_password', 'checkout');
		}
		return $this->password;
	}
		
	//  Validate payment
	protected function _payment_has_errors() {
		
		//  Check for errors with the payment
		$errors = false;
		
		//  If the return status is not 'OK', there was a problem
		if ($this->decryptedParams['Status'] != 'OK') {
			$errors = array();
			if ($this->decryptedParams['Status'] == 'NOTAUTHED') {
				array_push($errors, __('It appears that the payment was rejected by your bank.', 'administrate'));
			} else if ($this->decryptedParams['Status'] == 'ABORT') {
				array_push($errors, __('Payment was canceled.', 'administrate'));
			} else if ($this->decryptedParams['Status'] == 'REJECTED') {
				array_push($errors, __('Sorry, your payment was rejected.', 'administrate'));
			} else if ($this->decryptedParams['Status'] == 'ERROR') {
				array_push($errors, __('Sorry, and error occurred. Please try again later or contact us.', 'administrate'));
			}
		}
		
		//  Return the errors
		return $errors;	
		
	}
	
	//  Process the payment
	protected function _process_payment() {
		
		//  Save the transaction ID
		parent::_process_payment($this->decryptedParams['VPSTxId']);
		
		//  Redirect to step 5 (completion)
		wp_redirect($this->widget->get_step_url(5, true));
		exit;
		
	}
	
	//  Set the order
	public function get_order() {
		if (!$this->order || !property_exists($this, 'order')) {
			$txParts = explode('-', $this->decryptedParams['VendorTxCode']);
			$where = array(
				'order_id'	=>	$txParts[count($txParts)-1]
			);
			$this->order = new AdministrateWidgetCheckoutOrder($this->plugin, $where);	
		}
		return $this->order;
	}
	
	//  Get API URL
	private function _get_api_url() {
		return $this->apiUrl;	
	}
	
}
?>
