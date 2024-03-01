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


    // decrypt token to retrieve data

    $decryptData = json_decode(Validator::decryptData($_arr['tokenData']), true);
    if(empty($decryptData)) {
        throw new Exception("Invalid token");
    }

    if (!$validator->validate_epita_email($decryptData['admin_email'])){
        throw new CustomException('toast-error', 'Invalid Admin Email!');

    }


    // check if admin already exists by some chance, but it should already be validated from rgeistration form
    $admin = $db->query(require controller('queries/admin/SELECT_admin.php'), [ 
        'admin_email' => $decryptData['admin_email'],
    ]) -> fetchOne();


    if (!empty($admin)){
        throw new CustomException('toast-error', 'This is already an admin! Cannot reject...');
    }   

    try {

        // reject the registration
        $db -> query(require controller('queries/admin/UPDATE_reject_registration.php'), [
            'epita_email' => $decryptData['admin_email']
        ]);


        
    }catch (Exception) {
        throw new CustomException('toast-error', 'Cannot reject registration...  ');
        
    }

    $activityHandler->submitActivity(
        $db, 
        $_SESSION['admin']['email'], 
        'deny_register', 
        "rejected {$decryptData['admin_email']}'s registration request" 
     );


    $response['type'] = 'success';
    $response['message'] = $decryptData['admin_email'] . ' has been rejected and can longer apply to be an admin...';


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