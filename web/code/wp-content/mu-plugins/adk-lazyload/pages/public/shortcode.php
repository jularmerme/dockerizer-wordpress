<?php
/*
* Trigger this file on plugin uninstall
* @package AdkLazyLoad
*/

namespace ADKll_Inc;

class Shortcode
{
    public static function getAttributes($atts){
        // ONLY USE LOWERCASE NAMES
        $attributes = shortcode_atts(
            array(
                "id"=>'defId',
                "results_container_id"=>'',
                "load_pagination"=>false,
                "pagination_url"=>false,
                "load_scroll"=>true,
                "load_button"=>true,
                "autoload"=>true,
                "post_type"=>'post',
                "posts_first_page"=>"",
                "posts_per_page"=>get_option('posts_per_page'),
                "post_status"=>'publish',
                "tax_query"=>'',
                "tax_relation"=>'',
                "meta_query"=>'',
                "meta_relation"=>'',
                "orderby"=>'',
                "order_meta_key"=>'',
                "display_query"=>false,
                "load_more_txt"=>'Load more...',
                "template"=>'',
                "no_results"=>'',
                "year"=>'',
                "month"=>'',
                "day"=>'',
                "post_in"=>'',
                "post_not_in"=>'',
                "post_title"=>'',
                "post_title_like"=>'',
                "tag"=>'',
                "class_block"=>'pag_block',
                "class_item"=>'pag_item',
                "class_link"=>'pag_link',
                "class_active"=>'pag_active',
                "class_disabled"=>'pag_disabled',
                "cf_before"=>'',
                "cf_after"=>'',
                "acf_name" => 'cards'
            )
            , $atts
        );
        return $attributes;
    }
    
    public static function getShortcode($attributes, $plugin_url){
        $id = "adkll_" . $attributes["id"];
        $results_container_id = $attributes["results_container_id"];
        $post_type = $attributes["post_type"];
        $posts_first_page = $attributes["posts_first_page"];
        $posts_per_page = $attributes["posts_per_page"];
        $post_status = $attributes["post_status"];
        $tax_query = $attributes["tax_query"];
        $tax_relation = $attributes["tax_relation"];
        $meta_query = $attributes["meta_query"];
        $meta_relation = $attributes["meta_relation"];
        $load_more_txt = $attributes["load_more_txt"];
        $orderby = $attributes["orderby"];
        $order_meta_key = $attributes["order_meta_key"];
        $template = $attributes["template"];
        $no_results = $attributes["no_results"];
        $year = $attributes["year"];
        $month = $attributes["month"];
        $day = $attributes["day"];
        $post_in = $attributes["post_in"];
        $post_not_in = $attributes["post_not_in"];
        $post_title = $attributes["post_title"];
        $post_title_like = $attributes["post_title_like"];
        $tag = $attributes["tag"];
        $load_pagination = strtolower($attributes["load_pagination"]);
        $pagination_url = strtolower($attributes["pagination_url"]);
        $load_scroll = strtolower($attributes["load_scroll"]);
        $load_button = strtolower($attributes["load_button"]);
        $autoload = strtolower($attributes["autoload"]);
        $display_query = strtolower($attributes["display_query"]);

        $class_block = $attributes["class_block"];
        $class_item = $attributes["class_item"];
        $class_link = $attributes["class_link"];
        $class_active = $attributes["class_active"];
        $class_disabled = $attributes["class_disabled"];

        $cf_before = $attributes["cf_before"];
        $cf_after = $attributes["cf_after"];
        
        $load_pagination = ($load_pagination == 'false') ? false : boolval($load_pagination);
        $scrollLoad = ($load_scroll == 'false') ? false : boolval($load_scroll);
        $buttonLoad = ($load_button == 'false') ? false : boolval($load_button);
        $autoload = ($autoload == 'false') ? false : boolval($autoload);
        $pagination_url = ($pagination_url == 'false') ? false : boolval($pagination_url);
        $display_query = ($display_query == 'false') ? false : boolval($display_query);

        $acf_name = 'cards';
        
        if(!$scrollLoad && !$buttonLoad){
            $buttonLoad = true;
        }
        $buttonText = $load_more_txt;
        $classScroll = "";
        $classBtn = "";
        
        if($scrollLoad){
            $classScroll = "adkll-scrollautoload";
        }
        if(!$buttonLoad){
            $classBtn = "adkll-noShow";
        }
        $page = 1;
        $shortcode = "<div id='$id' class='adk-sc-dv'>";
        $shortcode .= "<div class='adkll-sc-loadGif' style='text-align:center'>";
        if(!$load_pagination){
            $shortcode .= "<img src='".$plugin_url."pages/public/images/loader.gif' style='display:initial; width:100px; margin:-20px 0 -20px 0'></div>";
            $shortcode .= "<div class='adkll-sc-loadmore $classScroll'><a href='#' class='adkll-loadmore-btn $classBtn'>$buttonText</a></div>";
        }
        else{
            $shortcode .= "</div>";
        }
        $shortcode .= "</div>";
        $nameObjectShortcodeVar = "objConfigShortCode_".$id;
        $shortcode .= "<script>";
        $shortcode .= "$nameObjectShortcodeVar = {
            id:'$id', 
            results_container_id:'$results_container_id', 
            page:'$page',
            load_pagination:'$load_pagination',
            pagination_url:'$pagination_url',
            autoload:'$autoload',
            post_type:'$post_type',
            posts_first_page: '$posts_first_page',
            posts_per_page: '$posts_per_page',
            post_status: '$post_status',
            tax_query: '$tax_query',
            tax_relation: '$tax_relation',
            meta_query: '$meta_query',
            meta_relation: '$meta_relation',
            orderby: '$orderby',
            order_meta_key: '$order_meta_key',
            display_query: '$display_query',
            template: '$template',
            no_results: '$no_results',
            year: '$year',
            month: '$month',
            day: '$day',
            post_in: '$post_in',
            post_not_in: '$post_not_in',
            post_title: '$post_title',
            post_title_like: '$post_title_like',
            tag: '$tag',
            class_block: '$class_block',
            class_item: '$class_item',
            class_link: '$class_link',
            class_active: '$class_active',
            class_disabled: '$class_disabled',
            cf_before: '$cf_before',
            cf_after: '$cf_after',
            query: '".get_search_query()."',
            url: '".admin_url( 'admin-ajax.php' )."',
            nonce: '".wp_create_nonce( 'load_more_posts' )."'
        };";
        $shortcode .= "</script>";
        echo $shortcode;
    }
}