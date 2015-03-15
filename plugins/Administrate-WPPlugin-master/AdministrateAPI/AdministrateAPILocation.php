<?php
//  Administrate API Location
class AdministrateAPILocation extends AdministrateAPIObject {
	
	//  Properties
	protected $fields = array(
		'id'		=>	array(
			'api_field'	=>	'LocationID'
		),
		'name'			=>	array(
			'api_field'	=>	'Name'
		),
		'description'	=>	array(
			'api_field'	=>	'Description'
		),
		'country'	=>	array(
			'api_field'	=>	'CountryCode'
		),
		'region'	=>	array(
			'api_field'	=>	'RegionID'
		)
	);
	
	//  Get the name
	public function get_name() {
		return $this->_get_field('name');	
	}
	
	//  Get the country
	public function get_country() {
		return $this->_get_field('country');	
	}
	
}
?>