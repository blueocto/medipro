<?php
abstract class AdministrateAPIObject {

	//  Properties
	protected $obj = false;
	protected $fields = array();
	
	//  Construct
	public function __construct($obj = false) {
		
		//  Save the object
		$this->obj = $obj;
		
	}
	
	//  Validate passed fields
	public function fields_have_errors($fields = array()) {
	
		//  Loop through fields
		$errors = array();
		foreach ($this->fields as $field=>$properties) {
			//  Standard validations?
		}
		
		if (count($errors) > 0) {
			return $errors;
		} else {
			return false;
		}
		
	}
	
	//  Prepare & sanitize fields for API call
	public function prepare_fields($inputFields = array()) {
		
		//  Loop through fields
		$outputFields = array();
		foreach ($this->fields as $field=>$properties) {
			
			//  Only proceed if the input field was passed
			if (isset($inputFields[$field]) || array_key_exists($field, $inputFields)) {
				
				//  Save it as an output field
				$outputFields[$properties['api_field']] = $inputFields[$field];
				
				//  If a mapping was specified, apply it
				if (isset($properties['mappings'])) {
					$outputFields[$properties['api_field']] = $properties['mappings'][$outputFields[$properties['api_field']]];
				}
				
				//  If a time format was specified, apply it
				if (isset($properties['time_format'])) {
					$outputFields[$properties['api_field']] = date($properties['time_format'], $outputFields[$properties['api_field']]);
				}
			
			//  Or if the field doesn't exist, but the auto fill flag is set, use the default
			} else if (isset($properties['auto_fill']) && $properties['auto_fill']) {
				$outputFields[$properties['api_field']] = $properties['default'];
			}

		}
		
		//  Return output fields
		return $outputFields;
		
	}
	
	//  Prepare object
	public function prepare_object($inputFields = array()) {
		$fields = $this->prepare_fields($inputFields);
		$obj = new stdClass();
		foreach ($fields as $key=>$val) {
			$obj->$key = $val; 	
		}
		return $obj;
	}
	
	//  Get a field
	protected function _get_field($field) {
		$field = $this->fields[$field]['api_field'];
		if ($this->obj && property_exists($this->obj, $field)) {
			return $this->obj->$field;	
		} else {
			return false;	
		}
	}
	
	//  Get the ID
	public function get_id() {
		return $this->_get_field('id');	
	}
	
	//  Whether the object exists or not
	public function exists() {
		return ($this->get_id() !== false);	
	}
	
}
?>
