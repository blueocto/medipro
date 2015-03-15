<?php
//  Administrate category widget
class AdministrateWidgetCategory extends WPPluginPatternWidget {

	//  Configuration
	protected $key = 'category';
	
	//  Properties
	private $category = false;
	
	//  Run the checkout
	public function run($params = array()) {
		
		//  Extract parameters
		extract(shortcode_atts(array(
			'category'		=>	0,
			'show_codes'	=>	true
		), $params));
		$categoryId = intval($category);
		
		//  If no category parameter was passed, see if we can get a request parameter
		if ($categoryId === 0) {
			if (isset($_REQUEST[$this->plugin->add_namespace(array('category', 'id'))])) {
				$categoryId = $_REQUEST[$this->plugin->add_namespace(array('category', 'id'))];
			}
		}
		
		//  Try to initialize the category  
		if (!empty($categoryId)) {
			$this->category = $this->plugin->make_api_call('get_category', $categoryId);
		}
		
		//  If the category exists, display it
		if ($this->category) {
		
			//  Include the template
			$this->_set_courses();
			ob_start();
			include($this->get_path('/views/category.php'));
			return ob_get_clean();
		
		//  Otherwise display the category list
		} else if ($this->plugin->to_boolean($this->plugin->get_option('show_category_links', 'course'))) {
			
			return $this->plugin->run_widget('category_list');
			
		}
		
	}
	
	//  Set courses
	private function _set_courses() {
		$this->courses = $this->plugin->make_api_call('get_courses', array('CourseCategoryID'=>$this->category->get_id()));
	}
	
	//  Get courses
	public function get_courses() {
		return $this->courses;
	}

}
