<?php
//  Administrate subcategory list
class AdministrateWidgetSubcategoryList extends WPPluginPatternWidget {

	//  Configuration
	protected $key = 'subcategory_list';
	protected $category = false;
	
	//  Run the checkout
	public function run($params = array()) {
		
		//  Extract parameters
		extract(shortcode_atts(array(
			'category'	=>	0
		), $params));
		$this->categoryId = intval($category);
		
		//  If no category parameter was passed, see if we can get a request parameter
		if ($this->categoryId === 0) {
			if (isset($_REQUEST[$this->plugin->add_namespace(array('category', 'id'))])) {
				$this->categoryId = $_REQUEST[$this->plugin->add_namespace(array('category', 'id'))];
			}
		}
		
		//  Try to initialize the category  
		if (!empty($this->categoryId)) {
			$this->category = $this->plugin->make_api_call('get_category', $this->categoryId);
		}
		
		//  If the category exists, display subcategory list
		if ($this->category) {
		
			//  Save category and registration page options
			$coursePage = $this->plugin->get_option('course_page', 'course');
		
			//  Include the template
			ob_start();
			include($this->get_path('/views/subcategory_list.php'));
			return ob_get_clean();
		
		//  Otherwise display the category list
		} else {
			
			return $this->plugin->run_widget('category_list');
			
		}
		
	}
	
	//  Get all subcategories
	public function get_subcategories() {
		if (!property_exists($this, 'subcategories')) {
			if ($this->plugin->to_boolean($this->plugin->get_option('show_empty_courses', 'course'))) {
				$this->subcategories = $this->category->get_subcategories();
			} else {
				$uniqueCategories = array();

				$events = $this->plugin->make_api_call('get_events', array('CourseCategoryID'=>$this->categoryId));
				foreach ($events as $event) {
					$categories = $event->get_categories();
					if ($categories) {
						foreach ($categories as $category) {
							$tmpSubcategories = $category->get_subcategories();
							if ($tmpSubcategories) {
								foreach ($tmpSubcategories as $subcategory) {
									$uniqueCategories[$subcategory->get_id()] = $subcategory;
								}
							}
						}
					}
				}

				$this->subcategories = array();
				foreach ($uniqueCategories as $cat) {
					$this->subcategories[] = $cat;
				}
			}
		}

		return $this->subcategories;
	}

}
