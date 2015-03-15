<?php
//  Administrate course table widget
class AdministrateWidgetCourseList extends WPPluginPatternWidget {

	//  Configuration
	protected $key = 'course_list';
	protected $category = false;
	protected $subcategory = false;
	
	//  Run the checkout
	public function run($params = array()) {
		
		//  Extract parameters
		extract(shortcode_atts(array(
			'category'		=>	0,
			'subcategory'	=>	0,
			'show_codes'	=>	true
		), $params));
		
		//  Set param types
		$show_codes = (($show_codes === true) || ($show_codes === 'true'));
		$categoryId = intval($category);
		$subcategoryId = intval($subcategory);
		
		//  If no course parameter was passed, see if we can get a request parameter
		if (($categoryId === 0) && ($subcategoryId === 0)) {
			if (isset($_REQUEST[$this->plugin->add_namespace(array('subcategory', 'id'))])) {
				$subcategoryId = $_REQUEST[$this->plugin->add_namespace(array('subcategory', 'id'))];
			} else if (isset($_REQUEST[$this->plugin->add_namespace(array('category', 'id'))])) {
				$categoryId = $_REQUEST[$this->plugin->add_namespace(array('category', 'id'))];
			}
		}
		
		//  Save course page option
		$coursePage = $this->plugin->get_option('course_page', 'course');
		
		//  Initialize SOAP params
		$params = array();
		
		//  If a subcategory was passed, use in filter
		if (!empty($subcategoryId)) {
			$params['CourseSubCategoryID'] = $subcategoryId;
		
		//  Or if a category was passed, use that in filter
		} else if (!empty($categoryId)) {
			$params['CourseCategoryID'] = $categoryId;	
		}
		
		//  Display the course list
		$this->_set_courses($params);
		ob_start();
		include($this->get_path('/views/course_list.php'));	
		return ob_get_clean();
		
	}
	
	//  Set courses
	private function _set_courses($params) {
		if ($this->plugin->to_boolean($this->plugin->get_option('show_empty_courses', 'course'))) {
			$this->courses = $this->plugin->make_api_call('get_courses', $params);
		} else {
			$this->courses = array();
			$events = $this->plugin->make_api_call('get_events', $params);
			foreach ($events as $event) {
				$key = $event->get_course_code();
				if (!isset($this->courses[$key])) {
					$course = $this->plugin->make_api_call('get_course_by_code', $event->get_course_code());
					if ($course->exists()) {
						$this->courses[$key] = $course;
					}
				}
			}
			ksort($this->courses);
		}
	}
	
	//  Get courses
	public function get_courses() {
		return $this->courses;	
	}

}
