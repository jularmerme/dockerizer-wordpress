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

function get_taxonomy_template_type( $taxonomy_slug ) {
  if($taxonomy_slug !== ''){
    global $base;
    get_template_part("$base/pages/taxonomy_" .$taxonomy_slug);
  }
}

function filter_post_type_link( $link, $post ) { 
	if ( 'vehicles' !=$post->post_type )
		return link;

	$terms = get_the_terms( get_the_ID(), 'brand_model');
	if($terms) {
		foreach($terms as $term){
			$taxonomy_id = 'brand_model_' . $term->term_id; 
			$type = get_field('taxonomy_type', $taxonomy_id);
			if('brand' == $type) {
				$brand = $term->slug;
			} elseif('model' == $type) {
				$model = $term->slug;
			} elseif('year' == $type) {
				$year = $term->slug;
			}
		}
	}
	$slug = $brand . '/' . $model . '/' . $year;
	
	$link = str_replace( '%brand_model%', $slug, $link ); 
	return $link; 
}

add_filter('post_type_link', 'filter_post_type_link', 10, 2);

function rewrite_rules() {
	add_rewrite_rule( 'vehicles/page/?([0-9]{1,})/?$', 'index.php?post_type=vehicles&paged=$matches[1]', 'top' );
	add_rewrite_rule( 'vehicles/(.+)/page/?([0-9]{1,})/?$', 'index.php?bran_model=$matches[2]&paged=$matches[3]', 'top' );
	add_rewrite_rule( 'vehicles/?$', 'index.php?post_type=vehicles', 'top' );
}

add_action('init', 'rewrite_rules');
