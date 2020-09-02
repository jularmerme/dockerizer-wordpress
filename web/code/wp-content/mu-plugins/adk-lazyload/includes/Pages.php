<?php
/*
* Trigger this file on plugin uninstall
* @package AdkLazyLoad
*/

namespace ADKll_Inc;
use \ADKll_Inc\BaseController;

class Pages extends BaseController
{
    public function register(){
        add_action('wp_enqueue_scripts', array($this, 'enqueue'));      // Load Assets
        add_shortcode('adk-lazyload', array($this, 'adkLazyLoad'));
    }
    
    function enqueue(){
        wp_enqueue_style('adkll_pluginStyle', $this->plugin_url . 'pages/public/css/style.css');
        wp_enqueue_script('adkll_pluginScript', $this->plugin_url . 'pages/public/js/script.js');
    }
    
    function adkLazyLoad($atts, $content=null){
        include_once($this->plugin_path.'/pages/public/shortcode.php');
        $attributes = Shortcode::getAttributes($atts);
        $plugin_url = $this->plugin_url;
        Shortcode::getShortcode($attributes, $plugin_url);
    }
}