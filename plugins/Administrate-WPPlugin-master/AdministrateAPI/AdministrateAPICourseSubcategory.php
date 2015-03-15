<?php
//  Administrate API Course Subcategory
class AdministrateAPICourseSubcategory extends AdministrateAPIObject {
	
	//  Properties
	protected $fields = array(
		'id'		=>	array(
			'api_field'	=>	'SubCategoryID'
		),
		'name'			=>	array(
			'api_field'	=>	'Name'
		),
		'description'	=>	array(
			'api_field'	=>	'Description'
		)
	);
	
	//  Get the name
	public function get_name() {
		return $this->_get_field('name');	
	}
	
	//  Get the description
	public function get_description() {
		return $this->_get_field('description');	
	}
	
	//  Get courses
	public function get_courses() {
		$params = array(
			'CourseCategoryID'	=>	$this->get_id()
		);
		return AdministrateAPI::get_courses($params);
	}
	
	//  Whether the category has courses or not
	public function has_courses($showEmptyCourses = true) {
		
		//  Assume no courses
		$hasCourses = false;
		
		//  First try getting courses directly associated with the category
		$courses = $this->get_courses();
		
		//  If there were, set it true
		if (count($courses) > 0) {
			foreach ($courses as $course) {
				if ($showEmptyCourses || $course->has_events()) {
					$hasCourses = true;	
					break;
				}
			}
			
		} 
		
		//  Return the result
		return $hasCourses;
		
	}
	
	//  Get the parent category
	public function get_parent_category() {
		if (!property_exists($this, 'parentCategory')) {
			$this->parentCategory = false;
			foreach (AdministrateAPI::get_categories() as $category) {
				foreach ($category->get_subcategories() as $subcategory) {
					if ($subcategory->get_id() == $this->get_id()) {
						$this->parentCategory = $category;
						break 2;	
					}
				}
			}
		}
		return $this->parentCategory;
	}
	
}
?>
