<?php
//  Administrate course filter
class AdministrateCourseFilter extends WPPluginPatternDAO {
	
	//  Properties
	protected $tableKey = 'filters';
	protected $primaryKey = 'filter_id';
	
	//  Get the object type
	public function get_object_type() {
		return $this->row['filter_object_type'];	
	}
	
	//  Get the object ID
	public function get_object_id() {
		return $this->row['filter_object_id'];	
	}
	
	//  Whether or not the object should be hidden
	public function is_hidden() {
		return ($this->row['filter_hidden'] == 1);	
	}
	
	//  Get the URL string
	public function get_url_string() {
		return $this->row['filter_url_string'];	
	}
	
	//  Get the object path
	public function get_object_path() {
		return $this->row['filter_object_path'];	
	}
	
	//  Get the object keywords
	public function get_keywords() {
		return $this->row['filter_object_keywords'];	
	}
	
	//  Get the object description
	public function get_description() {
		return $this->row['filter_object_description'];	
	}
	
	//  Sanitize and pack up fields
	protected function _sanitize_fields($inputFields = array()) {
		
		//  Sanitize URL string
		$outputFields = $inputFields;
		if (isset($outputFields['filter_url_string'])) {
			$outputFields['filter_url_string'] = sanitize_file_name($outputFields['filter_url_string']);
		}
		
		//  Sanitize the object path
		if (isset($outputFields['filter_object_path'])) {
			$outputFields['filter_object_path'] = str_replace('//', '/', $outputFields['filter_object_path']);
		}
		
		//  Return output fields
		return $outputFields;
		
	}
	
	
}
