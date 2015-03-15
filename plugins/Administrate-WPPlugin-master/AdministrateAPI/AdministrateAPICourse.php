<?php
//  Administrate API Course
class AdministrateAPICourse extends AdministrateAPIObject {
	
	//  Properties
	protected $fields = array(
		'id'				=>	array(
			'api_field'	=>	'DetailID'
		),
		'title'	=>	array(
			'api_field'	=>	'Title'
		),
		'code'			=>	array(
			'api_field'	=>	'Code'
		),
		'summary'		=>	array(
			'api_field'	=>	'Summary'
		),
		'schedule'		=>	array(
			'api_field'	=>	'Introduction'
		),
		'method'		=>	array(
			'api_field'	=>	'Method'
		),
		'prerequisites'	=>	array(
			'api_field'	=>	'Prerequisites'
		),
		'topics'		=>	array(
			'api_field'	=>	'Topics'
		),
		'benefits'		=>	array(
			'api_field'	=>	'Benefits'
		),
		'duration'	=>	array(
			'api_field'	=>	'Days'
		),
		'categories'	=>	array(
			'api_field'	=>	'Categories'
		),
		'custom_fields'	=>	array(
			'api_field' => 'CustomFields'
		)
	);
	
	//  Get the course title
	public function get_title() {
		return $this->_get_field('title');	
	}
	
	//  Get the course code
	public function get_code() {
		return $this->_get_field('code');	
	}
	
	//  Get the course summary
	public function get_summary() {
		return $this->_get_field('summary');	
	}
	
	//  Get the course schedule
	public function get_schedule() {
		return $this->_get_field('schedule');	
	}
		
	//  Get the course inclusions
	public function get_inclusions() {
		// Look through custom fields to grab CourseText7
		$custom_fields = $this->_get_field('custom_fields');
		if ($custom_fields) {
			foreach ($custom_fields as $field) {
				if($field->Name == 'CourseText7') {
					return $field->Value;
				}
			}
		}
		return '';
	}
	
	//  Get the course method
	public function get_method() {
		return $this->_get_field('method');	
	}
	
	//  Get the course prerequisites
	public function get_prerequisites() {
		return $this->_get_field('prerequisites');	
	}

	//  Get the course code
	public function get_topics() {
		return $this->_get_field('topics');	
	}

	//  Get the course benefits
	public function get_benefits() {
		return $this->_get_field('benefits');	
	}
	
	//  Get the course duration
	public function get_duration() {
		return $this->_get_field('duration');	
	}
	
	//  Get the course categories
	public function get_categories() {
		if (!property_exists($this, 'categories')) {
			$this->categories = array();
			$categories = $this->_get_field('categories');
			if ($categories) {
				foreach ($categories as $category) {
					array_push($this->categories, new AdministrateAPICourseCategory($category));
				}
			}
		}
		return $this->categories;
	}
	
	//  Get events for this course
	public function get_events() {
		$courseEvents = array();
		$plugin = AdministratePlugin::get_singleton();
		$allEvents = $plugin->make_api_call('get_events');
		foreach ($allEvents as $event) {
			if ($event->get_course_code() == $this->get_code()) {
				array_push($courseEvents, $event);	
			}
		}
		return $courseEvents;
	}
	
	//  Whether or not the course has events
	public function has_events() {
		return (count($this->get_events()) > 0);	
	}
	
}
