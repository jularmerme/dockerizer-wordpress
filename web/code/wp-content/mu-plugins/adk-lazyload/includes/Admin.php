<?php
/*
* Trigger this file on plugin uninstall
* @package AdkLazyLoad
*/

namespace ADKll_Inc;
use \ADKll_Inc\BaseController;

class Admin extends BaseController
{
    public function register(){
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));   // Load Assets
        add_action('admin_menu', array($this, 'add_admin_pages'));           // Admin Pages
        add_filter('plugin_action_links_' . $this->plugin_name, array($this, 'settings_link'));
    }
    
    function enqueue(){
        wp_enqueue_style('adkll_pluginStyle', $this->plugin_url . 'pages/admin/css/style.css');
        wp_enqueue_script('adkll_pluginScript', $this->plugin_url . 'pages/admin/js/script.js');
    }
    
    function add_admin_pages(){
        add_menu_page('ADK Lazy Load', 'Lazy Load', 'manage_options', 'adk_lazy_load_plugin', array($this, 'admin_index'), 'dashicons-plus-alt', 110);
        //add_menu_page('ADK Settings', 'Settings', 'manage_options', 'save_settings', array($this, 'admin_save'), 'dashicons-plus-alt', 111);
    }
    
    function admin_index(){
        include_once($this->plugin_path.'/pages/admin/admin.php');
    }

    /*function admin_save(){
        include_once($this->plugin_path.'/pages/admin/save_settings.php');
    }*/
    
    function settings_link($links){
        $settings_link = '<a href="admin.php?page=adk_lazy_load_plugin">Settings</a>';
        array_push($links, $settings_link);
        return $links;
    }
}