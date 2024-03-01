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

try{

   

    // use file get content s when not submitting via form
    $content = trim(file_get_contents("php://input"));
    $_arr = json_decode($content, true);

   
    // decryp to retreieve data
    $decryptData = json_decode(Validator::decryptData($_arr['tokenData']), true);
    if(empty($decryptData)) {
        throw new Exception("Invalid token");
    }

    $requiredFields = [
        'fname' => 'First Name',
        'lname' => 'Last Name',
    ];

    $_arr= $validator -> normalize_array($_arr);

    // validate the student name form fields to ensure that they are not empty
    foreach($requiredFields as $field => $display){

        if (!$validator->validate_name($_arr[$field])){
            throw new CustomException('toast-error', $display . ' is invalid!');
        }
    }

   

    // 
    $student = $db->query(require controller('queries/students/SELECT_student_contact.php'), [
        'student_epita_email' => $decryptData['student_epita_email'],
    ])->fetchOne();

    // check if the student does not exist
    if (empty($student)){
        throw new CustomException('toast-error', 'Student email is invalid');
    }

    // check if the no data should be changed
    if (lower($student['fname']) == lower($_arr['fname']) && lower($student['lname']) == lower($_arr['lname'])){

        $response = [
            'type' => 'info',
            'message'=> 'Names are the same, no update made'
        ];

    }else {

        // otherwise update the names 
        $db -> query(require controller('queries/students/UPDATE_names.php'), [ 
                    'student_contact_email' => $student['student_personal_email'],
                    'fname' => title($_arr['fname']),
                    'lname' => title($_arr['lname'])
                    ]);
        

        //log activity
        $activityHandler->submitActivity(
            $db, 
            $_SESSION['admin']['email'], 
            'edit', 
            "changed {$decryptData['student_epita_email']}'s name from " .  
                title($student['fname']) . ' ' .
                title($student['lname']) . ' to ' . 
                title($_arr['fname']) . ' ' .
                title($_arr['lname'])
        );
        
        $response = [
            'type' => 'success',
            'message'=> $decryptData['student_epita_email'] . " name successfully updated!"
        ];
    }
    header('Content-Type: application/json');
    echo json_encode($response);

}catch (CustomException $e) {

    $validator->addError($e->_getType(), $e->getMessage()) -> sendAPIErrors($e->getCode());
}
catch (Exception $e) {
    $validator->addError(message: $e->getMessage()) -> sendAPIErrors();

}
