<?php
// 

use Core\App;
use Core\DB;
use Core\Validator;
use Core\CustomException;
use Core\ActivityHandler;


require controller('/api/modalData.php');



// set up classes
$validator = new Validator();
$db = App::resolve(DB::class);
$activityHandler = new ActivityHandler();

try {

    // decrypt the token
    $decryptData = json_decode(Validator::decryptData($_POST['tokenData']), true);
    if(empty($decryptData)) {
        throw new Exception("Invalid token");
    }

    //get modal form data for courses
    $modalData = (new CourseModalData('add')) -> getFormData(null, null);
    $requiredFields = [];
    $optionalFields = [];


    //get all required fields and create labels for displayng messages via toasts
    foreach($modalData['formInputs'] as $input){
        if (!empty($input['required']) && $input['required']){
            if($input['name'] == 'course_type'){
                $requiredFields[$input['name']] ='Course Type';
            }else {
                $requiredFields[$input['name']] = $input['label'];
            }
        }
        else{
            $optionalFields[$input['name']] = $input['label'];
        }
    }

    // nullify optionaly fields if not provided
    foreach($optionalFields as $field => $value){
        if (!in_array($field, array_keys($_POST))){
            $_POST[$field] =null;
        }
    }

    $_POST = $validator -> normalize_array($_POST);


    // check for either tyoe fo course code ( new or existing )
    if (empty($_POST['course_code']) && empty($_POST['existing_course_code'])){
        throw new CustomException('toast-error',  ' Course Code is required!');
    } else {

        // for some reason null coaleasing wont work here ???
        if(empty($_POST['course_code'])){
            $_POST['final_code'] = upper($_POST['existing_course_code']);
        
        }else{
            $_POST['final_code'] = upper($_POST['course_code']);
        }
         
        
        unset($requiredFields['existing_course_code']);
        $requiredFields['course_code'] = $_POST['final_code'];
        
    }

       

   
    // validate the studen name form fields to ensure that they are not empty
    foreach($requiredFields as $field => $display){

        
        if( $field == 'course_type'){
            if (!in_array(lower($_POST[$field]), ['new', 'existing'])){
                throw new CustomException('toast-error', $display . 'must be either new or existing!');
            }
        }

        if($_POST['course_type'] == 'new'){
            if (!$validator->validate_text($_POST[$field], max : $field == 'course_desc' ? 200 : 100)){
                throw new CustomException('toast-error', $display . ' is required!');
            }
    

    
            if ($field == 'course_code'){
                if (!$validator->validate_course_code($_POST[$field])){
                    throw new CustomException('toast-error', 'Course Code should be at least 3 characters and only contain letters and underscores!');
                }
            }

            if ($field == 'course_name'){
                if (!$validator->validate_name($_POST[$field])){
                    throw new CustomException('toast-error', $display . ' should only contain letters invalid!');
                }
            }
        

            if ($field == 'duration') {
                if (!$validator->validate_int($_POST[$field], min: 1,max:  60)){
                    throw new CustomException('toast-error', $display . ' should be between 1 ad 60 hours!');
                }
            }
        }

        // doesnt actually do anything because attendance and sessions are not changes but could be useful for future implementations

        if( $field == 'teacher_epita_email'){
            $check_teacher_epita_email = $db -> query(require controller('queries/courses/SELECT_teacher.php'), [
                'teacher_epita_email' => lower($_POST['teacher_epita_email']),
            ])->fetchOne();
        
            if(empty($check_teacher_epita_email)){
                throw new CustomException("toast-warning", "Teacher email is invalid");
            }
        }

    }

    $students = $db -> query(require controller('queries/populations/SELECT_population_students_only.php'), [
        'population_code' => $decryptData['population_code'],
        'population_intake' => $decryptData['population_intake'], 
        'population_year' =>  $decryptData['population_year']
        ])->fetchAll();

        
  

    
   if($_POST['course_type'] == 'new'){
        // default course rev set to 1
        $_POST['course_rev'] = 1;

//    check if course code has been taken 
        $courses = $db->query(require controller('queries/UniqueSelectors/SELECT_unique_courses.php'))->fetchAll();
        foreach($courses as $course) {
            if (lower($course['course_code']) == lower($_POST['final_code'])){
                throw new CustomException('toast-error', 'Course Code already exists');
            }
        }

        try {

            // insert to db with current year, and default course rev of 1
            $db -> query(require controller('queries/courses/INSERT_new_course.php'), [
                'course_code' =>$_POST['final_code'],
                'course_rev' => $_POST['course_rev'],
                'duration' => $_POST['duration'],
                'course_last_rev' => date("Y"),
                'course_name' => title($_POST['course_name']),
                'course_desc' => $_POST['course_desc'],
            ]);


        } 
        catch (CustomException $e) {
            throw new CustomException('toast-error', 'Unable to add course');
        }   

        try {

            // insert to population
            $db -> query(require controller('queries/populations/INSERT_new_population_course.php'), [
                'course_code' => $_POST['final_code'],
                'course_rev' => 1,
                'population_code' => $decryptData['population_code']
            ]);
        }  
        catch (CustomException $e) {
            throw new CustomException('toast-error', 'Unable to add course to program');
        }   


        try {
    
            //insert admin override exam to the course 
            $db->query(require controller('queries/grades/INSERT_admin_override_exam.php'), [
                'course_code' => $_POST['final_code'],
                'course_rev' => $_POST['course_rev'],
            ]); 
        
         
        
               
        
                // insert null grades for all students in that course
                foreach($students as $student){
                    $db->query(require controller('queries/grades/INSERT_null_grades.php'), [
                        'student_epita_email' => $student['student_epita_email'], 
                        'course_code' => $_POST['final_code'],
                        'course_rev' => $_POST['course_rev'],
                        'exam_type' => 'Admin Override'
                        
                    ]);
                }
            }
            catch (CustomException $e) {
                throw new CustomException('toast-error', 'Unable to add student grades to new program');
            }   


    }   
    // check if course_code is not already amongst the populations courses
    // (can freely check here because it has been validated above)
    if($_POST['course_type'] == 'existing'){


        $popCourses = $db -> query(require controller('queries/courses/SELECT_courses_and_sessions.php'),[ 
                            'population_code' => $decryptData['population_code'],
                            'population_intake' => $decryptData['population_intake']
                            ]) -> fetchAll();

       

        foreach($popCourses as $pcourse) {
            if (lower($pcourse['course_code']) == lower($_POST['final_code'])){
                throw new CustomException('toast-error', 'Course Code already exists within the population');
            }
        }


          
        // check if it actually exists
        $existingCourse = $db -> query(require controller('queries/courses/SELECT_course_single.php'), [
            'course_code' => $_POST['final_code']
        ])-> fetchOne();

       


        
        if(empty($existingCourse)){
            throw new CustomException('toast-error', 'Course Code is not recognized, try making a new course instead.');
        }

        try {

            $_POST['course_rev'] = $existingCourse['course_rev'];
           

             // insert to db
            $db -> query(require controller('queries/populations/INSERT_new_population_course.php'), [
                'course_code' => $existingCourse['course_code'],
                'course_rev' => $existingCourse['course_rev'],
                'population_code' => $decryptData['population_code']
            ]);

            
        }
        catch (CustomException $e) {
            throw new CustomException('toast-error', 'Unable to add existing course to program');
        }   

        try {

            $course_exams = $db -> query(require controller('queries/grades/SELECT_course_exams.php'), [
                'course_code' => $existingCourse['course_code']
            ])->fetchAll();


            

        

                // insert null grades for all students in that course
            foreach($students as $student){
                foreach($course_exams as $exam){
                    $db->query(require controller('queries/grades/INSERT_null_grades.php'), [
                        'student_epita_email' => $student['student_epita_email'], 
                        'course_code' => $exam['course_code'],
                        'course_rev' => $exam['course_rev'],
                        'exam_type' => $exam['exam_type']
                    ]);
                }
            }
        }
            catch (CustomException $e) {
                throw new CustomException('toast-error', 'Unable to add student grades to new program');
            }   

   }


   $activityHandler->submitActivity(
    $db, 
    $_SESSION['admin']['email'], 
    'add', 
    "added a new course: " . upper($_POST['final_code']) . " to ". upper($decryptData['population_sudo'])
    );


        $response = [
            'type' => 'success-reload',
            'message' => $_POST['final_code'] . ' successfully added to ' . $decryptData['population_sudo']
        ];

    // Encode the response as JSON and send it back to the client
    header('Content-Type: application/json');
    echo json_encode($response);
    die();

}catch (CustomException $e) {

    $validator->addError($e->_getType(), $e->getMessage()) -> sendAPIErrors($e->getCode());
}
catch (Exception $e) {
    $validator->addError(message: $e->getMessage()) -> sendAPIErrors();

}

