<?php
/**
 * Plugin Name: ADK Theme Options
 * Description: Extends theme functionality by adding custom post type and taxonomy support
 * Version: 0.1.4
 * Author: ADK Group
 **/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


class adk_theme_options {

    public function __construct() {


    		add_action( 'init', array( &$this, 'default_post_types') );
        add_action( 'init', array( &$this, 'register_post_types_and_taxonomies') );
        // add_action( 'init', array( &$this, 'register_assets') );
        add_action( 'admin_menu', array( &$this, 'add_admin_menu') );
        add_action( 'add_meta_boxes', array($this, 'create_meta_boxes') );
        add_action( 'save_post', array($this, 'save_post') );
        add_action( 'manage_posts_custom_column', array($this, 'custom_post_admin_columns'), 10, 2 );
        add_action( 'manage_posts_custom_column', array($this, 'custom_taxonomy_admin_columns'), 10, 2 );

        add_filter( 'manage_edit-custom_post_tax_columns', array($this, 'edit_taxonomy_custom_columns') );
        add_filter( 'manage_edit-custom_post_columns', array($this, 'edit_post_custom_columns') );

    }


    public function default_post_types() {

  		// Register custom post type that will hold the user-created post types
  		$labels = array(
  			'name' => __( 'Custom Post Types', 'theme_options' ),
  			'singular_name' => __( 'Custom Post Type', 'theme_options' ),
  			'add_new' => __( 'Add New' , 'theme_options' ),
  			'add_new_item' => __( 'Add New Custom Post Type' , 'theme_options' ),
  			'edit_item' =>  __( 'Edit Custom Post Type' , 'theme_options' ),
  			'new_item' => __( 'New Custom Post Type' , 'theme_options' ),
  			'view_item' => __('View Custom Post Type', 'theme_options' ),
  			'search_items' => __('Search Custom Post Types', 'theme_options' ),
  			'not_found' =>  __('No Custom Post Types found', 'theme_options' ),
  			'not_found_in_trash' => __('No Custom Post Types found in Trash', 'theme_options' ),
  		);

  		register_post_type( 'custom_post', array(
  			'labels' => $labels,
  			'public' => false,
  			'show_ui' => true,
  			'_builtin' =>  false,
  			'capability_type' => 'page',
  			'hierarchical' => false,
  			'rewrite' => false,
  			'query_var' => 'custom_post',
  			'supports' => array(
  				'title'
  			),
  			'show_in_menu' => false,
  		));

      // Register custom post type that will hold the user-created taxonomies
  		$labels = array(
  			'name' => __( 'Custom Taxonomies', 'theme_options' ),
  			'singular_name' => __( 'Custom Taxonomy', 'theme_options' ),
  			'add_new' => __( 'Add New' , 'theme_options' ),
  			'add_new_item' => __( 'Add New Custom Taxonomy' , 'theme_options' ),
  			'edit_item' =>  __( 'Edit Custom Taxonomy' , 'theme_options' ),
  			'new_item' => __( 'New Custom Taxonomy' , 'theme_options' ),
  			'view_item' => __('View Custom Taxonomy', 'theme_options' ),
  			'search_items' => __('Search Custom Taxonomies', 'theme_options' ),
  			'not_found' =>  __('No Custom Taxonomies found', 'theme_options' ),
  			'not_found_in_trash' => __('No Custom Taxonomies found in Trash', 'theme_options' ),
  		);

  		register_post_type( 'custom_post_tax', array(
  			'labels' => $labels,
  			'public' => false,
  			'show_ui' => true,
  			'_builtin' =>  false,
  			'capability_type' => 'page',
  			'hierarchical' => false,
  			'rewrite' => false,
  			'query_var' => 'custom_post_tax',
  			'supports' => array(
  				'title'
  			),
  			'show_in_menu' => false,
  		));

  	} // END function default_post_types()



    public function add_admin_menu() {

      // add_menu_page( 'Theme Options', 'Theme Options', 'manage_options', 'theme-options');
      add_submenu_page('options-general.php', 'Custom Post Types', 'Custom Post Types', 'manage_options', 'edit.php?post_type=custom_post' );
      add_submenu_page('options-general.php', 'Custom Taxonomies', 'Custom Taxonomies', 'manage_options', 'edit.php?post_type=custom_post_tax' );
    }


    public function register_post_types_and_taxonomies() {

      // Initiate arrays to hold user data
      $user_post_types = array();
      $user_taxs = array();

      // Get user custom post types
      $post_types_arg = array(
        'numberposts' 	   => -1,
        'post_type' 	   => 'custom_post',
        'post_status'      => 'publish',
        'suppress_filters' => false,
      );
      $post_types = get_posts( $post_types_arg );

      // create array of post meta
      if( $post_types ) {
        foreach( $post_types as $post_type ) {
          $custom_post_meta = get_post_meta( $post_type->ID, '', true );

          // text
  				$custom_post_name                = ( array_key_exists( 'custom_post_name', $custom_post_meta ) && $custom_post_meta['custom_post_name'][0] ? esc_html( $custom_post_meta['custom_post_name'][0] ) : 'no_name' );
  				$custom_post_label               = ( array_key_exists( 'custom_post_label', $custom_post_meta ) && $custom_post_meta['custom_post_label'][0] ? esc_html( $custom_post_meta['custom_post_label'][0] ) : $custom_post_name );
  				$custom_post_singular_name       = ( array_key_exists( 'custom_post_singular_name', $custom_post_meta ) && $custom_post_meta['custom_post_singular_name'][0] ? esc_html( $custom_post_meta['custom_post_singular_name'][0] ) : $custom_post_label );
  				$custom_post_description         = ( array_key_exists( 'custom_post_description', $custom_post_meta ) && $custom_post_meta['custom_post_description'][0] ? $custom_post_meta['custom_post_description'][0] : '' );
  				$custom_post_icon                = ( array_key_exists( 'custom_post_icon', $custom_post_meta ) && $custom_post_meta['custom_post_icon'][0] ? $custom_post_meta['custom_post_icon'][0] : false );
  				$custom_post_custom_rewrite_slug = ( array_key_exists( 'custom_post_custom_rewrite_slug', $custom_post_meta ) && $custom_post_meta['custom_post_custom_rewrite_slug'][0] ? esc_html( $custom_post_meta['custom_post_custom_rewrite_slug'][0] ) : $custom_post_name );
  				$custom_post_menu_position       = ( array_key_exists( 'custom_post_menu_position', $custom_post_meta ) && $custom_post_meta['custom_post_menu_position'][0] ? (int) $custom_post_meta['custom_post_menu_position'][0] : null );

  				// dropdown
  				$custom_post_public              = ( array_key_exists( 'custom_post_public', $custom_post_meta ) && $custom_post_meta['custom_post_public'][0] == '1' ? true : false );
  				$custom_post_show_ui             = ( array_key_exists( 'custom_post_show_ui', $custom_post_meta ) && $custom_post_meta['custom_post_show_ui'][0] == '1' ? true : false );
  				$custom_post_has_archive         = ( array_key_exists( 'custom_post_has_archive', $custom_post_meta ) && $custom_post_meta['custom_post_has_archive'][0] == '1' ? true : false );
  				$custom_post_exclude_from_search = ( array_key_exists( 'custom_post_exclude_from_search', $custom_post_meta ) && $custom_post_meta['custom_post_exclude_from_search'][0] == '1' ? true : false );
  				$custom_post_capability_type     = ( array_key_exists( 'custom_post_capability_type', $custom_post_meta ) && $custom_post_meta['custom_post_capability_type'][0] ? $custom_post_meta['custom_post_capability_type'][0] : 'post' );
  				$custom_post_hierarchical        = ( array_key_exists( 'custom_post_hierarchical', $custom_post_meta ) && $custom_post_meta['custom_post_hierarchical'][0] == '1' ? true : false );
  				$custom_post_rewrite             = ( array_key_exists( 'custom_post_rewrite', $custom_post_meta ) && $custom_post_meta['custom_post_rewrite'][0] == '1' ? true : false );
  				$custom_post_withfront           = ( array_key_exists( 'custom_post_withfront', $custom_post_meta ) && $custom_post_meta['custom_post_withfront'][0] == '1' ? true : false );
  				$custom_post_feeds               = ( array_key_exists( 'custom_post_feeds', $custom_post_meta ) && $custom_post_meta['custom_post_feeds'][0] == '1' ? true : false );
  				$custom_post_pages               = ( array_key_exists( 'custom_post_pages', $custom_post_meta ) && $custom_post_meta['custom_post_pages'][0] == '1' ? true : false );
  				$custom_post_query_var           = ( array_key_exists( 'custom_post_query_var', $custom_post_meta ) && $custom_post_meta['custom_post_query_var'][0] == '1' ? true : false );
  				$custom_post_show_in_menu        = ( array_key_exists( 'custom_post_show_in_menu', $custom_post_meta ) && $custom_post_meta['custom_post_show_in_menu'][0] == '1' ? true : false );

  				// checkbox
  				$custom_post_supports            = ( array_key_exists( 'custom_post_supports', $custom_post_meta ) && $custom_post_meta['custom_post_supports'][0] ? $custom_post_meta['custom_post_supports'][0] : 'a:2:{i:0;s:5:"title";i:1;s:6:"editor";}' );
  				$custom_post_builtin_taxonomies  = ( array_key_exists( 'custom_post_builtin_taxonomies', $custom_post_meta ) && $custom_post_meta['custom_post_builtin_taxonomies'][0] ? $custom_post_meta['custom_post_builtin_taxonomies'][0] : 'a:0:{}' );

  				$custom_post_rewrite_options     = array();
  				if ( $custom_post_rewrite )      { $custom_post_rewrite_options['slug'] = _x( $custom_post_custom_rewrite_slug, 'URL Slug', 'theme_options' ); }
  				if ( $custom_post_withfront )    { $custom_post_rewrite_options['with_front'] = $custom_post_withfront; }
  				if ( $custom_post_feeds )        { $custom_post_rewrite_options['feeds'] = $custom_post_feeds; }
  				if ( $custom_post_pages )        { $custom_post_rewrite_options['pages'] = $custom_post_pages; }

          // Populate user custom post types array
  				$user_post_types[] = array(
  					'custom_post_id'                  => $post_type->ID,
  					'custom_post_name'                => $custom_post_name,
  					'custom_post_label'               => $custom_post_label,
  					'custom_post_singular_name'       => $custom_post_singular_name,
  					'custom_post_description'         => $custom_post_description,
  					'custom_post_icon'                => $custom_post_icon,
  					'custom_post_custom_rewrite_slug' => $custom_post_custom_rewrite_slug,
  					'custom_post_menu_position'       => $custom_post_menu_position,
  					'custom_post_public'              => (bool) $custom_post_public,
  					'custom_post_show_ui'             => (bool) $custom_post_show_ui,
  					'custom_post_has_archive'         => (bool) $custom_post_has_archive,
  					'custom_post_exclude_from_search' => (bool) $custom_post_exclude_from_search,
  					'custom_post_capability_type'     => $custom_post_capability_type,
  					'custom_post_hierarchical'        => (bool) $custom_post_hierarchical,
  					'custom_post_rewrite'             => $custom_post_rewrite_options,
  					'custom_post_query_var'           => (bool) $custom_post_query_var,
  					'custom_post_show_in_menu'        => (bool) $custom_post_show_in_menu,
  					'custom_post_supports'            => unserialize( $custom_post_supports ),
  					'custom_post_builtin_taxonomies'  => unserialize( $custom_post_builtin_taxonomies ),
  				);

  				// Loop through and register custom post types
  				if ( is_array( $user_post_types ) ) {
  					foreach ($user_post_types as $user_post_type) {

  						$labels = array(
  							'name'                => __( $user_post_type['custom_post_label'], 'theme_options' ),
  							'singular_name'       => __( $user_post_type['custom_post_singular_name'], 'theme_options' ),
  							'add_new'             => __( 'Add New' , 'theme_options' ),
  							'add_new_item'        => __( 'Add New ' . $user_post_type['custom_post_singular_name'] , 'theme_options' ),
  							'edit_item'           => __( 'Edit ' . $user_post_type['custom_post_singular_name'] , 'theme_options' ),
  							'new_item'            => __( 'New ' . $user_post_type['custom_post_singular_name'] , 'theme_options' ),
  							'view_item'           => __( 'View ' . $user_post_type['custom_post_singular_name'], 'theme_options' ),
  							'search_items'        => __( 'Search ' . $user_post_type['custom_post_label'], 'theme_options' ),
  							'not_found'           => __( 'No ' .  $user_post_type['custom_post_label'] . ' found', 'theme_options' ),
  							'not_found_in_trash'  => __( 'No ' .  $user_post_type['custom_post_label'] . ' found in Trash', 'theme_options' ),
  						);

  						$args = array(
  							'labels'              => $labels,
  							'description'         => $user_post_type['custom_post_description'],
  							'menu_icon'           => $user_post_type['custom_post_icon'],
  							'rewrite'             => $user_post_type['custom_post_rewrite'],
  							'menu_position'       => $user_post_type['custom_post_menu_position'],
  							'public'              => $user_post_type['custom_post_public'],
  							'show_ui'             => $user_post_type['custom_post_show_ui'],
  							'has_archive'         => $user_post_type['custom_post_has_archive'],
  							'exclude_from_search' => $user_post_type['custom_post_exclude_from_search'],
  							'capability_type'     => $user_post_type['custom_post_capability_type'],
  							'hierarchical'        => $user_post_type['custom_post_hierarchical'],
  							'show_in_menu'        => $user_post_type['custom_post_show_in_menu'],
  							'query_var'           => $user_post_type['custom_post_query_var'],
  							'publicly_queryable'  => true,
  							'_builtin'            => false,
  							'supports'            => $user_post_type['custom_post_supports'],
  							'taxonomies'          => $user_post_type['custom_post_builtin_taxonomies']
  						);

  						if( $user_post_type['custom_post_name'] != 'no_name' )
  							register_post_type( $user_post_type['custom_post_name'], $args);
  					}
  				}
  			}
  		}

      // Get user custom taxonomies
      $get_tax = array(
        'numberposts' 	   => -1,
        'post_type' 	   => 'custom_post_tax',
        'post_status'      => 'publish',
        'suppress_filters' => false,
      );
      $taxonomies = get_posts( $get_tax );

      // create array of post meta
      if( $taxonomies ) {
        foreach( $taxonomies as $tax ) {
          $meta = get_post_meta( $tax->ID, '', true );

          // text
          $tax_name                = ( array_key_exists( 'tax_name', $meta ) && $meta['tax_name'][0] ? esc_html( $meta['tax_name'][0] ) : 'no_name' );
          $tax_label               = ( array_key_exists( 'tax_label', $meta ) && $meta['tax_label'][0] ? esc_html( $meta['tax_label'][0] ) : $tax_name );
          $tax_singular_name       = ( array_key_exists( 'tax_singular_name', $meta ) && $meta['tax_singular_name'][0] ? esc_html( $meta['tax_singular_name'][0] ) : $tax_label );
          $tax_custom_rewrite_slug = ( array_key_exists( 'tax_custom_rewrite_slug', $meta ) && $meta['tax_custom_rewrite_slug'][0] ? esc_html( $meta['tax_custom_rewrite_slug'][0] ) : $tax_name );

          // dropdown
          $tax_show_ui             = ( array_key_exists( 'tax_show_ui', $meta ) && $meta['tax_show_ui'][0] == '1' ? true : false );
          $tax_hierarchical        = ( array_key_exists( 'tax_hierarchical', $meta ) && $meta['tax_hierarchical'][0] == '1' ? true : false );
          $tax_rewrite             = ( array_key_exists( 'tax_rewrite', $meta ) && $meta['tax_rewrite'][0] == '1' ? array( 'slug' => _x( $tax_custom_rewrite_slug, 'URL Slug', 'theme_options' ) ) : false );
          $tax_query_var           = ( array_key_exists( 'tax_query_var', $meta ) && $meta['tax_query_var'][0] == '1' ? true : false );

          // checkbox
          $tax_post_types          = ( array_key_exists( 'tax_post_types', $meta ) && $meta['tax_post_types'][0] ? $meta['tax_post_types'][0] : 'a:0:{}' );

          $user_taxs[] = array(
            'tax_id'                  => $tax->ID,
            'tax_name'                => $tax_name,
            'tax_label'               => $tax_label,
            'tax_singular_name'       => $tax_singular_name,
            'tax_custom_rewrite_slug' => $tax_custom_rewrite_slug,
            'tax_show_ui'             => (bool) $tax_show_ui,
            'tax_hierarchical'        => (bool) $tax_hierarchical,
            'tax_rewrite'             => $tax_rewrite,
            'tax_query_var'           => (bool) $tax_query_var,
            'tax_builtin_taxonomies'  => unserialize( $tax_post_types ),
          );

          // register custom post types
          if ( is_array( $user_taxs ) ) {
            foreach ($user_taxs as $user_tax) {

              $labels = array(
                'name'                       => _x( $user_tax['tax_label'], 'taxonomy general name', 'theme_options' ),
                'singular_name'              => _x( $user_tax['tax_singular_name'], 'taxonomy singular name' ),
                'search_items'               => __( 'Search ' . $user_tax['tax_label'], 'theme_options' ),
                'popular_items'              => __( 'Popular ' . $user_tax['tax_label'], 'theme_options' ),
                'all_items'                  => __( 'All ' . $user_tax['tax_label'], 'theme_options' ),
                'parent_item'                => __( 'Parent ' . $user_tax['tax_singular_name'], 'theme_options' ),
                'parent_item_colon'          => __( 'Parent ' . $user_tax['tax_singular_name'], 'theme_options' . ':' ),
                'edit_item'                  => __( 'Edit ' . $user_tax['tax_singular_name'], 'theme_options' ),
                'update_item'                => __( 'Update ' . $user_tax['tax_singular_name'], 'theme_options' ),
                'add_new_item'               => __( 'Add New ' . $user_tax['tax_singular_name'], 'theme_options' ),
                'new_item_name'              => __( 'New ' . $user_tax['tax_singular_name'], 'theme_options' . ' Name' ),
                'separate_items_with_commas' => __( 'Seperate ' . $user_tax['tax_label'], 'theme_options' . ' with commas' ),
                'add_or_remove_items'        => __( 'Add or remove ' . $user_tax['tax_label'], 'theme_options' ),
                'choose_from_most_used'      => __( 'Choose from the most used ' . $user_tax['tax_label'], 'theme_options' ),
                'menu_name'                  => __( 'All ' . $user_tax['tax_label'], 'theme_options' )
              );

              $args = array(
                'label'               => $user_tax['tax_label'],
                'labels'              => $labels,
                'rewrite'             => $user_tax['tax_rewrite'],
                'show_ui'             => $user_tax['tax_show_ui'],
                'hierarchical'        => $user_tax['tax_hierarchical'],
                'query_var'           => $user_tax['tax_query_var'],
              );

              if( $user_tax['tax_name'] != 'no_name' )
                register_taxonomy( $user_tax['tax_name'], $user_tax['tax_builtin_taxonomies'], $args );
            }
          }
        }
      }

    } // END function register_post_types_and_taxonomies()



    // Needs work
    public function create_meta_boxes() {

      // Add meta boxes to options page
      add_meta_box(
        'post_type_options',
        __( 'Options', 'theme-options' ),
        array($this, 'custom_post_type_meta_box'),
        'custom_post',
        'advanced',
        'high'
      );

      add_meta_box(
        'tax_options',
        __( 'Options', 'theme-options' ),
        array($this, 'custom_tax_meta_box'),
        'custom_post_tax',
        'advanced',
        'high'
      );

    }

    public function custom_post_type_meta_box( $post ) {

      // get post meta values
      $values = get_post_custom( $post->ID );

      // text fields
  		$custom_post_name                          = isset( $values['custom_post_name'] ) ? esc_attr( $values['custom_post_name'][0] ) : '';
  		$custom_post_label                         = isset( $values['custom_post_label'] ) ? esc_attr( $values['custom_post_label'][0] ) : '';
  		$custom_post_singular_name                 = isset( $values['custom_post_singular_name'] ) ? esc_attr( $values['custom_post_singular_name'][0] ) : '';
  		$custom_post_description                   = isset( $values['custom_post_description'] ) ? esc_attr( $values['custom_post_description'][0] ) : '';
  		// $custom_post_icon                          = isset( $values['custom_post_icon'] ) ? esc_attr( $values['custom_post_icon'][0] ) : '';
  		$custom_post_custom_rewrite_slug           = isset( $values['custom_post_custom_rewrite_slug'] ) ? esc_attr( $values['custom_post_custom_rewrite_slug'][0] ) : '';
  		$custom_post_menu_position                 = isset( $values['custom_post_menu_position'] ) ? esc_attr( $values['custom_post_menu_position'][0] ) : '';

  		// select fields
  		$custom_post_public                        = isset( $values['custom_post_public'] ) ? esc_attr( $values['custom_post_public'][0] ) : '';
  		$custom_post_show_ui                       = isset( $values['custom_post_show_ui'] ) ? esc_attr( $values['custom_post_show_ui'][0] ) : '';
  		$custom_post_has_archive                   = isset( $values['custom_post_has_archive'] ) ? esc_attr( $values['custom_post_has_archive'][0] ) : '';
  		$custom_post_exclude_from_search           = isset( $values['custom_post_exclude_from_search'] ) ? esc_attr( $values['custom_post_exclude_from_search'][0] ) : '';
  		$custom_post_capability_type               = isset( $values['custom_post_capability_type'] ) ? esc_attr( $values['custom_post_capability_type'][0] ) : '';
  		$custom_post_hierarchical                  = isset( $values['custom_post_hierarchical'] ) ? esc_attr( $values['custom_post_hierarchical'][0] ) : '';
  		$custom_post_rewrite                       = isset( $values['custom_post_rewrite'] ) ? esc_attr( $values['custom_post_rewrite'][0] ) : '';
  		$custom_post_withfront                     = isset( $values['custom_post_withfront'] ) ? esc_attr( $values['custom_post_withfront'][0] ) : '';
  		$custom_post_feeds                         = isset( $values['custom_post_feeds'] ) ? esc_attr( $values['custom_post_feeds'][0] ) : '';
  		$custom_post_pages                         = isset( $values['custom_post_pages'] ) ? esc_attr( $values['custom_post_pages'][0] ) : '';
  		$custom_post_query_var                     = isset( $values['custom_post_query_var'] ) ? esc_attr( $values['custom_post_query_var'][0] ) : '';
  		$custom_post_show_in_menu                  = isset( $values['custom_post_show_in_menu'] ) ? esc_attr( $values['custom_post_show_in_menu'][0] ) : '';

  		// checkbox fields
  		$custom_post_supports                      = isset( $values['custom_post_supports'] ) ? unserialize( $values['custom_post_supports'][0] ) : array();
  		$custom_post_supports_title                = ( isset( $values['custom_post_supports'] ) && in_array( 'title', $custom_post_supports ) ? 'title' : '' );
  		$custom_post_supports_editor               = ( isset( $values['custom_post_supports'] ) && in_array( 'editor', $custom_post_supports ) ? 'editor' : '' );
  		$custom_post_supports_excerpt              = ( isset( $values['custom_post_supports'] ) && in_array( 'excerpt', $custom_post_supports ) ? 'excerpt' : '' );
  		$custom_post_supports_trackbacks           = ( isset( $values['custom_post_supports'] ) && in_array( 'trackbacks', $custom_post_supports ) ? 'trackbacks' : '' );
  		$custom_post_supports_custom_fields        = ( isset( $values['custom_post_supports'] ) && in_array( 'custom-fields', $custom_post_supports ) ? 'custom-fields' : '' );
  		$custom_post_supports_comments             = ( isset( $values['custom_post_supports'] ) && in_array( 'comments', $custom_post_supports ) ? 'comments' : '' );
  		$custom_post_supports_revisions            = ( isset( $values['custom_post_supports'] ) && in_array( 'revisions', $custom_post_supports ) ? 'revisions' : '' );
  		$custom_post_supports_featured_image       = ( isset( $values['custom_post_supports'] ) && in_array( 'thumbnail', $custom_post_supports ) ? 'thumbnail' : '' );
  		$custom_post_supports_author               = ( isset( $values['custom_post_supports'] ) && in_array( 'author', $custom_post_supports ) ? 'author' : '' );
  		$custom_post_supports_page_attributes      = ( isset( $values['custom_post_supports'] ) && in_array( 'page-attributes', $custom_post_supports ) ? 'page-attributes' : '' );
  		$custom_post_supports_post_formats         = ( isset( $values['custom_post_supports'] ) && in_array( 'post-formats', $custom_post_supports ) ? 'post-formats' : '' );

  		$custom_post_builtin_taxonomies            = isset( $values['custom_post_builtin_taxonomies'] ) ? unserialize( $values['custom_post_builtin_taxonomies'][0] ) : array();
  		$custom_post_builtin_taxonomies_categories = ( isset( $values['custom_post_builtin_taxonomies'] ) && in_array( 'category', $custom_post_builtin_taxonomies ) ? 'category' : '' );
  		$custom_post_builtin_taxonomies_tags       = ( isset( $values['custom_post_builtin_taxonomies'] ) && in_array( 'post_tag', $custom_post_builtin_taxonomies ) ? 'post_tag' : '' );

  		// nonce
  		wp_nonce_field( 'custom_post_meta_box_nonce_action', 'custom_post_meta_box_nonce_field' );

  		// set defaults if new Custom Post Type is being created
  		global $pagenow;
  		$custom_post_supports_title                = $pagenow === 'post-new.php' ? 'title' : $custom_post_supports_title;
  		$custom_post_supports_editor               = $pagenow === 'post-new.php' ? 'editor' : $custom_post_supports_editor;
  		$custom_post_supports_excerpt              = $pagenow === 'post-new.php' ? 'excerpt' : $custom_post_supports_excerpt;
  		?>
  		<table class="custom_post">
  			<tr>
  				<td class="label">
  					<label for="custom_post_name"><span class="required">*</span> <?php _e( 'Custom Post Type Name', 'theme_options' ); ?></label>
  					<p><?php _e( 'The post type name. Used to retrieve custom post type content. Must be all in lower-case and without any spaces.', 'theme_options' ); ?></p>
  					<p><em><?php _e( '(Example: product)', 'theme_options' ); ?></em></p>
  				</td>
  				<td>
  					<input type="text" name="custom_post_name" id="custom_post_name" class="widefat" tabindex="1" value="<?php echo $custom_post_name; ?>" />
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="custom_post_label"><?php _e( 'Label', 'theme_options' ); ?></label>
  					<p><?php _e( 'A plural descriptive name for the post type.', 'theme_options' ); ?></p>
            <p><em><?php _e( '(Example: Products)', 'theme_options' ); ?></em></p>
  				</td>
  				<td>
  					<input type="text" name="custom_post_label" id="custom_post_label" class="widefat" tabindex="2" value="<?php echo $custom_post_label; ?>" />
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="custom_post_singular_name"><?php _e( 'Singular Name', 'theme_options' ); ?></label>
  					<p><?php _e( 'A singular descriptive name for the post type.', 'theme_options' ); ?></p>
            <p><em><?php _e( '(Example: Product)', 'theme_options' ); ?></em></p>
  				</td>
  				<td>
  					<input type="text" name="custom_post_singular_name" id="custom_post_singular_name" class="widefat" tabindex="3" value="<?php echo $custom_post_singular_name; ?>" />
  				</td>
  			</tr>
  			<tr>
  				<td class="label top">
  					<label for="custom_post_description"><?php _e( 'Description', 'theme_options' ); ?></label>
  					<p><?php _e( 'A short descriptive summary of what the post type is.', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<textarea name="custom_post_description" id="custom_post_description" class="widefat" tabindex="4" rows="4"><?php echo $custom_post_description; ?></textarea>
  				</td>
  			</tr>
  			<tr>
  				<td colspan="2" class="section">
  					<h3><?php _e( 'Visibility', 'theme_options' ); ?></h3>
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="custom_post_public"><?php _e( 'Public', 'theme_options' ); ?></label>
  					<p><?php _e( 'Whether a post type is intended to be used publicly either via the admin interface or by front-end users.', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<select name="custom_post_public" id="custom_post_public" tabindex="5">
  						<option value="1" <?php selected( $custom_post_public, '1' ); ?>><?php _e( 'True', 'theme_options' ); ?> (<?php _e( 'default', 'theme_options' ); ?>)</option>
  						<option value="0" <?php selected( $custom_post_public, '0' ); ?>><?php _e( 'False', 'theme_options' ); ?></option>
  					</select>
  				</td>
  			</tr>
  			<tr>
  				<td colspan="2" class="section">
  					<h3><?php _e( 'Rewrite Options', 'theme_options' ); ?></h3>
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="custom_post_rewrite"><?php _e( 'Rewrite', 'theme_options' ); ?></label>
  					<p><?php _e( 'Triggers the handling of rewrites for this post type.', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<select name="custom_post_rewrite" id="custom_post_rewrite" tabindex="6">
  						<option value="1" <?php selected( $custom_post_rewrite, '1' ); ?>><?php _e( 'True', 'theme_options' ); ?> (<?php _e( 'default', 'theme_options' ); ?>)</option>
  						<option value="0" <?php selected( $custom_post_rewrite, '0' ); ?>><?php _e( 'False', 'theme_options' ); ?></option>
  					</select>
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="custom_post_withfront"><?php _e( 'With Front', 'theme_options' ); ?></label>
  					<p><?php _e( 'Should the permastruct be prepended with the front base.', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<select name="custom_post_withfront" id="custom_post_withfront" tabindex="7">
  						<option value="1" <?php selected( $custom_post_withfront, '1' ); ?>><?php _e( 'True', 'theme_options' ); ?> (<?php _e( 'default', 'theme_options' ); ?>)</option>
  						<option value="0" <?php selected( $custom_post_withfront, '0' ); ?>><?php _e( 'False', 'theme_options' ); ?></option>
  					</select>
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="custom_post_custom_rewrite_slug"><?php _e( 'Custom Rewrite Slug', 'theme_options' ); ?></label>
  					<p><?php _e( 'Customize the permastruct slug.', 'theme_options' ); ?></p>
  					<p><?php _e( 'Default: [Custom Post Type Name]', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<input type="text" name="custom_post_custom_rewrite_slug" id="custom_post_custom_rewrite_slug" class="widefat" tabindex="8" value="<?php echo $custom_post_custom_rewrite_slug; ?>" />
  				</td>
  			</tr>
  			<tr>
  				<td colspan="2" class="section">
  					<h3><?php _e( 'Front-end Options', 'theme_options' ); ?></h3>
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="custom_post_feeds"><?php _e( 'Feeds', 'theme_options' ); ?></label>
  					<p><?php _e( 'Should a feed permastruct be built for this post type. Defaults to "has_archive" value.', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<select name="custom_post_feeds" id="custom_post_feeds" tabindex="9">
  						<option value="0" <?php selected( $custom_post_feeds, '0' ); ?>><?php _e( 'False', 'theme_options' ); ?> (<?php _e( 'default', 'theme_options' ); ?>)</option>
  						<option value="1" <?php selected( $custom_post_feeds, '1' ); ?>><?php _e( 'True', 'theme_options' ); ?></option>
  					</select>
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="custom_post_pages"><?php _e( 'Pages', 'theme_options' ); ?></label>
  					<p><?php _e( 'Should the permastruct provide for pagination.', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<select name="custom_post_pages" id="custom_post_pages" tabindex="10">
  						<option value="1" <?php selected( $custom_post_pages, '1' ); ?>><?php _e( 'True', 'theme_options' ); ?> (<?php _e( 'default', 'theme_options' ); ?>)</option>
  						<option value="0" <?php selected( $custom_post_pages, '0' ); ?>><?php _e( 'False', 'theme_options' ); ?></option>
  					</select>
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="custom_post_exclude_from_search"><?php _e( 'Exclude From Search', 'theme_options' ); ?></label>
  					<p><?php _e( 'Whether to exclude posts with this post type from front end search results.', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<select name="custom_post_exclude_from_search" id="custom_post_exclude_from_search" tabindex="11">
  						<option value="0" <?php selected( $custom_post_exclude_from_search, '0' ); ?>><?php _e( 'False', 'theme_options' ); ?> (<?php _e( 'default', 'theme_options' ); ?>)</option>
  						<option value="1" <?php selected( $custom_post_exclude_from_search, '1' ); ?>><?php _e( 'True', 'theme_options' ); ?></option>
  					</select>
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="custom_post_has_archive"><?php _e( 'Has Archive', 'theme_options' ); ?></label>
  					<p><?php _e( 'Enables post type archives.', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<select name="custom_post_has_archive" id="custom_post_has_archive" tabindex="12">
  						<option value="0" <?php selected( $custom_post_has_archive, '0' ); ?>><?php _e( 'False', 'theme_options' ); ?> (<?php _e( 'default', 'theme_options' ); ?>)</option>
  						<option value="1" <?php selected( $custom_post_has_archive, '1' ); ?>><?php _e( 'True', 'theme_options' ); ?></option>
  					</select>
  				</td>
  			</tr>
  			<tr>
  				<td colspan="2" class="section">
  					<h3><?php _e( 'Admin Menu Options', 'theme_options' ); ?></h3>
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="custom_post_show_ui"><?php _e( 'Show UI', 'theme_options' ); ?></label>
  					<p><?php _e( 'Whether to generate a default UI for managing this post type in the admin.', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<select name="custom_post_show_ui" id="custom_post_show_ui" tabindex="13">
  						<option value="1" <?php selected( $custom_post_show_ui, '1' ); ?>><?php _e( 'True', 'theme_options' ); ?> (<?php _e( 'default', 'theme_options' ); ?>)</option>
  						<option value="0" <?php selected( $custom_post_show_ui, '0' ); ?>><?php _e( 'False', 'theme_options' ); ?></option>
  					</select>
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="custom_post_menu_position"><?php _e( 'Menu Position', 'theme_options' ); ?></label>
  					<p><?php _e( 'The position in the menu order the post type should appear. "Show in Menu" must be true.', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<input type="text" name="custom_post_menu_position" id="custom_post_menu_position" class="widefat" tabindex="14" value="<?php echo $custom_post_menu_position; ?>" />
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="custom_post_show_in_menu"><?php _e( 'Show in Menu', 'theme_options' ); ?></label>
  					<p><?php _e( 'Where to show the post type in the admin menu. "Show UI" must be true.', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<select name="custom_post_show_in_menu" id="custom_post_show_in_menu" tabindex="15">
  						<option value="1" <?php selected( $custom_post_show_in_menu, '1' ); ?>><?php _e( 'True', 'theme_options' ); ?> (<?php _e( 'default', 'theme_options' ); ?>)</option>
  						<option value="0" <?php selected( $custom_post_show_in_menu, '0' ); ?>><?php _e( 'False', 'theme_options' ); ?></option>
  					</select>
  				</td>
  			</tr>
  			<tr>
  				<td colspan="2" class="section">
  					<h3><?php _e( 'Wordpress Integration', 'theme_options' ); ?></h3>
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="custom_post_capability_type"><?php _e( 'Capability Type', 'theme_options' ); ?></label>
  					<p><?php _e( 'The post type to use to build the read, edit, and delete capabilities.', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<select name="custom_post_capability_type" id="custom_post_capability_type" tabindex="16">
  						<option value="post" <?php selected( $custom_post_capability_type, 'post' ); ?>><?php _e( 'Post', 'theme_options' ); ?> (<?php _e( 'default', 'theme_options' ); ?>)</option>
  						<option value="page" <?php selected( $custom_post_capability_type, 'page' ); ?>><?php _e( 'Page', 'theme_options' ); ?></option>
  					</select>
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="custom_post_hierarchical"><?php _e( 'Hierarchical', 'theme_options' ); ?></label>
  					<p><?php _e( 'Whether the post type is hierarchical (e.g. page).', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<select name="custom_post_hierarchical" id="custom_post_hierarchical" tabindex="17">
  						<option value="0" <?php selected( $custom_post_hierarchical, '0' ); ?>><?php _e( 'False', 'theme_options' ); ?> (<?php _e( 'default', 'theme_options' ); ?>)</option>
  						<option value="1" <?php selected( $custom_post_hierarchical, '1' ); ?>><?php _e( 'True', 'theme_options' ); ?></option>
  					</select>
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="custom_post_query_var"><?php _e( 'Query Var', 'theme_options' ); ?></label>
  					<p><?php _e( 'Sets the query_var key for this post type.', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<select name="custom_post_query_var" id="custom_post_query_var" tabindex="18">
  						<option value="1" <?php selected( $custom_post_query_var, '1' ); ?>><?php _e( 'True', 'theme_options' ); ?> (<?php _e( 'default', 'theme_options' ); ?>)</option>
  						<option value="0" <?php selected( $custom_post_query_var, '0' ); ?>><?php _e( 'False', 'theme_options' ); ?></option>
  					</select>
  				</td>
  			</tr>
  			<tr>
  				<td class="label top">
  					<label for="custom_post_supports"><?php _e( 'Supports', 'theme_options' ); ?></label>
  					<p><?php _e( 'Adds the respective meta boxes when creating content for this Custom Post Type.', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<input type="checkbox" tabindex="19" name="custom_post_supports[]" id="custom_post_supports_title" value="title" <?php checked( $custom_post_supports_title, 'title' ); ?> /> <label for="custom_post_supports_title"><?php _e( 'Title', 'theme_options' ); ?> <span class="default">(<?php _e( 'default', 'theme_options' ); ?>)</span></label><br />
  					<input type="checkbox" tabindex="20" name="custom_post_supports[]" id="custom_post_supports_editor" value="editor" <?php checked( $custom_post_supports_editor, 'editor' ); ?> /> <label for="custom_post_supports_editor"><?php _e( 'Editor', 'theme_options' ); ?> <span class="default">(<?php _e( 'default', 'theme_options' ); ?>)</span></label><br />
  					<input type="checkbox" tabindex="21" name="custom_post_supports[]" id="custom_post_supports_excerpt" value="excerpt" <?php checked( $custom_post_supports_excerpt, 'excerpt' ); ?> /> <label for="custom_post_supports_excerpt"><?php _e( 'Excerpt', 'theme_options' ); ?> <span class="default">(<?php _e( 'default', 'theme_options' ); ?>)</span></label><br />
  					<input type="checkbox" tabindex="22" name="custom_post_supports[]" id="custom_post_supports_trackbacks" value="trackbacks" <?php checked( $custom_post_supports_trackbacks, 'trackbacks' ); ?> /> <label for="custom_post_supports_trackbacks"><?php _e( 'Trackbacks', 'theme_options' ); ?></label><br />
  					<input type="checkbox" tabindex="23" name="custom_post_supports[]" id="custom_post_supports_custom_fields" value="custom-fields" <?php checked( $custom_post_supports_custom_fields, 'custom-fields' ); ?> /> <label for="custom_post_supports_custom_fields"><?php _e( 'Custom Fields', 'theme_options' ); ?></label><br />
  					<input type="checkbox" tabindex="24" name="custom_post_supports[]" id="custom_post_supports_comments" value="comments" <?php checked( $custom_post_supports_comments, 'comments' ); ?> /> <label for="custom_post_supports_comments"><?php _e( 'Comments', 'theme_options' ); ?></label><br />
  					<input type="checkbox" tabindex="25" name="custom_post_supports[]" id="custom_post_supports_revisions" value="revisions" <?php checked( $custom_post_supports_revisions, 'revisions' ); ?> /> <label for="custom_post_supports_revisions"><?php _e( 'Revisions', 'theme_options' ); ?></label><br />
  					<input type="checkbox" tabindex="26" name="custom_post_supports[]" id="custom_post_supports_featured_image" value="thumbnail" <?php checked( $custom_post_supports_featured_image, 'thumbnail' ); ?> /> <label for="custom_post_supports_featured_image"><?php _e( 'Featured Image', 'theme_options' ); ?></label><br />
  					<input type="checkbox" tabindex="27" name="custom_post_supports[]" id="custom_post_supports_author" value="author" <?php checked( $custom_post_supports_author, 'author' ); ?> /> <label for="custom_post_supports_author"><?php _e( 'Author', 'theme_options' ); ?></label><br />
  					<input type="checkbox" tabindex="28" name="custom_post_supports[]" id="custom_post_supports_page_attributes" value="page-attributes" <?php checked( $custom_post_supports_page_attributes, 'page-attributes' ); ?> /> <label for="custom_post_supports_page_attributes"><?php _e( 'Page Attributes', 'theme_options' ); ?></label><br />
  					<input type="checkbox" tabindex="29" name="custom_post_supports[]" id="custom_post_supports_post_formats" value="post-formats" <?php checked( $custom_post_supports_post_formats, 'post-formats' ); ?> /> <label for="custom_post_supports_post_formats"><?php _e( 'Post Formats', 'theme_options' ); ?></label><br />
  				</td>
  			</tr>
  			<tr>
  				<td class="label top">
  					<label for="custom_post_builtin_taxonomies"><?php _e( 'Built-in Taxonomies', 'theme_options' ); ?></label>
  					<p><?php _e( '', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<input type="checkbox" tabindex="30" name="custom_post_builtin_taxonomies[]" id="custom_post_builtin_taxonomies_categories" value="category" <?php checked( $custom_post_builtin_taxonomies_categories, 'category' ); ?> /> <label for="custom_post_builtin_taxonomies_categories"><?php _e( 'Categories', 'theme_options' ); ?></label><br />
  					<input type="checkbox" tabindex="31" name="custom_post_builtin_taxonomies[]" id="custom_post_builtin_taxonomies_tags" value="post_tag" <?php checked( $custom_post_builtin_taxonomies_tags, 'post_tag' ); ?> /> <label for="custom_post_builtin_taxonomies_tags"><?php _e( 'Tags', 'theme_options' ); ?></label><br />
  				</td>
  			</tr>
  		</table>

  		<?php

    } // END custom_post_type_meta_box meta_box()

    public function custom_tax_meta_box( $post ) {

  		// get post meta values
  		$values = get_post_custom( $post->ID );

  		// text fields
  		$tax_name                          = isset( $values['tax_name'] ) ? esc_attr( $values['tax_name'][0] ) : '';
  		$tax_label                         = isset( $values['tax_label'] ) ? esc_attr( $values['tax_label'][0] ) : '';
  		$tax_singular_name                 = isset( $values['tax_singular_name'] ) ? esc_attr( $values['tax_singular_name'][0] ) : '';
  		$tax_custom_rewrite_slug           = isset( $values['tax_custom_rewrite_slug'] ) ? esc_attr( $values['tax_custom_rewrite_slug'][0] ) : '';

  		// select fields
  		$tax_show_ui                       = isset( $values['tax_show_ui'] ) ? esc_attr( $values['tax_show_ui'][0] ) : '';
  		$tax_hierarchical                  = isset( $values['tax_hierarchical'] ) ? esc_attr( $values['tax_hierarchical'][0] ) : '';
  		$tax_rewrite                       = isset( $values['tax_rewrite'] ) ? esc_attr( $values['tax_rewrite'][0] ) : '';
  		$tax_query_var                     = isset( $values['tax_query_var'] ) ? esc_attr( $values['tax_query_var'][0] ) : '';

  		// checkbox fields
  		$tax_supports                      = isset( $values['tax_supports'] ) ? unserialize( $values['tax_supports'][0] ) : array();
  		$tax_supports_title                = ( isset( $values['tax_supports'] ) && in_array( 'title', $supports ) ? 'title' : '' );
  		$tax_supports_editor               = ( isset( $values['tax_supports'] ) && in_array( 'editor', $supports ) ? 'editor' : '' );
  		$tax_supports_excerpt              = ( isset( $values['tax_supports'] ) && in_array( 'excerpt', $supports ) ? 'excerpt' : '' );
  		$tax_supports_trackbacks           = ( isset( $values['tax_supports'] ) && in_array( 'trackbacks', $supports ) ? 'trackbacks' : '' );
  		$tax_supports_custom_fields        = ( isset( $values['tax_supports'] ) && in_array( 'custom-fields', $supports ) ? 'custom-fields' : '' );
  		$tax_supports_comments             = ( isset( $values['tax_supports'] ) && in_array( 'comments', $supports ) ? 'comments' : '' );
  		$tax_supports_revisions            = ( isset( $values['tax_supports'] ) && in_array( 'revisions', $supports ) ? 'revisions' : '' );
  		$tax_supports_featured_image       = ( isset( $values['tax_supports'] ) && in_array( 'thumbnail', $supports ) ? 'thumbnail' : '' );
  		$tax_supports_author               = ( isset( $values['tax_supports'] ) && in_array( 'author', $supports ) ? 'author' : '' );
  		$tax_supports_page_attributes      = ( isset( $values['tax_supports'] ) && in_array( 'page-attributes', $supports ) ? 'page-attributes' : '' );
  		$tax_supports_post_formats         = ( isset( $values['tax_supports'] ) && in_array( 'post-formats', $supports ) ? 'post-formats' : '' );

  		$tax_post_types                    = isset( $values['tax_post_types'] ) ? unserialize( $values['tax_post_types'][0] ) : array();
  		$tax_post_types_post               = ( isset( $values['tax_post_types'] ) && in_array( 'post', $tax_post_types ) ? 'post' : '' );
  		$tax_post_types_page               = ( isset( $values['tax_post_types'] ) && in_array( 'page', $tax_post_types ) ? 'page' : '' );

  		// nonce
  		wp_nonce_field( 'custom_post_meta_box_nonce_action', 'custom_post_meta_box_nonce_field' );
  		?>
  		<table class="custom_post">
  			<tr>
  				<td class="label">
  					<label for="tax_name"><span class="required">*</span> <?php _e( 'Custom Taxonomy Name', 'theme_options' ); ?></label>
  					<p><?php _e( 'The taxonomy name. Used to retrieve custom taxonomy content.', 'theme_options' ); ?></p>
  					<p><em><?php _e( '(Example: school)', 'theme_options' ); ?></em></p>
  				</td>
  				<td>
  					<input type="text" name="tax_name" id="tax_name" class="widefat" tabindex="1" value="<?php echo $tax_name; ?>" />
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="tax_label"><?php _e( 'Label', 'theme_options' ); ?></label>
  					<p><?php _e( 'A plural descriptive name for the taxonomy.', 'theme_options' ); ?></p>
            <p><em><?php _e( '(Example: Schools)', 'theme_options' ); ?></em></p>
  				</td>
  				<td>
  					<input type="text" name="tax_label" id="tax_label" class="widefat" tabindex="2" value="<?php echo $tax_label; ?>" />
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="tax_singular_name"><?php _e( 'Singular Name', 'theme_options' ); ?></label>
  					<p><?php _e( 'A singular descriptive name for the taxonomy.', 'theme_options' ); ?></p>
            <p><em><?php _e( '(Example: School)', 'theme_options' ); ?></em></p>
  				</td>
  				<td>
  					<input type="text" name="tax_singular_name" id="tax_singular_name" class="widefat" tabindex="3" value="<?php echo $tax_singular_name; ?>" />
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="tax_show_ui"><?php _e( 'Show UI', 'theme_options' ); ?></label>
  					<p><?php _e( 'Whether to generate a default UI for managing this taxonomy in the admin.', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<select name="tax_show_ui" id="tax_show_ui" tabindex="4">
  						<option value="1" <?php selected( $tax_show_ui, '1' ); ?>><?php _e( 'True', 'theme_options' ); ?> (<?php _e( 'default', 'theme_options' ); ?>)</option>
  						<option value="0" <?php selected( $tax_show_ui, '0' ); ?>><?php _e( 'False', 'theme_options' ); ?></option>
  					</select>
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="tax_hierarchical"><?php _e( 'Hierarchical', 'theme_options' ); ?></label>
  					<p><?php _e( 'Whether the taxonomy is hierarchical (e.g. page).', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<select name="tax_hierarchical" id="tax_hierarchical" tabindex="5">
  						<option value="0" <?php selected( $tax_hierarchical, '0' ); ?>><?php _e( 'False', 'theme_options' ); ?> (<?php _e( 'default', 'theme_options' ); ?>)</option>
  						<option value="1" <?php selected( $tax_hierarchical, '1' ); ?>><?php _e( 'True', 'theme_options' ); ?></option>
  					</select>
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="tax_rewrite"><?php _e( 'Rewrite', 'theme_options' ); ?></label>
  					<p><?php _e( 'Triggers the handling of rewrites for this taxonomy.', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<select name="tax_rewrite" id="tax_rewrite" tabindex="6">
  						<option value="1" <?php selected( $tax_rewrite, '1' ); ?>><?php _e( 'True', 'theme_options' ); ?> (<?php _e( 'default', 'theme_options' ); ?>)</option>
  						<option value="0" <?php selected( $tax_rewrite, '0' ); ?>><?php _e( 'False', 'theme_options' ); ?></option>
  					</select>
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="tax_custom_rewrite_slug"><?php _e( 'Custom Rewrite Slug', 'theme_options' ); ?></label>
  					<p><?php _e( 'Customize the permastruct slug.', 'theme_options' ); ?></p>
  					<p><?php _e( 'Default: [Custom Taxonomy Name]', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<input type="text" name="tax_custom_rewrite_slug" id="tax_custom_rewrite_slug" class="widefat" tabindex="7" value="<?php echo $tax_custom_rewrite_slug; ?>" />
  				</td>
  			</tr>
  			<tr>
  				<td class="label">
  					<label for="tax_query_var"><?php _e( 'Query Var', 'theme_options' ); ?></label>
  					<p><?php _e( 'Sets the query_var key for this taxonomy.', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<select name="tax_query_var" id="tax_query_var" tabindex="8">
  						<option value="1" <?php selected( $tax_query_var, '1' ); ?>><?php _e( 'True', 'theme_options' ); ?> (<?php _e( 'default', 'theme_options' ); ?>)</option>
  						<option value="0" <?php selected( $tax_query_var, '0' ); ?>><?php _e( 'False', 'theme_options' ); ?></option>
  					</select>
  				</td>
  			</tr>
  			<tr>
  				<td class="label top">
  					<label for="tax_post_types"><?php _e( 'Post Types', 'theme_options' ); ?></label>
  					<p><?php _e( '', 'theme_options' ); ?></p>
  				</td>
  				<td>
  					<input type="checkbox" tabindex="9" name="tax_post_types[]" id="tax_post_types_post" value="post" <?php checked( $tax_post_types_post, 'post' ); ?> /> <label for="tax_post_types_post"><?php _e( 'Posts', 'theme_options' ); ?></label><br />
  					<input type="checkbox" tabindex="10" name="tax_post_types[]" id="tax_post_types_page" value="page" <?php checked( $tax_post_types_page, 'page' ); ?> /> <label for="tax_post_types_page"><?php _e( 'Pages', 'theme_options' ); ?></label><br />
  					<?php
  						$post_types = get_post_types( array( 'public' => true, '_builtin' => false ) );
  						$i = 10;
  						foreach ( $post_types as $post_type ) {
  							$checked = in_array( $post_type, $tax_post_types )  ? 'checked="checked"' : '';
  							?>
  							<input type="checkbox" tabindex="<?php echo $i; ?>" name="tax_post_types[]" id="tax_post_types_<?php echo $post_type; ?>" value="<?php echo $post_type; ?>" <?php echo $checked; ?> /> <label for="tax_post_types_<?php echo $post_type; ?>"><?php echo ucfirst( $post_type ); ?></label><br />
  							<?php
  							$i++;
  						}
  					?>
  				</td>
  			</tr>
  		</table>
  		<?php

  	} // END function custom_tax_meta_box()

    public function save_post( $post_id ) {

      // verify if this is an auto save routine.
      // If it is our form has not been submitted, so we dont want to do anything
      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

      // if our nonce isn't there, or we can't verify it, bail
      if( !isset( $_POST['custom_post_meta_box_nonce_field'] ) || !wp_verify_nonce( $_POST['custom_post_meta_box_nonce_field'], 'custom_post_meta_box_nonce_action' ) ) return;

      // update custom post type meta values
  		if( isset($_POST['custom_post_name']) )
  			update_post_meta( $post_id, 'custom_post_name', sanitize_text_field( str_replace( ' ', '', $_POST['custom_post_name'] ) ) );

  		if( isset($_POST['custom_post_label']) )
  			update_post_meta( $post_id, 'custom_post_label', sanitize_text_field( $_POST['custom_post_label'] ) );

  		if( isset($_POST['custom_post_singular_name']) )
  			update_post_meta( $post_id, 'custom_post_singular_name', sanitize_text_field( $_POST['custom_post_singular_name'] ) );

  		if( isset($_POST['custom_post_description']) )
  			update_post_meta( $post_id, 'custom_post_description', esc_textarea( $_POST['custom_post_description'] ) );

  		// if( isset($_POST['custom_post_icon']) )
  		// 	update_post_meta( $post_id, 'custom_post_icon', esc_textarea( $_POST['custom_post_icon'] ) );

  		if( isset( $_POST['custom_post_public'] ) )
  			update_post_meta( $post_id, 'custom_post_public', esc_attr( $_POST['custom_post_public'] ) );

  		if( isset( $_POST['custom_post_show_ui'] ) )
  			update_post_meta( $post_id, 'custom_post_show_ui', esc_attr( $_POST['custom_post_show_ui'] ) );

  		if( isset( $_POST['custom_post_has_archive'] ) )
  			update_post_meta( $post_id, 'custom_post_has_archive', esc_attr( $_POST['custom_post_has_archive'] ) );

  		if( isset( $_POST['custom_post_exclude_from_search'] ) )
  			update_post_meta( $post_id, 'custom_post_exclude_from_search', esc_attr( $_POST['custom_post_exclude_from_search'] ) );

  		if( isset( $_POST['custom_post_capability_type'] ) )
  			update_post_meta( $post_id, 'custom_post_capability_type', esc_attr( $_POST['custom_post_capability_type'] ) );

  		if( isset( $_POST['custom_post_hierarchical'] ) )
  			update_post_meta( $post_id, 'custom_post_hierarchical', esc_attr( $_POST['custom_post_hierarchical'] ) );

  		if( isset( $_POST['custom_post_rewrite'] ) )
  			update_post_meta( $post_id, 'custom_post_rewrite', esc_attr( $_POST['custom_post_rewrite'] ) );

  		if( isset( $_POST['custom_post_withfront'] ) )
  			update_post_meta( $post_id, 'custom_post_withfront', esc_attr( $_POST['custom_post_withfront'] ) );

  		if( isset( $_POST['custom_post_feeds'] ) )
  			update_post_meta( $post_id, 'custom_post_feeds', esc_attr( $_POST['custom_post_feeds'] ) );

  		if( isset( $_POST['custom_post_pages'] ) )
  			update_post_meta( $post_id, 'custom_post_pages', esc_attr( $_POST['custom_post_pages'] ) );

  		if( isset($_POST['custom_post_custom_rewrite_slug']) )
  			update_post_meta( $post_id, 'custom_post_custom_rewrite_slug', sanitize_text_field( $_POST['custom_post_custom_rewrite_slug'] ) );

  		if( isset( $_POST['custom_post_query_var'] ) )
  			update_post_meta( $post_id, 'custom_post_query_var', esc_attr( $_POST['custom_post_query_var'] ) );

  		if( isset($_POST['custom_post_menu_position']) )
  			update_post_meta( $post_id, 'custom_post_menu_position', sanitize_text_field( $_POST['custom_post_menu_position'] ) );

  		if( isset( $_POST['custom_post_show_in_menu'] ) )
  			update_post_meta( $post_id, 'custom_post_show_in_menu', esc_attr( $_POST['custom_post_show_in_menu'] ) );

  		$custom_post_supports = isset( $_POST['custom_post_supports'] ) ? $_POST['custom_post_supports'] : array();
  			update_post_meta( $post_id, 'custom_post_supports', $custom_post_supports );

  		$custom_post_builtin_taxonomies = isset( $_POST['custom_post_builtin_taxonomies'] ) ? $_POST['custom_post_builtin_taxonomies'] : array();
  			update_post_meta( $post_id, 'custom_post_builtin_taxonomies', $custom_post_builtin_taxonomies );

      // update taxonomy meta values
      if( isset($_POST['tax_name']) )
        update_post_meta( $post_id, 'tax_name', sanitize_text_field( str_replace( ' ', '', $_POST['tax_name'] ) ) );

      if( isset($_POST['tax_label']) )
        update_post_meta( $post_id, 'tax_label', sanitize_text_field( $_POST['tax_label'] ) );

      if( isset($_POST['tax_singular_name']) )
        update_post_meta( $post_id, 'tax_singular_name', sanitize_text_field( $_POST['tax_singular_name'] ) );

      if( isset( $_POST['tax_show_ui'] ) )
        update_post_meta( $post_id, 'tax_show_ui', esc_attr( $_POST['tax_show_ui'] ) );

      if( isset( $_POST['tax_hierarchical'] ) )
        update_post_meta( $post_id, 'tax_hierarchical', esc_attr( $_POST['tax_hierarchical'] ) );

      if( isset( $_POST['tax_rewrite'] ) )
        update_post_meta( $post_id, 'tax_rewrite', esc_attr( $_POST['tax_rewrite'] ) );

      if( isset($_POST['tax_custom_rewrite_slug']) )
        update_post_meta( $post_id, 'tax_custom_rewrite_slug', sanitize_text_field( $_POST['tax_custom_rewrite_slug'] ) );

      if( isset( $_POST['tax_query_var'] ) )
        update_post_meta( $post_id, 'tax_query_var', esc_attr( $_POST['tax_query_var'] ) );

      $tax_post_types = isset( $_POST['tax_post_types'] ) ? $_POST['tax_post_types'] : array();
        update_post_meta( $post_id, 'tax_post_types', $tax_post_types );

    } // END save_post()


    function edit_post_custom_columns( $cols ) {

      $cols = array(
        'cb'                    => '<input type="checkbox" />',
        'title'                 => __( 'Post Type', 'theme_options' ),
        'custom_post_type_name' => __( 'Custom Post Type Name', 'theme_options' ),
        'label'                 => __( 'Label', 'theme_options' ),
        'description'           => __( 'Description', 'theme_options' ),
      );
      return $cols;

    } // END edit_post_custom_columns()

    function sort_post_type_columns() {

      return array(
        'title'                 => 'title'
      );

    } // END sort_columns()

    function custom_post_admin_columns( $column, $post_id ) {

      switch ( $column ) {
        case "custom_post_type_name":
          echo get_post_meta( $post_id, 'custom_post_name', true);
          break;
        case "label":
          echo get_post_meta( $post_id, 'custom_post_label', true);
          break;
        case "description":
          echo get_post_meta( $post_id, 'custom_post_description', true);
          break;
      }

    } // END custom_post_admin_columns()

    function edit_taxonomy_custom_columns ( $cols ) {

      $cols = array(
        'cb'                    => '<input type="checkbox" />',
        'title'                 => __( 'Taxonomy', 'theme_options' ),
        'custom_post_type_name' => __( 'Custom Taxonomy Name', 'theme_options' ),
        'label'                 => __( 'Label', 'theme_options' )
      );
      return $cols;

    } // END edit_taxonomy_custom_columns()

    function sort_taxonomy_type_columns() {

      return array(
        'title'                 => 'title'
      );

    } // END sort_taxonomy_type_columns()

    function custom_taxonomy_admin_columns( $column, $post_id ) {

      switch ( $column ) {
        case "custom_post_type_name":
          echo get_post_meta( $post_id, 'tax_name', true);
          break;
        case "label":
          echo get_post_meta( $post_id, 'tax_label', true);
          break;
      }

    } // END get_taxonomy_custom_columns()

}

$adk_theme_options = new adk_theme_options();
