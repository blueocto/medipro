<?php
//  Administrate event price widget
class AdministrateWidgetEventPrice extends WPPluginPatternWidget {
	
	//  Properties
	protected $key = 'event_price';
	protected $shortCode = false;
	protected $currencySymbols = array(
		'AED'	=>	'د.إ',
		'AUD'	=>	'$',
		'CAD'	=>	'$',
		'BHD'	=>	'.د.ب',
		'EGP'	=>	'£',
		'EUR'	=>	'€',
		'GBP'	=>	'£',
		'INR'	=>	'₹',
		'JOD'	=>	'دينار',
		'KWD'	=>	'ك',
		'LBP'	=>	'ل.ل',
		'OMR'	=>	'﷼',
		'PLN'	=>	'zł',
		'QAR'	=>	'ريال',
		'SAR'	=>	'﷼',
		'SYP'	=>	'£',
		'USD'	=>	'$',
		'NZD'	=>	'$'
	);
	protected $suffixedSymbols = array(
		'AED',
		'BHD',
		'JOD',
		'KWD',
		'LBP',
		'OMR',
		'QAR',
		'SAR'
	);
	
	//  Constructor
	public function __construct(&$plugin) {
		
		//  Call parent constructor
		parent::__construct($plugin);
		
		//  Add the jQuery plugin & CSS
		wp_enqueue_script($this->add_namespace(''), $this->_get_url('/jquery.administrate_event_price.js'), array('jquery'), false, true);
		wp_enqueue_style($this->add_namespace(''), $this->_get_url('/style.css'));
			
	}
	
	//  Display the currency selector
	public function run(&$event, $fieldName = false, $defaultCurrency = '', $showCurrencyIndicator = true, $currencyIndicator = 'symbol') {
		
		//  Set the prices
		$prices = $event->get_prices();
		
		//  If no default currency was passed
		if (empty($defaultCurrency)) {
			$defaultCurrency = $this->_get_default_currency($event);
		}
		
		//  Include the templates
		ob_start();
		$this->display_prices($event, $this->_get_pricing_basis(), $defaultCurrency, $showCurrencyIndicator, $currencyIndicator);
		if ($fieldName) {
			$this->display_selector($event, $defaultCurrency, $fieldName);
		}
		return ob_get_clean();
	
	}
	
	//  Display price list
	public function display_prices(&$event, $pricingBasis = false, $defaultCurrency = '', $showCurrencyIndicator = true, $currencyIndicator = 'symbol') {
	
		//  If no pricing basis was passed, get the global value
		if (!$pricingBasis) {
			$pricingBasis = $this->_get_pricing_basis();	
		}
		
		//  If no default currency is defined, get it from the event
		if (empty($defaultCurrency)) {
			$defaultCurrency = $this->_get_default_currency($event);	
		}
		
		//  Set the prices
		$prices = $event->get_prices();
		
		//  Include the template
		include($this->get_path('/views/prices.php'));
	
	}
	
	//  Display currency selector
	public function display_selector(&$events, $defaultCurrency = '', $fieldName = '') {
		
		//  If only a single event was passed, put it in an array
		if (!is_array($events)) {
			$events = array($events);
		}
		
		//  Loop through all the events and find all unique currencies
		$currencies = array();
		foreach ($events as $event) {
			foreach ($event->get_prices() as $price) {
				if (!in_array($price->get_currency(), $currencies)) {
					array_push($currencies, $price->get_currency());	
				}
			}
		}
		sort($currencies);
		
		//  Include the template
		include($this->get_path('/views/currency_selector.php'));
	
	}
	
	//  Get the pricing basis
	private function _get_pricing_basis() {
		if (!property_exists($this, 'pricingBasis')) {
			$this->pricingBasis = $this->plugin->get_option('basis', 'pricing');
		}
		return $this->pricingBasis;
	}
	
	//  Get the default currency
	private function _get_default_currency(&$event) {
		
		//  See if the event supports the admin's selected default currency and use it
		$currencyOption = $this->plugin->get_option('currency', 'pricing');
		foreach ($event->get_prices() as $price) {
			if ($price->get_currency() == $currencyOption) {
				$defaultCurrency = $price->get_currency();
				break;	
			}
		}
		
		//  If there is still no default currency, just use the event's default
		if (empty($defaultCurrency)) {
			$defaultCurrency = $event->get_default_currency();	
		}
		
		return $defaultCurrency;
	
	}
	
	//  Get a currency symbol
	public function get_currency_symbol($currency) {
		return $this->currencySymbols[$currency];	
	}
	
	//  Format a currency
	public function format_currency($amount, $currency, $showCurrencyIndicator = true, $currencyIndicator = 'symbol') {
		$amount = number_format($amount, 2);
		if ($showCurrencyIndicator) {
			if ($currencyIndicator == 'symbol') {
				if (in_array($currency, $this->suffixedSymbols)) {
					return $amount . $this->get_currency_symbol($currency);	
				} else {
					return $this->get_currency_symbol($currency) . $amount;
				}
			} else {
				return $amount . ' ' . $currency;	
			}
		} else {
			return $amount;	
		}
	}

}
