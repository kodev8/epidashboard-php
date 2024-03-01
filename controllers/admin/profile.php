<?php


// name for message
$name = title($_SESSION['admin']['fname']);
if (substr($name, -1) == 's') {
    $name = $name . "'";
}else {
    $name = $name . "'s";
}
 
// control the activity via sessions to maintain state accross apages

if (empty($_SESSION['active_activity_tab'])){
    
    $_SESSION['active_activity_tab'] = 'your';
}
$active_tab =  $_SESSION['active_activity_tab'];

require view('admin/profile.view.php');

?>