<?php

namespace Core; 

class App {

    // singleton that allows to set a global container

    private static $container;
    
    public static function setContainer($container) {

        static::$container = $container;

    }

    public static function getContainer(){
        return static::$container;
    }

    public static function resolve($key) {
        return static::$container->resolve($key);
    }
    
}