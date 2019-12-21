<?php namespace Config;

    class Router {

        public static function Route(Request $request) {
            $controllerler = $request->getController() . 'Controller';

            $method = $request->getMethod();

            $parameters = $request->getParameters();

            $class = "Controllers\\". $controllerler;

            $instance = new $class;

            if(!isset($parameters)) {
                call_user_func(array($instance, $method));
            } else {
                call_user_func_array(array($instance, $method), $parameters);
            }
        }
    }
    
?>