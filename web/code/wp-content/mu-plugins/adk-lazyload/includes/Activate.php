<?php
/*
* Trigger this file on plugin uninstall
* @package AdkLazyLoad
*/

namespace ADKll_Inc;

class Activate
{
    public static function activatePlugin(){
        // generate a CPT
        // flush rewrite rules
        // El hook admin_menu ejecuta la funcion rai_menu_administrador
        flush_rewrite_rules();
    }
}