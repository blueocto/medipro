<?php
//  Administrate API Event Price
class AdministrateAPIEventPrice extends AdministrateAPIObject {
	
	//  Properties
	protected $fields = array(
		'currency'		=>	array(
			'api_field'	=>	'CurrencyCode'
		),
		'level'			=>	array(
			'api_field'	=>	'PriceLevel'
		),
		'net'			=>	array(
			'api_field'	=>	'NetPrice'
		),
		'gross'			=>	array(
			'api_field'	=>	'GrossPrice'
		),
		'items_net'		=>	array(
			'api_field'	=>	'NetItemsPrice'
		),
		'items_gross'	=>	array(
			'api_field'	=>	'GrossItemsPrice'
		),
		'num_items'		=>	array(
			'api_field'	=>	'ItemCount'
		)
	);
	
	//  Get the currency
	public function get_currency() {
		return $this->_get_field('currency');	
	}
	
	//  Get the net price
	public function get_net() {
		return $this->_get_field('net');	
	}
	
	//  Get the gross price
	public function get_gross() {
		return $this->_get_field('gross');	
	}
	
	//  Get the display price
	public function get_display_price($basis = 'net') {
		if ($basis == 'net') {
			return $this->get_net();	
		} else {
			return $this->get_gross();
		}
	}
	
}
