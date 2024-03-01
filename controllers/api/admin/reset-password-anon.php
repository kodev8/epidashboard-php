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


try {

    // check all required fields to ensure they are in Post variables
    $requiredFields = [
    
        'epita_email' => 'EPITA Email',
     
    ];

    $_POST = $validator -> normalize_array($_POST);
    

    foreach($requiredFields as $field => $display){
        
        if (!$validator->validate_text($_POST[$field], min: 1)){
            throw new CustomException('inline-error', $display . ' is Required!');
        }

    }

    if(!$validator->validate_epita_email( lower( $_POST['epita_email'] ) ) ){
        throw new CustomException('inline-error', 'Invalid Email! Must be from Epita Organization!', );
    }

    

    $check_existing_admin = $db -> query(require controller('queries/admin/SELECT_admin.php'), [ 
        'admin_email' => $_POST['epita_email']
        ])-> fetchOne();
    
    
   

    
    if (!empty($check_existing_admin)){

        // delete any previous passwrod reset requests to prevent cluttering or spam
        $db -> query(require controller('queries/admin/DELETE_password_request_repeat.php'), [ 
            'admin_email' => $_POST['epita_email']
            ]);

    

        // send to activity tab
        $activityHandler->submitActivity(
            $db, 
            $_POST['epita_email'], 
            'request', 
            $_POST['epita_email'] . " requested to reset their password" 
        );

       
    }

    $response['type'] = 'success';
    $response['message'] ='If an account is associated with this email, the request has been sent successfully!';


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