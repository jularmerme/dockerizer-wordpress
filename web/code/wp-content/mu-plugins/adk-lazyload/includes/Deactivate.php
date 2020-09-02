<?php
/*
* Trigger this file on plugin uninstall
* @package AdkLazyLoad
*/

namespace ADKll_Inc;

class Deactivate
{
    public static function deactivatePlugin(){
        flush_rewrite_rules();
    }
}