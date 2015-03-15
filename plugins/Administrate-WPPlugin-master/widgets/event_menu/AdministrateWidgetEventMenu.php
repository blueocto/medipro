<?php
//  Include the parent class
require_once($this->plugin->get_path('/widgets/AdministrateWidgetMenu.php'));

//  Administrate event menu widget
class AdministrateWidgetEventMenu extends AdministrateWidgetMenu {

	//  Configuration
	protected $key = 'event_menu';
	
	//  Constructor
	public function __construct(&$plugin) {
	
		//  Call parent constructor
		parent::__construct($plugin);
		
		//  Only proceed if the proper option was set
		$showSubmenu = $this->plugin->get_option('show_submenu', 'checkout');
		$this->page = $this->plugin->get_option('checkout_page', 'checkout');
		if (!empty($showSubmenu) && !empty($this->page)) {
		
			//  Set the event ID key
			$this->eventKey = $this->plugin->add_namespace(array('checkout', 'event', 'id'));
			
			//  Add the submenu filter
			add_filter('wp_nav_menu_objects', array($this, 'add_menu'));
		
		}
		
	}
	
	//  Add the menu items
	protected function _add_items(&$item) {
		
		//  Query the API for events from upcoming events
		$events = $this->plugin->make_api_call('get_events');
		
		//  Loop through the events
		$num = 1;
		foreach ($events as $event) {
		
			//  Add the event
			$categoryItem = $this->_add_item(
				$item, 
				$event->get_id(), 
				'<span class="' . $this->plugin->add_namespace(array('event'), '-') . '">' . $this->plugin->format_date_span($event->get_dates()) . __(':</span> ', 'administrate') . $event->get_course_title() . __(' (', 'administrate') . $event->get_course_code() . __(')', 'administrate'), 
				$this->plugin->get_registration_url($event->get_id()), 
				++$num,  
				(isset($_REQUEST[$this->eventKey]) && ($_REQUEST[$this->eventKey] == $event->get_id()))
			);
		
		}
			
	}

}
