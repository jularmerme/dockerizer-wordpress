<?php
/*
* Trigger this file on plugin uninstall
* @package AdkLazyLoad
*/

namespace ADKll_Inc;

class BaseController
{
    public $plugin_path;
    public $plugin_url;
    public $plugin_name;
    public $plugin_showName;
    
    public function __construct(){
        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 1));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 1));
        $this->plugin_name = plugin_basename(dirname(__FILE__, 2)) . "/adk-lazyload.php";
        $this->plugin_showName = "Lazy Load by ADK";
    }
}