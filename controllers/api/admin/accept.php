<?php
// 

use Core\App;
use Core\DB;
use Core\Validator;
use Core\CustomException;
use Core\ActivityHandler;



$validator = new Validator();
$db = App::resolve(DB::class);
$activityHandler = new ActivityHandler();

$content = trim(file_get_contents("php://input"));
$_arr = json_decode($content, true); 

try {

    $requiredFields = [
        'as_super' => 'Admin Type',
    ];
    

    if(!isset($_arr['as_super'])){ // used isset here because expecting a bool
        throw new CustomException('toast-error', 'Admin Type Must be  supplied!');  
    }   


    $decryptData = json_decode(Validator::decryptData($_arr['tokenData']), true);
    if(empty($decryptData)) {
        throw new Exception("Invalid token");
    }

    

    if (!$validator->validate_epita_email($decryptData['admin_email'])){
        throw new CustomException('toast-error', 'Invalid Admin Email!');

    }

    // check if admin already exists
    $admin = $db->query(require controller('queries/admin/SELECT_admin.php'), [ 
        'admin_email' => $decryptData['admin_email'],
    ]) -> fetchOne();

    if (!empty($admin)){
        throw new CustomException('toast-error', 'This email cannot be used as an admin');
    }   

    try {
        // get the registration
        $reg = $db -> query(require controller('queries/admin/SELECT_registration_pending.php'), [
            'epita_email' => $decryptData['admin_email']
        ])->fetchOne();

        // update the accpted
        $db -> query(require controller('queries/admin/UPDATE_confirm_registration.php'), [
            'epita_email' => $decryptData['admin_email'],
           'confirmation_time' => date('Y-m-d H:i:s'),
        ]);
        
        // add registered to admin as a new admin
        $reg = $db -> query(require controller('queries/admin/INSERT_new_admin.php'), [
            'epita_email' => $decryptData['admin_email'],
            'fname' =>  $reg['fname'],
            'lname' =>  $reg['lname'],
            'password' => $reg['password'],
            'superuser' => $_arr['as_super']
            
        ]);

        
    }catch (Exception) {
        throw new CustomException('toast-error', 'Could not add new admin :(');
        
    }

    $activityHandler->submitActivity(
        $db, 
        $_SESSION['admin']['email'], 
        'confirm_register', 
        "added {$decryptData['admin_email']} to admins"  . ($_arr['as_super'] ? ' (super) ': '')
     );

     

    $response['type'] = 'success';
    $response['message'] = $decryptData['admin_email'] . ' has been registered as an admin!' . ($_arr['as_super'] ? ' (super) ': '');


    // Encode the response as JSON and send it back to the client
    header('Content-Type: application/json');
    echo json_encode($response);
    die();
}

catch (CustomException $e) {

    $validator->addError($e->_getType(), $e->getMessage()) -> sendAPIErrors();

}
catch (Exception $e) {
    $validator->addError(message:  $e->getMessage()) -> sendAPIErrors();
}

// };