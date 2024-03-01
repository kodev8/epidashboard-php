<?php

use Core\App;
use Core\DB;
use Core\ActivityHandler;

$content = trim(file_get_contents("php://input"));
$_arr = json_decode($content, true); 


// if no tab is set default to your otherwise us the given tab
$active_tab = $_arr['tab'] ?? 'your';

// set session active tab to maintain state 
$_SESSION['active_activity_tab'] = $active_tab;


$db = App::resolve(DB::class);
$activityHandler = new ActivityHandler();

// always set to 'your' if not super user
if(empty($_SESSION['admin']['superuser'])) {
    $active_tab = 'your';
}

// switch case to determine which content is dynamicall returned
switch ($active_tab){
    case 'other':
        
        $activity = $activityHandler -> getOtherActivityData($db, $_SESSION['admin']['email']);
        break;

    case 'registrations';        
        // $activity =  null;
        $activity = $activityHandler -> getRegistrations($db);
       
        break;
    default: 
        $activity = $activityHandler -> getYourActivityData($db, $_SESSION['admin']['email']);
    break;

};
   

    header('Content-Type: application/json');
    echo json_encode($activity);