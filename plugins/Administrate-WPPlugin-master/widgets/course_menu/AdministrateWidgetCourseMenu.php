<?php
//  Include the parent class
require_once($this->plugin->get_path('/widgets/AdministrateWidgetMenu.php'));

//  Administrate course menu widget
class AdministrateWidgetCourseMenu extends AdministrateWidgetMenu {

	//  Configuration
	protected $key = 'course_menu';
	
	//  Constructor
	public function __construct(&$plugin) {
	
		//  Call the parent
		parent::__construct($plugin);
		
		//  Only proceed if the proper option was set
		$this->type = $this->plugin->get_option('submenu_type', 'course');
		$this->page = $this->plugin->get_option('course_page', 'course');
		$this->showEmptyCategories = $this->plugin->to_boolean($this->plugin->get_option('show_empty_courses', 'course'));
		if (!empty($this->type) && ($this->type != 'none') && !empty($this->page)) {
		
			//  Set the request parameters we'll use
			$this->categoryKey = $this->plugin->add_namespace(array('category', 'id'));
			$this->subcategoryKey = $this->plugin->add_namespace(array('subcategory', 'id'));
			$this->courseKey = $this->plugin->add_namespace(array('course', 'id'));
			
			//  Add the submenu filter
			add_filter('wp_nav_menu_objects', array($this, 'add_menu'));
		
		}
		
	}
	
	//  Add the menu items
	protected function _add_items(&$item) {
		
		//  If the submenu type is courses, spit them out
		if ($this->type == 'course') {
		
			//  Query the API for courses from upcoming events
			$courses = $this->plugin->make_api_call('get_courses');
			
			//  Loop through the courses and add them
			$num = 1;
			foreach ($courses as $course) {
				
				//  Only add the item if it isn't supposed to be hidden
				if (!$this->plugin->filter_object_is_hidden('course', $course->get_code())) {
				
					//  Add the item
					$categoryItem = $this->_add_item(
						$item, 
						$course->get_code(), 
						'<span class="' . $this->plugin->add_namespace(array('course', 'code'), '-') . '">' . $course->get_code() . __(':</span> ', 'administrate') . $course->get_title(), 
						$this->plugin->get_course_url($course),
						++$num,  
						(isset($_REQUEST[$this->courseKey]) && ($_REQUEST[$this->courseKey] == $course->get_code()))
					);
				
				}
				
			}
	
		//  Otherwise this is going to be a category / category+subcategory / category+course menu
		} else {
			
			//  Get unique categories
			$categories = $this->plugin->make_api_call('get_categories', $this->showEmptyCategories);
			
			//  Loop through the subcategories and add them
			$catNum = 0;
			foreach ($categories as $category) {
				
				//  Only add the item if it isn't supposed to be hidden
				if (!$this->plugin->filter_object_is_hidden('category', $category->get_id())) {
				
					//  Add the category
					$categoryItem = $this->_add_item(
						$item, 
						$category->get_id(), 
						$category->get_name(), 
						$this->plugin->get_category_url($category),
						++$catNum,  
						(isset($_REQUEST[$this->categoryKey]) && ($_REQUEST[$this->categoryKey] == $category->get_id()))
					);
					
					//  If the menu type is subcategories, add subcategories now
					if ($this->type == 'subcategory') {
						
						//  Get the subcategories
						$subcategories = $category->get_subcategories();
						
						//  Loop through the subcategories and add them
						$subcatNum = 0;
						foreach ($subcategories as $subcategory) {
							
							//  Only add the item if it isn't supposed to be hidden
							if (!$this->plugin->filter_object_is_hidden('subcategory', $subcategory->get_id())) {
							
								//  Add the item
								$subcategoryItem = $this->_add_item(
									$categoryItem, 
									's'.$subcategory->get_id(), 
									$subcategory->get_name(), 
									$this->plugin->get_subcategory_url($subcategory, $category),
									++$subcatNum,  
									(isset($_REQUEST[$this->subcategoryKey]) && ($_REQUEST[$this->subcategoryKey] == $subcategory->get_id()))
								);
							
							}

						}
					
					//  Or if the menu type is catcourse, add courses now	
					} else if ($this->type == 'catcourse') {
									
						//  Get the courses
						$courses = $this->plugin->make_api_call('get_courses', array('CourseCategoryID'=>$category->get_id()));
						
						//  Loop through the courses and add them
						$courseNum = 0;
						foreach ($courses as $course) {
							
							//  Only add the item if it isn't supposed to be hidden
							if (!$this->plugin->filter_object_is_hidden('course', $course->get_code())) {
	
								//  Add the item						
								$courseItem = $this->_add_item(
									$categoryItem, 
									$course->get_code(), 
									$course->get_title(), 
									$this->plugin->get_course_url($course, false, $category),
									++$courseNum,  
									(isset($_REQUEST[$this->courseKey]) && ($_REQUEST[$this->courseKey] == $course->get_title()))
								);
							
							}
							
						}
						
					}
				
				}
			
			}
			
		}
		
		//  Return the items with any additions
		return $this->items;
		
	}

}
