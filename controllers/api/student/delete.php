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
   
    // decrypt token setn via post
    $decryptData = json_decode(Validator::decryptData($_GET['tokenData']), true);
    if(empty($decryptData)) {
        throw new Exception("Invalid token");
    }

    // check for a student contact
    $student_contact = $db -> query(
        require controller('queries/students/SELECT_student_contact.php'), [
            'student_epita_email' => $decryptData['student_epita_email'],
        ]
       ) -> fetchOne();
    if( empty($student_contact)){
        throw new CustomException(message: 'Unable to delete grades: cannot find student contact...');

    }

    
    // delete all the grades for a studnt
    try{
        $db->query(require controller('queries/grades/DELETE_all_student_grades.php'), [
            'student_epita_email' => $decryptData['student_epita_email'],
        ]);
    }
    catch (Exception $e){
            throw new CustomException(message: 'Unable to delete grades');
    }

    // delete student info first to respect foregin ket requirements
    try {
        $db->query(require controller('queries/students/DELETE_student.php'), [
            'student_epita_email' => $decryptData['student_epita_email'],
        ]);
    }
    catch (Exception $e){
        throw new CustomException(message: 'Unable remove to student info');
    }  

    // delete the contact info
    try {

        $personal_email = $student_contact['student_personal_email'];
    
       $db->query(require controller('queries/students/DELETE_student_contact.php'), [
            'student_personal_email' => $personal_email,
        ]);
    }
    catch (Exception $e){
        throw new CustomException(message: 'Unable remove to student contact info');
        }   


    // log activity 
    $activityHandler->submitActivity(
        $db, 
        $_SESSION['admin']['email'], 
        'delete', 
        "removed " . lower($decryptData['student_epita_email']) . " from " . upper($decryptData['population_sudo']) 
    );

    // send success response
    $response = [
        'type'=> 'success-reload',
        'message' => $decryptData['student_epita_email'] . " has been removed!"
    ];

    // Encode the response as JSON and send it back to the client
    header('Content-Type: application/json');
    echo json_encode($response);

}catch (CustomException $e) {

    $validator->addError($e->_getType(), $e->getMessage()) -> sendAPIErrors($e->getCode());
}
catch (Exception $e) {
    $validator->addError(message: $e->getMessage()) -> sendAPIErrors();

}
