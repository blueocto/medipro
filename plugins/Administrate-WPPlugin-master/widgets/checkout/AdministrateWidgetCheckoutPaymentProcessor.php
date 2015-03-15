<?php
//  Administrate Widget Checkout Payment Processor
abstract class AdministrateWidgetCheckoutPaymentProcessor {
	
	//  Properties
	protected $key = '';
	protected $usStates = array(	//  For payment processors that require 2-character state abbreviation (ahem! SagePay!)
		'AL'	=>	'alabama',  
		'AK'	=>	'alaska',  
		'AZ'	=>	'arizona',  
		'AR'	=>	'arkansas',  
		'CA'	=>	'california',  
		'CO'	=>	'colorado',  
		'CT'	=>	'connecticut',  
		'DE'	=>	'delaware',  
		'DC'	=>	'district of columbia',  
		'FL'	=>	'florida',  
		'GA'	=>	'georgia',  
		'HI'	=>	'hawaii',  
		'ID'	=>	'idaho',  
		'IL'	=>	'illinois',  
		'IN'	=>	'indiana',  
		'IA'	=>	'iowa',  
		'KS'	=>	'kansas',  
		'KY'	=>	'kentucky',  
		'LA'	=>	'louisiana',  
		'ME'	=>	'maine',  
		'MD'	=>	'maryland',  
		'MA'	=>	'massachusetts',  
		'MI'	=>	'michigan',  
		'MN'	=>	'minnesota',  
		'MS'	=>	'mississippi',  
		'MO'	=>	'missouri',  
		'MT'	=>	'montana',
		'NE'	=>	'nebraska',
		'NV'	=>	'nevada',
		'NH'	=>	'new hampshire',
		'NJ'	=>	'new jersey',
		'NM'	=>	'new mexico',
		'NY'	=>	'new york',
		'NC'	=>	'north carolina',
		'ND'	=>	'north dakota',
		'OH'	=>	'ohio',  
		'OK'	=>	'oklahoma',  
		'OR'	=>	'oregon',  
		'PA'	=>	'pennsylvania',  
		'RI'	=>	'rhode island',  
		'SC'	=>	'south carolina',  
		'SD'	=>	'south dakota',
		'TN'	=>	'tennessee',  
		'TX'	=>	'texas',  
		'UT'	=>	'utah',  
		'VT'	=>	'vermont',  
		'VA'	=>	'virginia',  
		'WA'	=>	'washington',  
		'WV'	=>	'west virginia',  
		'WI'	=>	'wisconsin',  
		'WY'	=>	'wyoming'
	);
	
	//  Constructor
	public function __construct(&$plugin, &$widget, &$order = false) {
		$this->plugin = &$plugin;
		$this->widget = &$widget;
		$this->order = &$order;
	}
	
	//  Display payment form
	public function display_form() { }
	
	//  Validate payment
	protected function _payment_has_errors() { 
		return false;
	}
	
	//  Process the payment
	protected function _process_payment($txnId) {
		$this->order->set_processor_transaction_id($txnId);
	}
	
	//  Handle payment notification
	public function handle_payment() {
		if (!$this->_payment_has_errors()) {
			$this->_process_payment();	
		}
	}
	
	//  Set the order after initialization
	public function set_order(&$order) {
		$this->order = &$order;	
	}
	
	//  Get an option
	protected function _get_option($key) {
		return $this->widget->get_option($this->key . $this->plugin->get_key_delimiter() . $key);	
	}

}
?>