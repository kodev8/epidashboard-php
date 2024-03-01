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

    

    // here some logic would send emails and only when the password is reset then the following would occur

    $admin = $db->query(require controller('queries/admin/UPDATE_reset_sent_handled.php'), [ 
        'activity_id' => $decryptData['activity_id'],
    ]);


    $response['type'] = 'success';
    $response['message'] = 'Password reset link has been sent to ' . $decryptData['admin_email'] ;


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