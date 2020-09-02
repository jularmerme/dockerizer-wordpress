<?php
/*
|--------------------------------------------------------------------
| Theme functions - Add your custom functions here
|--------------------------------------------------------------------
*/

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Global Options',
		'menu_title'	=> 'Global Options',
		'menu_slug' 	=> 'global-options',
		'capability'	=> 'manage_options',
		'redirect'		=> false
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Submenu #1',
		'menu_title'	=> 'Submenu #1',
		'parent_slug'	=> 'global-options',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Submenu #2',
		'menu_title'	=> 'Submenu #2',
		'parent_slug'	=> 'global-options',
	));
	
}

add_image_size( 'FHD', '1920', '1080', false );
add_image_size( 'custom', 1200, 500, false );

function meks_time_ago() {
	return human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ).' '.__( 'ago' );
}

