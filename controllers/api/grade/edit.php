<?php
// 

use Core\ActivityHandler;
use Core\App;
use Core\DB;
use Core\Validator;
use Core\CustomException;

$validator = new Validator();
$db = App::resolve(DB::class);
$activityHandler = new ActivityHandler();

try {
    // get sent data and decrypt token

    $content = trim(file_get_contents("php://input"));
    $_arr = json_decode($content, true);

   
    $decryptData = json_decode(Validator::decryptData($_arr['tokenData']), true);
    if(empty($decryptData)) {
        throw new Exception("Invalid token");
    }

    //set up required fields

    $requiredFields = [
        'grade' => 'Grade',
    ];


    // validate the student name form fields to ensure that they are not empty
    foreach($requiredFields as $field => $display){

        if (!$validator->validate_int($_arr[$field]) || !$validator->validate_int($_arr[$field], min: 1, max: 20)){
            throw new CustomException('toast-error', $display . ' is invalid!');
        }
    }

    // get a single grade that should be edited, if not abort
    $selectGrade = $db->query(require controller('queries/grades/SELECT_student_SING_grade.php'), [
        'student_epita_email' => $decryptData['student_epita_email'],
       'course_code'=>  $decryptData['population_course_code'],
       'exam_type' =>  $decryptData['exam_type']
    ])->fetchOne();


    if (empty($selectGrade)){
        throw new CustomException('toast-error','No grade found!');
    }

    // if if grade realy needs to be changed
    if ($selectGrade['grade'] == $_arr['grade']){
        $response = ['type' => 'info', 'message'=> 'Grade is the same, no changes made!'];
    }else {

        
        // uppdate the grade and log the activity
        $updateGrade = $db->query(require controller('queries/grades/UPDATE_student_grade.php'), [
            'grade' => $_arr['grade'],
            'student_epita_email' => $decryptData['student_epita_email'],
        'course_code'=>  $decryptData['population_course_code'],
        'exam_type' =>  $decryptData['exam_type']
        ]);


        $activityHandler->submitActivity(
        $db, 
        $_SESSION['admin']['email'], 
        'edit', 
        "changed {$decryptData['student_epita_email']}'s " . upper($decryptData['population_course_code']) . " grade from " . ($selectGrade['grade'] ?? 'NO GRADE') . " to {$_arr['grade']}" );

        $response = ['type'=> 'success',
                    'message' => $decryptData['student_epita_email'] . " grade update successfully"
        ];
    }
    // Encode the response as JSON and send it back to the client
    header('Content-Type: application/json');
    echo json_encode($response);

}catch (CustomException $e) {

    $validator->addError($e->_getType(), $e->getMessage()) -> sendAPIErrors($e->getCode());
}
catch (Exception $e) {
    $validator->addError(message: $e->getMessage()) -> sendAPIErrors();

}
