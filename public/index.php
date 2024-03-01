<?php

session_start();

use controllers\populations\PopulationStore;
use Core\App;
use Core\Container;
use Core\DB;
use Core\Router;



const BASE_PATH =__DIR__ . '/../' ;

require BASE_PATH . 'Core/helper.php';

spl_autoload_register( function($class) {

    str_replace('\\', DIRECTORY_SEPARATOR, $class);
    require base_path("$class.php");
    
});

$container = new Container();
$container->bind(DB::class, function () {
    $config = require base_path('Core/config.php');
    return new DB($config['db']);
});

$container->bind(PopulationStore::class, function () {
    return PopulationStore::getInstance();
});


App::setContainer($container);

$router = new  Router();
require base_path('Core/routes.php');

date_default_timezone_set('Europe/Paris');

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

if (strlen($uri) > 1 && preg_match('#/+$#', $uri, $matches)){
    header("Location: " . rtrim($uri, '/'));
    die();
}
$_SESSION['_LOGGING'] = false;


require_once controller('base.php');

$method = $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method, );




