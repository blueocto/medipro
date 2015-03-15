<?php
//  Administrate menu widget
abstract class AdministrateWidgetMenu extends WPPluginPatternWidget {

	//  Configuration
	protected $shortCode = false;
	
	
	//  Constructor
	public function __construct(&$plugin) {
	
		//  Save the plugin by reference
		$this->plugin = &$plugin;
		
	}
	
	//  Add the submenu
	public function add_menu($items, $args = array()) {
		
		//  Figure out the max menu ID so there are no conflicts
		$maxPost = $this->plugin->db->get_row("SELECT MAX(ID) as max_id FROM " . $this->plugin->db->posts);
		$this->menuItemId = $maxPost->max_id;
		
		//  Save the current items
		$this->items = $items;
		
		//  Loop through the items
		foreach ($items as $item) {
			
			//  If this menu item is the correct page, add the menu items
			if ($item->object_id == $this->page) {
				$this->_add_items($item);
			}
			
		}
		
		//  Return the items
		return $this->items;
			
	}
	
	//  Create a menu item based on parent
	protected function _add_item(&$parent, $id, $title, $url, $num = 1, $current = false) {
		
		//  Increment the menu item ID
		++$this->menuItemId;
		
		//  First, create a template submenu item
		$item = new stdClass();
		$item->post_status = $parent->post_status;
		$item->post_type = $parent->post_type;
		$item->menu_item_parent = $parent->ID;
		$item->filter = $parent->filter;
		$item->type_label = $parent->type_label;
		$item->object = $parent->object;
		$item->target = $parent->target;
		$item->attr_title = '';
		$item->current_item_ancestor = false;
		$item->current_item_parent = false;
		
		//  Set the item classes
		$classes = $parent->classes;
		$menuEl = array_search('current-menu-item', $classes);
		if (!$current && ($menuEl > -1)) {
			unset($classes[$menuEl]);	
		} else if ($current && ($menuEl == -1)) {
			array_push($classes, 'current-menu-item');	
		}
		$pageEl = array_search('current_page_item', $classes);
		if (!$current && ($pageEl > -1)) {
			unset($classes[$pageEl]);	
		} else if ($current && ($pageEl == -1)) {
			array_push($classes, 'current_page_item');	
		}
		if (!($num % 2)) {
			array_push($classes, $this->plugin->add_namespace('alt', '-'));	
		}
		array_push($classes, $this->plugin->add_namespace(array('api', 'id', $id), '-'));
		$item->classes = $classes;
		
		//  Add subcategory specific attributes
		$item->ID = $this->menuItemId;
		$item->post_name = $this->menuItemId;
		$item->guid = $url;
		$item->menu_order = $num;
		$item->db_id = $this->menuItemId;
		$item->object_id = $this->menuItemId;
		$item->url = $url;
		$item->title = $title;
		$item->current = $current;
		
		//  Add the subcategory item to the submenu
		array_push($this->items, $item);
		
		return $item;
		
	}

}
