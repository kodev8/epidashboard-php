<?php

use Core\Response;
use Core\Router;

// helper functions

//lower and upper functions to shorten built in func
function lower($word){
    return strtolower($word);
}

function upper($word){
    return strtoupper($word);
}

//creates titles from strings
function title($word){
    return ucwords(strtolower($word));
}

//retrun first_letter for population sudos
function first_letter($word, $case='upper'){
    // returns the upper case of the first letter of a word
    $letter = substr($word, 0, 1);
    if ($case == 'upper'){
        return strtoupper($letter);
    }else{
        return strtolower($letter);
    };
}

function dd($value){
    // displays vars in neat anad human readable form
    //  helpful for debugging
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
}


// file accessors

function base_path(string $path){
    // loads files relative to the base directoty
    return BASE_PATH . $path;
}

// gets base path to controller folder
function controller(string $path){
    return base_path('controllers/'. $path);

}

// requires views from the view folder
function view(string $view_path){
    // easily loads views
    // extract($attributes);
    
    return base_path('views/'. $view_path);
}

function component(string $view_path){
    // easily loads views
    return view('components/'. $view_path);
}

// return base url 
function base_url(){
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'];
}

//return static path
function static_url($path){
    return base_url() . '/static/' . $path;
}


// statrt a session if one hasnt started
function startSession(){
    if(!isset($_SESSION))  { 
        session_start();   
    }
}
// checked if user is logged in and super user
function superUserRequired() {
startSession();

    if (empty($_SESSION['admin']['superuser'])) {
        Router::abort(Response::FORBIDDEN);
    }

}
