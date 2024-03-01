<?php

namespace Core;

use Core\AccessAuth\AccessAuth;
use Core\Response;

class Router {

    // store of all allowed routes
    protected $routes = [];

    // add a new route to the routes store to map to a controller with a respective method
    private function addRoute(string $uri , string  $controller, string $method) {

        $this->routes[] = [
            "uri" => $uri,
            "controller" => $controller,
            "method" => $method,
            "accessAuth" => null
        ];

        return $this;

    }
    
    // add get routes
    public function get(string $uri , string  $controller) {
        return $this->addRoute($uri, $controller, 'GET');
    
    }

    // add post routes
    public function post(string $uri , string  $controller) {
        return $this->addRoute($uri, $controller, 'POST');
    }

    // add delete routes
    public  function delete(string $uri , string  $controller) {
        return $this->addRoute($uri, $controller, "DELETE");
    }

    // add patch routes
    public function patch(string $uri , string  $controller) {
        return $this->addRoute($uri, $controller, 'PATCH');
    }

    // route function to route either using an exist match or by validating via regex
    public function route($uri, $method){

        

         //iterate through routes store
        foreach ($this->routes as $route) {

            //first check if there is an exact match
            if($route['uri'] == $uri && $route["method"] === $method){
                AccessAuth::resolveAccess($route["accessAuth"]) ;
                return require controller($route['controller']);
            }

            // otherise try to route with regeex
            elseif(preg_match('/^regex/', $route['uri']) && preg_match(ltrim($route['uri'], 'regex'), $uri, $matches)){
                AccessAuth::resolveAccess($route["accessAuth"]);
                return require controller($route['controller']);
            }
        }
        // otherwise abort
        static::abort();
    }

    public function addAccessAuth($accessBy) {

        $this->routes[array_key_last($this->routes)]['accessAuth'] = $accessBy;
        return ;
    }
    
    // resolve routes
    public static function resolveRoute(
                                    array $fields, 
                                    array $urlMatches, 
                                    array $fieldCheck,
                                    bool $viaApi=false
                                    ){

            // check if any requiried fields empty, if yes abort via api rather than 404 page
            if(empty($urlMatches) || empty($fields) ||  empty($fieldCheck)) {
                self::abort(viaApi: $viaApi);
            }
            
            // remove the matches from the begging of the array
            array_shift($urlMatches);
    
            // if the number of expected is not equal to fields matched, abort
            if (count($urlMatches) != count($fields)){
                self::abort(viaApi: $viaApi);
            }

            //setup arrays for url mapping a
            $url_mapping = [];
            $actives = [];
    
            for ($i = 0; $i < count($fields); $i++){
    
                $field = $fields[$i];
                $url_mapping[$field['name']] = $urlMatches[$i];
    
                //fields to be verified will have a verify key set to try
                if (!empty($field['verify']) && $field['verify']==true){

                    foreach($fieldCheck as $check){

                        //abort if no match
                        if (!array_key_exists($field['name'], $check)) {
                            self::abort(viaApi: $viaApi);

                        }
                        // set field name to the matched check
                        if (lower($url_mapping[$field['name']] ) === $check[$field['name']]){
                                $actives[$field['name']]  = $check;
                                break;
                            }
                        }
                }
            };
    
            //if no matches found after all iteration, abort
            if (empty($actives)){

                self::abort(viaApi: $viaApi);

            }
    
            return [
                'actives' => $actives,
                'url_mapping' => $url_mapping
            ];
           
    }

    // fields ot verity for ppulations and coures
    public static function resolveFields ($key) {

        $fieldMap = [
            'population' => [
                [
                    'name'=>'slug',
                    'verify' => true
                ]
            
            ],
            'course'  => [
                [
                    'name'=> 'slug',
                    'verify' => true
                ],
                [
                    'name' => 'course'
                ]
            ]
        ];


        if (array_key_exists($key, $fieldMap)){
            return $fieldMap[$key];
        
        }
        return null;
    }

    //aborts via json response, or via code page
    public static function abort($code=Response::NOT_FOUND, $viaApi=false) {

        if ($viaApi){
            
            LiveSearch::returnResults(null);

        }else{

            http_response_code($code);
    
            require view("/codes/{$code}.php");
        
            die();

        }
        //retrun relevant http code to the server
       
    }
}
