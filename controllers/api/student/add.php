<?php
// 

use Core\App;
use Core\DB;
use Core\Validator;
use Core\CustomException;
use Core\LiveSearch;
use Core\ActivityHandler;

require controller('/api/modalData.php');

$validator = new Validator();
$db = App::resolve(DB::class);
$activityHandler = new ActivityHandler();


try {


    $decryptData = json_decode(Validator::decryptData($_POST['tokenData']), true);
    if(empty($decryptData)) {
        throw new Exception("Invalid token");
    }

    // get form data  from add modal data stor
    $modalData = (new StudentModalData('add')) -> getFormData(null, null);

    // distinguish required from optional
    $requiredFields = [];
    $optionalFields = [];
    foreach($modalData['formInputs'] as $input){
        if (!empty($input['required']) && $input['required']){
            $requiredFields[$input['name']] = $input['label'];
        }
        else{
            $optionalFields[$input['name']] = $input['label'];
        }
    }

    $_POST = $validator -> normalize_array($_POST);


    // validate the studen name form fields to ensure that they are not empty
    foreach($requiredFields as $field => $display){

        if ($field != 'dob'){
            if (!$validator->validate_text($_POST[$field])){
                throw new CustomException('toast-error', $display . ' is required!');
            }
        }
    
        // validate student epita email
        if ($field == 'student_epita_email'){
            if (!$validator->validate_epita_email($_POST['student_epita_email'])){
                throw new CustomException('toast-error', $display . ' is invalid!');
            }
        }

        //validate name formats
        if ($field == 'fname' || $field=='lname'){
            if (!$validator->validate_name($_POST[$field])){
                throw new CustomException('toast-error', $display . ' is invalid!');
            }
        }
       
        // validate any email and ensure epita email is not used as perosnal email
        if ($field == 'personal_email'){
            if (!$validator->validate_email($_POST['personal_email'])){
                throw new CustomException('toast-error', $display . ' is invalid!');
            }
        }
           
        // validate a date of birth
        if ($field == 'dob'){
            if (!$validator->validate_dob($_POST['dob'])){
                throw new CustomException('toast-error', $display . ' is invalid!');
            }
        }

        // check if enrolment status is amongst allowed types i.e. completed, selected and confirmed
        if ($field == 'enrolment_status'){
            if (!in_array(trim(lower($_POST[$field])), LiveSearch::resolveCategories('enrolment_status'))){
                throw new CustomException('toast-error', $display . ' is invalid!');
            }
        }

    }

    if ($_POST['personal_email'] ==  $_POST['student_epita_email'] || $validator->validate_epita_email($_POST['personal_email'])){
        throw new CustomException('toast-error', "EPITA email should not be used as personal email!");
    }

    // check if student email is already used
    $check_student_epita_email = $db -> query(require controller('queries/students/SELECT_student_contact.php'), [
        'student_epita_email' => lower($_POST['student_epita_email']),
    ])->fetchOne();

    if(!empty($check_student_epita_email)){
        throw new CustomException("toast-warning", "Student with this EPITA email already exists!");
    }
   
    // check if personal email is already used
    $check_personal_email = $db -> query(require controller('queries/students/SELECT_contact_from_personal.php'), [
        'personal_email' => lower($_POST['personal_email']),
    ])->fetchOne();

    if(!empty($check_personal_email)){
        throw new CustomException("toast-warning", "Student with this Personal email already exists!");
    }

    // nullify any optional fields
    foreach($optionalFields as $field => $value){
        if (!in_array($field, array_keys($_POST))){
            $_POST[$field] =null;
        }
    }

    // insert a new student contact first to align with foreign key requirements
    $db->query(require controller('queries/students/INSERT_new_student_contact.php'), [
        'personal_email' => lower($_POST['personal_email']),
        'fname' => title($_POST['fname']),
        'lname' => title($_POST['lname']),
        'address' => title($_POST['address']),
        'city' => title($_POST['city']),
        'country'=> title($_POST['country']),
        'dob' => $_POST['dob']
    ]);


    // insert new student
    $db->query(require controller('queries/students/INSERT_new_student.php'), [
        'student_epita_email' => lower($_POST['student_epita_email']),
        'personal_email' => lower($_POST['personal_email']),
        'enrolment_status' => lower($_POST['enrolment_status']),
        'population_intake' => upper($decryptData['population_intake']),
        'population_year' => $decryptData['population_year'],
        'population_code'=> $decryptData['population_code'],
    ]);

    // get all the courses of a population to insert null grades for
    $course_grades_to_insert = $db->query(require controller('queries/courses/SELECT_population_courses_exams.php'), [
        'population_code'=> $decryptData['population_code'],
    ])->fetchAll();


    foreach($course_grades_to_insert as $course_data) {
        
        // insert null grades for the student
        $db->query(require controller('queries/grades/INSERT_null_grades.php'), [
            'student_epita_email' => lower($_POST['student_epita_email']),
            'course_code' => $course_data['course_code'],
            'course_rev' =>  $course_data['course_rev'],
            'exam_type' => $course_data['exam_type']
            
        ]);
    }

    // add to activity table to keep track of changes
    $activityHandler->submitActivity(
        $db, 
        $_SESSION['admin']['email'], 
        'add', 
        "added a student " . lower($_POST['student_epita_email']) . " to " . upper($decryptData['population_sudo']));

    // send success response as json
    $response = [
        'type' => 'success-reload',
        'message' => $_POST['student_epita_email'] . ' successfully added to ' . $decryptData['population_sudo']
    ];


    header('Content-Type: application/json');
    echo json_encode($response);
    die();

}catch (CustomException $e) {

    $validator->addError($e->_getType(), $e->getMessage()) -> sendAPIErrors($e->getCode());
}
catch (Exception $e) {
    $validator->addError(message: $e->getMessage()) -> sendAPIErrors();

}

