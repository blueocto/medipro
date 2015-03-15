<?php
//  Administrate subcategory widget
class AdministrateWidgetSubcategory extends WPPluginPatternWidget {

	//  Configuration
	protected $key = 'subcategory';
	
	//  Properties
	private $subcategory = false;
	
	//  Run the checkout
	public function run($params = array()) {
		
		//  Extract parameters
		extract(shortcode_atts(array(
			'subcategory'	=>	0,
			'category'		=>	0,
			'show_codes'	=>	true
		), $params));
		$subcategoryId = intval($subcategory);
		$categoryId = intval($category);
		
		//  If no subcategory parameter was passed, see if we can get a request parameter
		if ($subcategoryId === 0) {
			if (isset($_REQUEST[$this->plugin->add_namespace(array('subcategory', 'id'))])) {
				$subcategoryId = $_REQUEST[$this->plugin->add_namespace(array('subcategory', 'id'))];
			}
		}
		
		//  If no category parameter was passed, see if we can get a request parameter
		if ($categoryId === 0) {
			if (isset($_REQUEST[$this->plugin->add_namespace(array('category', 'id'))])) {
				$categoryId = $_REQUEST[$this->plugin->add_namespace(array('category', 'id'))];
			}
		}
		
		//  Try to initialize the subcategory  
		if (!empty($subcategoryId)) {
			$this->subcategory = $this->plugin->make_api_call('get_subcategory', $subcategoryId);
		}
		
		//  If the subcategory exists, display it
		if ($this->subcategory) {
		
			//  Save category and registration page options
			$coursePage = $this->plugin->get_option('course_page', 'category');
		
			//  Include the template
			$this->_set_courses();
			ob_start();
			include($this->get_path('/views/subcategory.php'));
			return ob_get_clean();
		
		//  Otherwise display the category
		} else {
			
			return $this->plugin->run_widget(
				'category',
				array(
					'show_codes'	=>	$show_codes
				)
			);
			
		}
		
	}
	
	//  Set courses
	private function _set_courses() {
		$this->courses = $this->plugin->make_api_call('get_courses', array('CourseCategoryID'=>$this->subcategory->get_id()));
	}
	
	//  Get courses
	public function get_courses() {
		return $this->courses;
	}

}
