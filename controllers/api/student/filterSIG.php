<?php
// 

// filtters students indiviual grades 
use controllers\populations\PopulationStore;
use Core\Validator;
use Core\LiveSearch;
use Core\App;
use Core\DB;


try {
    // check if email is in request, or abort
    if (empty($_GET['email'])){
        LiveSearch::returnResults(null);
    }

    // set up class stores
    $db = App::resolve(DB::class);
    $popStore = App::resolve(PopulationStore::class);


    $expectedSearchFields = [
        'course_code' =>  'GRADE_COURSE_CODE_REF',
        'course_name'   => 'c.COURSE_NAME',
    ];

    $expectedCategoryFields = [
        'exam_type' => 'GRADE_EXAM_TYPE_REF'

    ];

    $output  = LiveSearch::buildQuery($_GET, $expectedSearchFields, $expectedCategoryFields, prefix: ' AND ');

    $search = $output['search'];
    
    $email = lower($_GET['email']);

    // merge the email with the generated output params for the prepared statements
    $finalParams = array_merge([
        'student_epita_email' => $email,
    ], $output['finalParams']);

    // filtere grades
    $get_filtered_student_grades_query = require controller('queries/grades/SELECT_individual_grades_per_student.php');

    $filtered_student_grades = $db -> query(
        $get_filtered_student_grades_query, $finalParams
    ) -> fetchAll();


    if (empty($filtered_student_grades)) {
        LiveSearch::returnResults(null);
    }

    foreach($filtered_student_grades as &$student_grade) {
        // add course -url for  and grade class for recreated rows
        $population = $popStore->getPopulation($student_grade['population_code'], $student_grade['intake'], $student_grade['_year']);
        $student_grade['course_url'] ='/populations/' . $population['slug'] . '/' . lower($student_grade['course_code']);
        $student_grade['grade_class'] = $student_grade['grade'] < 10 ? 'failed' : ($student_grade['grade']  > 13 ? 'success' : 'average') ;
    
        $student_grade['token'] = Validator::encryptData(json_encode([
            'student_epita_email' => $email,
            'population_sudo' => $student_grade['population_code'] . $student_grade['intake'] . $student_grade['_year'],
            'course_name' => $student_grade['course_name'],
            'population_course_code' => $student_grade['course_code'],
            'exam_type' => $student_grade['exam_type'],
            'grade' => $student_grade['grade']
    
        ]));
    
    }

    unset($student_grade);

    LiveSearch::returnResults($filtered_student_grades);

}
catch (Error $err){

    (new Validator())->addError(message:$err -> getMessage()) -> sendAPIErrors();

}


?>

