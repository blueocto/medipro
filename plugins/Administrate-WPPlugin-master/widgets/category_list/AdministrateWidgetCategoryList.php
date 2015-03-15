<?php
//  Administrate category list
class AdministrateWidgetCategoryList extends WPPluginPatternWidget {

	//  Configuration
	protected $key = 'category_list';
	
	//  Run the checkout
	public function run($params = array()) {
		
		//  Extract parameters
		extract(shortcode_atts(array(
			'show_subcategories'	=>	true
		), $params));
		
		//  Set param types
		$show_subcategories = (($show_subcategories === true) || ($show_subcategories === 'true'));
		
		//  Save course page option
		$coursePage = $this->plugin->get_option('course_page', 'course');
		
		//  Include the appropriate template
		ob_start();
		include($this->get_path('/views/category_list.php'));
		return ob_get_clean();
		
	}
	
	//  Get all categories
	public function get_categories() {
		if (!property_exists($this, 'categories')) {
			$this->categories = $this->plugin->make_api_call('get_categories', $this->plugin->to_boolean($this->plugin->get_option('show_empty_courses', 'course')));
		}
		return $this->categories;
	}

}
