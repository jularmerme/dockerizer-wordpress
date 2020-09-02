<?php
/*
Plugin Name: ADK Lazy Load
Plugin URI: https://adkgroup.com/
Description: Load content on demand through Ajax
Version: 1.0
Author: Diego Rojas
Author URI: drojas@adkgroup.com
License: ADK
*/

// Security Line
defined('ABSPATH') or die('You can not access here');

// Require Composer Autoload
if(file_exists(dirname(__File__) . '/vendor/autoload.php')){
    require_once dirname(__File__) . '/vendor/autoload.php';
}

// include namespaces
use ADKll_Inc\Activate;
use ADKll_Inc\Deactivate;
use ADKll_Inc\BaseController;

// Init services
if(class_exists('ADKll_Inc\\Init')){
    ADKll_Inc\Init::register_services();
}

// Class for the Plugin
class AdkLazyLoad extends BaseController
{
    function activatePlugin(){
        Activate::activatePlugin();
    }
    
    function DeactivatePlugin(){
        Deactivate::deactivatePlugin();
    }
    
    function loadMorePost_callback() {
        if(!DOING_AJAX){
            return;
        }
        $varsq = get_search_query();
        check_ajax_referer('load_more_posts', 'security');

        global $template_params;
        $paged = $_POST['page'];
        $load_pagination = ($_POST['load_pagination']) ? $_POST['load_pagination'] : "";
        $query = ($_POST['query']) ? $_POST['query'] : "";
        $post_type = ($_POST['post_type']) ? explode(',', $_POST['post_type']) : "";
        $post_status = ($_POST['post_status']) ? explode(',', $_POST['post_status']) : "";
        $wp_post_per_page = get_option('posts_per_page');
        $posts_per_page = ($_POST['posts_per_page'] && is_numeric($_POST['posts_per_page'])) ? $_POST['posts_per_page'] : $wp_post_per_page;
        $posts_first_page = ($_POST['posts_first_page'] && is_numeric($_POST['posts_first_page'])) ? $_POST['posts_first_page'] : $posts_per_page;
        $template = ($_POST['template']) ? $_POST['template'] : "";
        $no_results_template = ($_POST['no_results']) ? $_POST['no_results'] : "";
        $taxonomies = ($_POST['tax_query']) ? explode(';', html_entity_decode($_POST['tax_query'])) : array();
        $tax_relation = ($_POST['tax_relation']) ? $_POST['tax_relation'] : "";
        $metaqueries = ($_POST['meta_query']) ? explode(';', html_entity_decode($_POST['meta_query'])) : array();
        $meta_relation = ($_POST['meta_relation']) ? $_POST['meta_relation'] : "";
        $year = ($_POST['year']) ? html_entity_decode($_POST['year']) : "";
        $month = ($_POST['month']) ? html_entity_decode($_POST['month']) : "";
        $day = ($_POST['day']) ? html_entity_decode($_POST['day']) : "";
        $post_in = ($_POST['post_in']) ? explode(',', $_POST['post_in']) : "";
        $post_title = ($_POST['post_title']) ? $_POST['post_title'] : "";
        $post_title_like = ($_POST['post_title_like']) ? $_POST['post_title_like'] : "";
        $tag = ($_POST['tag']) ? $_POST['tag'] : "";
        $orderBy_params = ($_POST['orderby']) ? explode(',', $_POST['orderby']) : array();
        $order_meta_key = ($_POST['order_meta_key']) ? $_POST['order_meta_key'] : "";
        $display_query = ($_POST['display_query']) ? $_POST['display_query'] : "";
        $use_elasticPress = ($_POST['use_elasticPress']) ? $_POST['use_elasticPress'] : "false";
        $wp_acf_name = get_option( 'acf_name' );
        $acf_name = ($_POST['acf_name']) ? $_POST['acf_name'] : "";
        $post_id = ($_PSOT['post_id']) ? $_POST['post_id'] : "";

        $args = array();
        if( $use_elasticPress ) {
          $args['ep_integrate'] = $use_elasticPress;
        }
        if(count($post_type)){
            $args['post_type'] = $post_type;
        }
        if(count($post_status)){
            $args['post_status'] = $post_status;
        }
        if($year){
            $args['date_query']['year'] = $year;
        }
        if($month){
            $args['date_query']['month'] = $month;
        }
        if($day){
            $args['date_query']['day'] = $day;
        }
        if(count($taxonomies)){
            $tax_query = array();
            if($tax_relation && count($taxonomies)>1){
                $tax_query['relation'] = $tax_relation;
            }
            foreach($taxonomies as $taxonomy){
                $taxFormed = array();
                $taxParts = explode('|', $taxonomy);
                if(count($taxParts) >= 3){
                    $terms = (count(explode(',', $taxParts[2])) == 1) ? $taxParts[2] : explode(',', $taxParts[2]);
                    $taxFormed['taxonomy'] = $taxParts[0];
                    $taxFormed['field'] = $taxParts[1];
                    $taxFormed['terms'] = $terms;
                    $taxFormed['operator'] = isset($taxParts[3]) ? $taxParts[3] : 'IN';
                    $tax_query[] = $taxFormed;
                }
            }
            $args['tax_query'] = $tax_query;
        }
        if(count($metaqueries)){
            $meta_query = array();
            if($meta_relation && count($metaqueries)>1){
                $meta_query['relation'] = $meta_relation;
            }
            foreach($metaqueries as $metaquery){
                $metaFormed = array();
                $metaParts = explode('|', $metaquery);
                $value = (count(explode(',', $metaParts[1])) == 1) ? $metaParts[1] : explode(',', $metaParts[1]);
                if(count($metaParts) >= 3){
                    $metaFormed['key'] = $metaParts[0];
                    $metaFormed['value'] = $value;
                    $metaFormed['compare'] = $metaParts[2];
                    if(array_key_exists(3, $metaParts) && $metaParts[3]){
                        $metaFormed['type'] = $metaParts[3];
                    }
                    $meta_query[] = $metaFormed;
                }
            }
            $args['meta_query'] = $meta_query;
        }
        if($query){
            $args['s'] = $query;
        }
        $args['posts_per_page'] = ($paged == 1) ? $posts_first_page : $posts_per_page;

        $offsetValue = (($paged - 2) * $posts_per_page) + $posts_first_page;
        $offsetValue = $args['offset'] = ($paged == 1) ? 0 : $offsetValue;

        if($posts_per_page == $posts_first_page){
            $args['paged'] = $paged;
        }
        else{
            $args['offset'] = $offsetValue;
        }

        if($post_in){
            $args['post__in'] = $post_in;
        }

        if($post_title){
            $args['post_title'] = $post_title;
        }

        if($post_title_like){
            $args['post_title_like'] = $post_title_like;
            add_filter( 'posts_where', function ( $where, &$wp_query ) {
                    global $wpdb;
                    if ( $search_term = $wp_query->get( 'post_title_like' ) ) {
                        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . $wpdb->esc_like( $search_term ) . '%\'';
                    }
                    return $where;
                }, 10, 2
            );
        }
        if($tag){
            $tags = explode(',', $tag);
            if(count($tags) > 1){
                $args['tag__in'] = $tags;   // ?????
            }
            else{
                $args['tag'] = $tag;
            }
        }
        
        $orderBy = array();
        foreach($orderBy_params as $orderBy_param){
            $orderBy_couple = explode('|', $orderBy_param);
            $ascDesc = 'DESC';
            if(array_key_exists(1, $orderBy_couple) && $orderBy_couple[1]){
                $ascDesc = $orderBy_couple[1];
            }
            $field = $orderBy_couple[0];
            if($field){
                $orderBy[$field] = $ascDesc;
            }
        }
        if($orderBy){
            $args['orderby'] = $orderBy;
        }
        if($order_meta_key){
            $args['meta_key'] = $order_meta_key;
        }
        if($display_query){
            echo "<pre>";
            print_r($args);
            echo "</pre>";
        }
        $my_posts = new WP_Query( $args );
        
        $totalPostsResult = $my_posts->found_posts;

        if( ! $acf_name ) {
          if ( $my_posts->have_posts() ) :
              if($display_query){
                  echo "Results: " . $my_posts->post_count;
              }
              $contRows = 0;
              ?>
              <?php while ( $my_posts->have_posts() ) : $my_posts->the_post(); ?>
                  <?php
                      $contRows++;
                      $template_params['paged'] = $paged;
                      $template_params['contRows'] = $contRows;
                      if($template){
                          $fileTemplateName = get_template_directory() . '/adkll_content-' . $template . '.php';
                          if(file_exists($fileTemplateName)){
                              get_template_part('adkll_content', $template);
                          }
                          else{
                              include($this->plugin_path . "pages/public/adkll_content-basic.php");
                          }
                      }else{
                          include($this->plugin_path . "pages/public/adkll_content-basic.php");
                      }
                  ?>
              <?php endwhile;
              if($load_pagination){
                  $max_num_pages = (ceil(($my_posts->found_posts - $posts_first_page) / $posts_per_page)) + 1;
                  // Styles for classic pagination block
                  $classes['block'] = ($_POST['class_block']) ? $_POST['class_block'] : "";
                  $classes['item'] = ($_POST['class_item']) ? $_POST['class_item'] : "";
                  $classes['link']  = ($_POST['class_link']) ? $_POST['class_link'] : "";
                  $classes['active'] = ($_POST['class_active']) ? $_POST['class_active'] : "";
                  $classes['disabled'] = ($_POST['class_disabled']) ? $_POST['class_disabled'] : "";
                  $this->showPagination($paged, $max_num_pages, $classes);
              }
              ?>
          <?php
          else:
              if($no_results_template){
                  $fileTemplateNameNoResults = get_template_directory() . '/adkll_content-' . $no_results_template . '.php';
                  if(file_exists($fileTemplateNameNoResults)){
                      get_template_part('adkll_content', $no_results_template);
                  }
                  else{
                      include($this->plugin_path . "pages/public/adkll_content-no-results.php");
                  }
              }
              else{
                  echo 'No Results Found';
              }
          endif;
        } else { 
          echo 'Custom Field code should be here : ' . $acf_name . ' ' . $post_id;
        }

        if(($offsetValue + $my_posts->post_count) >= $totalPostsResult){
            echo "<span class='adkll-theLastPage'></span>";
        }
        wp_reset_postdata();
        wp_die();

    }

    function showPagination($paged, $max_num_pages, $classes){
        if($max_num_pages){
            $pag_class_block = $classes['block'];
            $pag_class_item = $classes['item'];
            $pag_class_link = $classes['link'];
            $pag_class_active = $classes['active'];
            $pag_class_disabled = $classes['disabled'];
            include($this->plugin_path . "pages/public/pagination.php");
        }
    }
}

if(class_exists('AdkLazyLoad')){
    $adkLazyLoad = new AdkLazyLoad();
}

/**
* The code that runs during plugin activation
*/
register_activation_hook(__FILE__, array($adkLazyLoad, 'activatePlugin'));

/**
* The code that runs during plugin activation
*/
register_deactivation_hook(__FILE__, array($adkLazyLoad, 'deactivatePlugin'));

add_action('wp_ajax_load_posts_by_ajax', array($adkLazyLoad, 'loadMorePost_callback'));
add_action('wp_ajax_nopriv_load_posts_by_ajax', array($adkLazyLoad, 'loadMorePost_callback'));