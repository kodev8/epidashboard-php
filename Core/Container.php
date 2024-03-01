<?php

namespace Core;
use Exception;

class Container {
    //Singleton store for configuration of classes
    // and instances.
    public $bindings = [];

    public function bind($key, $resolver){

        $this -> bindings[$key] = $resolver;
    }


    public function resolve($key){

        
        if (!array_key_exists($key, $this->bindings)){
            throw new Exception("Binding not found for: $key");
        }
    

        $resolver = $this -> bindings[$key];
        return call_user_func($resolver);
                 
           
    }
}