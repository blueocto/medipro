<?php

//Setup the function to match the current theme; replace medipro with current theme name
add_action( 'after_setup_theme', 'medipro_theme_setup' );

function medipro_theme_setup() {
	global $content_width;
	$content_width = 894;

	//Add theme support for post thumbnails (featured images).
	add_theme_support( 'post-thumbnails', array( 'post', 'page', 'product', 'tribe_events' ) );

	//Add WooCommerce support
	add_theme_support( 'woocommerce' );
	// Add theme support for Woocommerce thumbnails, and integrate the size settings.
	if( function_exists( 'add_theme_support' ) ) {
		if( get_option( 'woo_post_image_support' ) == 'true' ) {
			add_theme_support( 'post-thumbnails' );
		}
	}

	function medipro_register_menus() {
		register_nav_menus(
			array(
				//'top-menu' => __( 'Top Menu' ),
				'header-menu' => __('Header Menu' ),
				//'footer-menu' => __( 'Footer Menu' )
			)
		);
	}
	add_action( 'init', 'medipro_register_menus' );

	//Add category classes to body tag
	add_filter('body_class','wpa76627_class_names'); 
	function wpa76627_class_names( $classes ) { 
		if( is_singular( 'product' ) ): global $post; 
		foreach( get_the_terms( $post->ID, 'product_cat' ) as $cat ) 
		// maybe you want to make this more unique, like: 
			$classes[] = 'prod-cat-' . $cat->slug; 
		// $classes[] = $cat->slug; 
		endif; 
		return $classes;
	}

	// Remove Woocommerce stylesheets to prevent !important statements everywhere
	add_filter( 'woocommerce_enqueue_styles', '__return_false' );

	// Equipment product page - edit the tabs
	function woo_remove_product_tab($tabs) { 
		unset( $tabs['description'] );      		// Remove the description tab
    	//unset( $tabs['reviews'] ); 					// Remove the reviews tab
    	unset( $tabs['additional_information'] );  	// Remove the additional information tab
 		return $tabs;
 	} 
 	add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tab', 98);


	// Enqueue custom Javascript here using wp_enqueue_script().
	function medipro_load_scripts() {
		// Load the comment reply JavaScript.
		if ( is_singular() && get_option( 'thread_comments' ) && comments_open() ) wp_enqueue_script( 'comment-reply' );
	}


	//Change default post type to say news
	function change_post_menu_label() {
		global $menu;
		global $submenu;
		$menu[5][0] = 'News';
		$submenu['edit.php'][5][0] = 'News';
		$submenu['edit.php'][10][0] = 'Add News';
		$submenu['edit.php'][16][0] = 'News Tags';
		echo '';
	}
	function change_post_object_label() {
		global $wp_post_types;
		$labels = &$wp_post_types['post']->labels;
		$labels->name = 'Latest News';
		$labels->singular_name = 'News';
		$labels->add_new = 'Add News';
		$labels->add_new_item = 'Add News';
		$labels->edit_item = 'Edit News';
		$labels->new_item = 'News';
		$labels->view_item = 'View News';
		$labels->search_items = 'Search News';
		$labels->not_found = 'No News found';
		$labels->not_found_in_trash = 'No News found in Trash';
	}
	add_action( 'init', 'change_post_object_label' );
	add_action( 'admin_menu', 'change_post_menu_label' );

	/*** SECURITY FIXES ***/
	/*Remove generator tags */
	remove_action( 'wp_head', 'wp_generator' ) ; 
	remove_action( 'wp_head', 'wlwmanifest_link' ) ; 
	remove_action( 'wp_head', 'rsd_link' ) ;
	/* Remove HTML from comments */
	add_filter( 'pre_comment_content', 'wp_specialchars' );
	/* Remove the Editor option in Appearance */
	function remove_editor_menu() {
	  remove_action('admin_menu', '_add_themes_utility_last', 101);
	}
	add_action('_admin_menu', 'remove_editor_menu', 1);

	//Hide the upgrade notice in Admin
	add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );

	//Hide plugin updates notification in the dashboard
	function hide_plugin_update_indicator(){
	    global $menu,$submenu;
	    $menu[65][0] = 'Plugins';
	    $submenu['index.php'][10][0] = 'Updates';
	}
	add_action('admin_menu', 'hide_plugin_update_indicator');

	//Remove WooCommerce's annoying update message
	remove_action( 'admin_notices', 'woothemes_updater_notice' );

}// End the function medipro_theme_setup()

?>