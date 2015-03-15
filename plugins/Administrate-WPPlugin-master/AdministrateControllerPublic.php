<?php
//  Administrate public controller
class AdministrateControllerPublic extends WPPluginPatternController {
	
	//  Properties
	private $currentFilter = false;
	private $isInBody = false;
	private $pageIsGenerated = false;
	
	//  Run
	public function run() {
		
		//  If the flush cache flag is in the parameters, do it
		if (isset($_GET[$this->plugin->add_namespace('flush_cache')])) {
			$this->plugin->flush_cache();
		}
		
		//  Set special pages for reference
		$this->registrationPage = $this->plugin->get_option('checkout_page', 'checkout');
		$this->coursePage = $this->plugin->get_option('course_page', 'course');
		
		//  Parse the course URL
		$parsed = parse_url(get_permalink($this->coursePage));
		$this->coursesBaseUrl = $parsed['path'];
		add_action('request', array($this, 'parse_course_url'), 5);

		//  Include and initialize checkout widget
		require_once($this->plugin->get_path('/widgets/checkout/AdministrateWidgetCheckout.php'));
		$this->widgets['checkout'] = new AdministrateWidgetCheckout($this->plugin);
		
		//  Include and initialize the event table widget
		require_once($this->plugin->get_path('/widgets/event_table/AdministrateWidgetEventTable.php'));
		$this->widgets['event_table'] = new AdministrateWidgetEventTable($this->plugin);
		
		//  Include and initialize the event listing widget
		require_once($this->plugin->get_path('/widgets/event_list/AdministrateWidgetEventList.php'));
		$this->widgets['event_list'] = new AdministrateWidgetEventList($this->plugin);
		
		//  Include and initialize the event slider widget
		require_once($this->plugin->get_path('/widgets/event_slider/AdministrateWidgetEventSlider.php'));
		$this->widgets['event_slider'] = new AdministrateWidgetEventSlider($this->plugin);
		
		//  Include and initialize the event price widget
		require_once($this->plugin->get_path('/widgets/event_price/AdministrateWidgetEventPrice.php'));
		$this->widgets['event_price'] = new AdministrateWidgetEventPrice($this->plugin);
		
		//  Include and initialize the event widget
		require_once($this->plugin->get_path('/widgets/event/AdministrateWidgetEvent.php'));
		$this->widgets['event'] = new AdministrateWidgetEvent($this->plugin);
		
		//  Include and initialize the event menu widget
		require_once($this->plugin->get_path('/widgets/event_menu/AdministrateWidgetEventMenu.php'));
		$this->widgets['event_menu'] = new AdministrateWidgetEventMenu($this->plugin);
		
		//  Include and initialize the category listing widget
		require_once($this->plugin->get_path('/widgets/category_list/AdministrateWidgetCategoryList.php'));
		$this->widgets['category_list'] = new AdministrateWidgetCategoryList($this->plugin);
		
		//  Include and initialize the category widget
		require_once($this->plugin->get_path('/widgets/category/AdministrateWidgetCategory.php'));
		$this->widgets['category'] = new AdministrateWidgetCategory($this->plugin);
		
		//  Include and initialize the subcategory listing widget
		require_once($this->plugin->get_path('/widgets/subcategory_list/AdministrateWidgetSubcategoryList.php'));
		$this->widgets['subcategory_list'] = new AdministrateWidgetSubcategoryList($this->plugin);
		
		//  Include and initialize the subcategory widget
		require_once($this->plugin->get_path('/widgets/subcategory/AdministrateWidgetSubcategory.php'));
		$this->widgets['subcategory'] = new AdministrateWidgetSubcategory($this->plugin);
		
		//  Include and initialize the course listing widget
		require_once($this->plugin->get_path('/widgets/course_list/AdministrateWidgetCourseList.php'));
		$this->widgets['course_list'] = new AdministrateWidgetCourseList($this->plugin);
		
		//  Include and initialize the course widget
		require_once($this->plugin->get_path('/widgets/course/AdministrateWidgetCourse.php'));
		$this->widgets['course'] = new AdministrateWidgetCourse($this->plugin);
		
		//  Include and initialize the course menu widget
		require_once($this->plugin->get_path('/widgets/course_menu/AdministrateWidgetCourseMenu.php'));
		$this->widgets['course_menu'] = new AdministrateWidgetCourseMenu($this->plugin);
		
		//  Add a content filter that will automatically append the appropriate widget
		add_filter('wp_head', array($this, 'set_content_location'), 9999);
		add_filter('the_content', array($this, 'append_page_widget'));

		//  Add the CSS
		wp_enqueue_style($this->plugin->add_namespace('common'), $this->plugin->get_url('/css/common.css'));
		wp_enqueue_style($this->plugin->add_namespace('public'), $this->plugin->get_url('/css/public.css'));

		//  Add the public JS
		wp_enqueue_script($this->plugin->add_namespace('js'), $this->plugin->get_url('/js/public.js'), array('jquery', 'administrate_checkout', 'administrate_event_price', 'administrate_event_table', 'administrate_event_slider'), false, true);
	
	}
	
	//  Display the price widget widget
	public function display_price_widget(&$event, $fieldName, $defaultCurrency = '', $showCurrencyIndicator = true, $currencyIndicator = 'symbol') {
		echo $this->widgets['event_price']->run($event, $fieldName, $defaultCurrency, $showCurrencyIndicator, $currencyIndicator); 
	}
	
	//  Display the currency selector
	public function display_currency_selector(&$events, $defaultCurrency = '', $fieldName = '') {
		$this->widgets['event_price']->display_selector($events, $defaultCurrency, $fieldName);	
	}
	
	//  Display the currency prices
	public function display_currency_prices(&$event, $pricingBasis = false, $defaultCurrency = '', $showCurrencyIndicator = true, $currencyIndicator = 'symbol') {
		$this->widgets['event_price']->display_prices($event, $pricingBasis, $defaultCurrency, $showCurrencyIndicator, $currencyIndicator);	
	}
	
	//  Get a currency symbol
	public function get_currency_symbol($currency) {
		return $this->widgets['event_price']->get_currency_symbol($currency);	
	}
	
	//  Format a currency
	public function format_currency($amount, $currency, $showCurrencyIndicator = true, $currencyIndicator = 'symbol') {
		return $this->widgets['event_price']->format_currency($amount, $currency, $showCurrencyIndicator, $currencyIndicator);	
	}
	
	//  Set the flag to indicate that we're in the body
	public function set_content_location() {
		$this->isInBody = true;
	}
	
	//  Run the checkout page
	public function append_page_widget($content) {
		
		//  Only proceed if this is the main page query
		if (is_singular() && is_main_query() && $this->isInBody) {
						
			global $post;
			
			//  If this is the checkout page, append checkout widget
			if ($post->ID == $this->registrationPage) {
				$widget = $this->widgets['checkout']->run();
				return $content . $widget;
			
			//  Or if this is the course page, append course widget	
			} else if (($post->ID == $this->coursePage) || in_array($this->coursePage, get_post_ancestors($post->ID))) {
				
				$widget = $this->widgets['course']->run();
				if (
					!$this->page_is_generated() ||
					(
						!isset($_REQUEST[$this->plugin->add_namespace(array('course', 'id'))]) && 
						!isset($_REQUEST[$this->plugin->add_namespace(array('subcategory', 'id'))]) &&
						!isset($_REQUEST[$this->plugin->add_namespace(array('category', 'id'))])
					)
				) {
					return $content . $widget;
				} else {
					return $widget;	
				}
				
			//  Or else just return the content
			} else {
				return $content;	
			}
		
		} else {
			return $content;	
		}
		
	}

	//  Parse the URL
	public function parse_course_url($request) {
		
		global $post;

		//  Only proceed with parsing if the beginning of the request URI matches the course page
		$coursesBaseUrlLength = strlen($this->coursesBaseUrl);
		if (substr($_SERVER['REQUEST_URI'], 0, $coursesBaseUrlLength) == $this->coursesBaseUrl) {
			
			//  Only proceed if a course URL structure is set
			$urlStructure = $this->plugin->get_course_url_structure();
			if ($this->plugin->use_custom_course_urls()) {
				
				//  First check for an exact match in the cached object paths
				require_once($this->plugin->get_path('/AdministrateCourseFilter.php'));
				$filter = new AdministrateCourseFilter(
					$this->plugin,
					array(
						'filter_object_path'	=>	$_SERVER['REQUEST_URI'], 
						'filter_hidden'			=>	0
					)
				);

				//  If the cached path wasn't found, continue parsing
				if (!$filter->exists()) {
				
					//  Get the parts of the URL after the base courses URL
					$urlSubstr = substr($_SERVER['REQUEST_URI'], $coursesBaseUrlLength);
					if (substr($urlSubstr, -1) == '/') {
						$urlSubstr = substr($urlSubstr, 0, -1);	
					}
					$urlParts = explode('/', $urlSubstr);
					
					//  Split the structure into parts
					$urlStructureParts = $this->plugin->get_course_url_parts();
					
					//  Loop through URL structure backwards to find the first identifier available
					for ($i = count($urlStructureParts)-1; $i >= 0; --$i) {
					
						//  Only proceed if this part exists in the current URL
						if (isset($urlParts[$i])) {
							
							//  Get the object type based on the URL structure
							$objectType = $urlStructureParts[$i];
							
							//  Get the object SEO string
							$objectUrlString = $urlParts[$i];
							
							//echo $objectType . ' = ' . $objectUrlString . '<br>';
							
							//  Query the filters table for a match
							$filter = new AdministrateCourseFilter(
								$this->plugin, 
								array(
									'filter_object_type'	=>	$objectType, 
									'filter_url_string'		=>	$objectUrlString, 
									'filter_hidden'			=>	0
								)
							);
							
							//  No need to continue looping if there's a match
							if ($filter->exists()) {
								break;
							}
								
						}
						
					}
				
				}
				
				//  If the filter exists, add the REQUEST param and break the loop
				if ($filter->exists()) {
					
					//  Save the REQUEST param and the filter
					$_REQUEST[$this->plugin->add_namespace(array($filter->get_object_type(), 'id'))] = $filter->get_object_id();
					$this->currentFilter = &$filter;
					
					$currentPageObj = get_page_by_path($request['pagename']);

					// We didn't find a real page, or $request['pagename'] was null
					if (!$currentPageObj || $request['pagename'] == null) {
						
						//  Add the page name parameter
						$coursePageObj = get_post($this->coursePage);
						
						//  Modify the WP request data
						$request['pagename'] = $coursePageObj->post_name;
						unset($request['name']);
						unset($request['category_name']);

						/* If wordpress permalink setting only has a single element, such as
						 * /%postname%/, then the request will show a 404, and append the URL
						 * as an attachemnt. We *do* have a page to generate, so remove this data.
						 */
						unset($request['error']);
						unset($request['attachment']);

						//  Handle the YOAST SEO plugin
						$this->yoast_handle_meta();
						
						//  Add the hook to output meta tags
						add_filter('wp_title', array($this, 'set_meta_title'), 20, 2);
						add_action('wp_head', array($this, 'output_meta_tags'), 0);
						
						//  Flag that this page has been automatically generated
						$this->pageIsGenerated = true;
					
					}
					
				}

			//  Otherwise this is definitely a generated page
			} else {

				$this->pageIsGenerated = true;

			}
		
		}

		return $request;
	
	}
	
	//  Filter the meta title
	public function set_meta_title($title, $sep = '|') {
		
		//  If this is the course page, add category / subcategory / course title
		$prepend = '';
		$courseKey = $this->plugin->add_namespace(array('course', 'id'));
		if (isset($_REQUEST[$courseKey])) {
			$course = AdministrateAPI::get_course_by_code($_REQUEST[$courseKey]);
			$prepend = $course->get_title();
		} else {
			$subcategoryKey = $this->plugin->add_namespace(array('subcategory', 'id'));
			if (isset($_REQUEST[$subcategoryKey])) {
				$subcategory = AdministrateAPI::get_subcategory($_REQUEST[$subcategoryKey]);
				$prepend = $subcategory->get_name();
			} else {
				$categoryKey = $this->plugin->add_namespace(array('category', 'id'));
				if (isset($_REQUEST[$categoryKey])) {
					$category = AdministrateAPI::get_category($_REQUEST[$categoryKey]);
					$prepend = $category->get_name();
				}
			}
		}
		if (!empty($prepend)) {
			$title = $prepend;
		}
		
		return $title;
	
	}
	
	//  Set meta description
	public function set_meta_description($str) {
		return $this->currentFilter->get_description();
	}
	
	//  Output custom meta tags
	public function output_meta_tags() {
		$keywords = $this->currentFilter->get_keywords();
		$description = $this->currentFilter->get_description();
		require_once($this->plugin->get_path('/views/public/meta_tags.php'));
	}
	
	//  Handle YOAST meta
	public function yoast_handle_meta() {
		
		//  Prevent the description from showing
		add_filter('wpseo_metadesc', array($this, 'yoast_disable_meta'), 20);
		add_filter('wpseo_metakey', array($this, 'yoast_disable_meta'), 20);
		
		//  Modify opengraph meta
		add_filter('wpseo_opengraph_title', array($this, 'set_meta_title'), 20);
		add_filter('wpseo_opengraph_desc', array($this, 'set_meta_description'), 20);
		add_filter('wpseo_opengraph_url', array($this, 'yoast_set_canonical'), 20);
		
		//  Modify Twitter meta
		add_filter('wpseo_twitter_title', array($this, 'set_meta_title'), 20);
		add_filter('wpseo_twitter_description', array($this, 'set_meta_description'), 20);
		
		//  Hijack the canonical string
		add_filter('wpseo_canonical', array($this, 'yoast_set_canonical'), 20);
		
	}
	
	//  Disable YOAST meta
	public function yoast_disable_meta($str) {
		return false;
	}
	
	//  Set YOAST canonical
	public function yoast_set_canonical($url) {
		return 'http://' . $_SERVER['HTTP_HOST'] . $this->coursesBaseUrl . $this->currentFilter->get_url_string() . '/';	
	}
	
	//  Whether or not the current page is automatically generated
	public function page_is_generated() {
		return $this->pageIsGenerated;	
	}
	
}
