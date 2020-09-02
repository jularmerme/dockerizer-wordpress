<?php
/*
* Trigger this file on plugin uninstall
* @package AdkLazyLoad
*/

namespace ADKll_Inc;

final class Init
{
    /**
    * Store all the classes inside array
    * @return array Full list of classes
    */
    public static function get_services(){
        return [
            Admin::class,
            Pages::class
        ];
    }
    
    /**
    * Loop through the classes, initialize them, and call the register() method if it exists
    * @return 
    */
    public static function register_services(){
        foreach(self::get_services() as $class){
            $service = self::instantiate($class);
            if(method_exists($service, 'register')){
                $service->register();
            }
        }
    }
    
    /**
    * Initialize the class
    * @param class $class from the services array
    * @return class instance new instance of the class
    */
    private static function instantiate($class){
        return new $class();
    }
}