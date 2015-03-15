<?php
//  Administrate course widget
class AdministrateWidgetCourse extends WPPluginPatternWidget {

	//  Configuration
	protected $key = 'course';
	
	//  Properties
	private $course = false;
	
	//  Constructor
	public function __construct(&$plugin) {
		
		//  Call parent constructor
		parent::__construct($plugin);
		
		//  Add the CSS
		wp_enqueue_style($this->add_namespace(''), $this->_get_url('/style.css'));
			
	}
	
	//  Run the checkout
	public function run($params = array()) {
		
		//  Extract parameters
		extract(shortcode_atts(array(
			'course'	=>	'',
			'show_code'	=>	true
		), $params));
		
		//  If no course parameter was passed, see if we can get a request parameter
		if (empty($course)) {
			if (isset($_REQUEST[$this->plugin->add_namespace(array('course', 'id'))])) {
				$course = $_REQUEST[$this->plugin->add_namespace(array('course', 'id'))];
			}
		}
		
		//  Try to initialize the course  
		if (!empty($course)) {
			$this->course = $this->plugin->make_api_call('get_course_by_code', $course);
		}
		
		//  If the course exists, display it
		if ($this->course) {
		
			$course_fields = $this->plugin->get_course_fields($this->course, $this->get_option('page_fields'));
		
			//  Save course and registration page options
			$coursePage = $this->plugin->get_option('course_page', 'course');
			$registrationPage = $this->plugin->get_option('checkout_page', 'checkout');
			
			//  Include the template
			ob_start();
			include($this->get_path('/views/course.php'));
			return ob_get_clean();
		
		//  Otherwise display the subcategory
		} else {
			
			return $this->plugin->run_widget(
				'subcategory',
				array(
					'show_codes'	=>	$show_codes
				)
			);
			
		}
		
	}

}
