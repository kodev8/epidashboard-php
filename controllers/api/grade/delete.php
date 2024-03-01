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
    // uses delete request to sadat is sent via SGB $_GET
   
    // decrypt token 
    $decryptData = json_decode(Validator::decryptData($_GET['tokenData']), true);
    if(empty($decryptData)) {
        throw new Exception("Invalid token");
    }

    // delete the grade and log the activity
    $db->query(require controller('queries/grades/DELETE_single_grade.php'), [
        'student_epita_email' => $decryptData['student_epita_email'],
       'course_code'=>  $decryptData['population_course_code'],
       'exam_type' =>  $decryptData['exam_type']
    ]);

    $response = ['type'=> 'success-reload',
                'message' => $decryptData['student_epita_email'] . " grade deleted successfully"
    ];
    
    $activityHandler->submitActivity(
        $db, 
        $_SESSION['admin']['email'], 
        'delete', 
        "removed {$decryptData['student_epita_email']}'s ". upper($decryptData['population_course_code']) . " grade"
        );
    
    
    // Encode the response as JSON and send it back to the client
    header('Content-Type: application/json');
    echo json_encode($response);

}catch (CustomException $e) {

    $validator->addError($e->_getType(), $e->getMessage()) -> sendAPIErrors($e->getCode());
}
catch (Exception $e) {
    $validator->addError(message: $e->getMessage()) -> sendAPIErrors();

}
