<?php
//  Administrate API Course Category
class AdministrateAPICourseCategory extends AdministrateAPIObject {
	
	//  Properties
	protected $fields = array(
		'id'		=>	array(
			'api_field'	=>	'CategoryID'
		),
		'name'			=>	array(
			'api_field'	=>	'Name'
		),
		'subcategories'	=>	array(
			'api_field'	=>	'SubCategories'
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
	
	//  Get subcategories
	public function get_subcategories() {
		$tmpSubcategories = $this->_get_field('subcategories');
		$subcategories = array();
		if ($tmpSubcategories) {
			foreach ($tmpSubcategories as $subcategory) {
				array_push($subcategories, new AdministrateAPICourseSubcategory($subcategory));
			}
		}
		return $subcategories;
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
			
		//  Otherwise loop through subcategories trying to find courses
		} else {
			foreach ($this->get_subcategories() as $subcategory) {
				if ($subcategory->has_courses($showEmptyCourses)) {
					$hasCourses = true;
					break;	
				}
			}
		}
		
		//  Return the result
		return $hasCourses;
		
	}
	
}
?>
