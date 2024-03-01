<?php

use controllers\populations\PopulationStore;
use Core\LiveSearch;
use Core\Validator;
use Core\App;
use Core\DB;

if (empty($_GET['email'])){
    header('Location: /students');
    exit();
}

$email = lower($_GET['email']);

$db = App::resolve(DB::class);
$popStore = App::resolve(PopulationStore::class);

$student_grades = $db -> query(
    require controller('queries/grades/SELECT_individual_grades_per_student.php'), [
        'student_epita_email' => $email,
    ]
   ) -> fetchAllOrAbort();


$student_overall_grade = $db -> query(
    require controller('queries/grades/SELECT_individual_overall_wgrade_per_student.php'), [
        'student_epita_email' => $email,
    ]
   ) -> fetchOneOrAbort();


$student_contact = $db -> query(
    require controller('queries/students/SELECT_student_contact.php'), [
        'student_epita_email' => $email,
    ]
   ) -> fetchOneOrAbort();

$population = $popStore->getPopulation($student_contact['population_code'], $student_contact['intake'], $student_contact['_year']);

foreach($student_grades as &$student_grade){

    
    $student_grade['course_url'] ='/populations/' . $population['slug'] . '/' . lower($student_grade['course_code']);
    
    if( !empty($student_grade['grade'])){
    $student_grade['grade_class'] = $student_grade['grade'] < 10 ? 'failed' : ($student_grade['grade']  > 13 ? 'success' : 'average') ;
    //na*
    $student_grade['grade'] = round((float) $student_grade['grade'], 2) ;
    }else{
        $student_grade['grade'] = '';
        $student_grade['grade_class'] = '';
    }


    // generate token 
    $student_grade['token'] = Validator::encryptData(json_encode([
        'student_epita_email' => $email,
        'population_sudo' => $population['sudo'],
        'course_name' => $student_grade['course_name'],
        'population_course_code' => $student_grade['course_code'],
        'exam_type' => $student_grade['exam_type'],
        'grade' => $student_grade['grade']

    ]));
}

unset($student_grade);
if ( $student_overall_grade["overall_weighted_grade"]){
    $student_overall_grade['w_grade_class'] = $student_overall_grade["overall_weighted_grade"] < 10 ? 'failed' : ($student_overall_grade["overall_weighted_grade"] > 13 ? 'success' : 'average') ;
    $student_overall_grade["overall_weighted_grade"] = round((float) $student_overall_grade["overall_weighted_grade"], 2) ;
    }else{
        $student_overall_grade["overall_weighted_grade"] = 'N/A';
        $student_overall_grade["w_grade_class"] = '';
    }

$format_student = rtrim($email, '@epita.fr');
$emailID = str_replace(['.', '@'], '-', $format_student);

// SIG abbreviation for student individual GRADE
$SIG_FormID = 'SIGform_'  . $emailID;
$SIG_TableID = 'individual-grades';


$searchFilters =[ 
    [
        'key' => 'course_code', 
        'display' => 'Course Code',
    ], 
    [
        'key' => 'course_name', 
        'display' => 'Course Name',  
    ],

];

$categoryFilters = [
    [
        'key' => 'exam_type',
        'display' => 'Exam Type',
        'fields' => LiveSearch::resolveCategories('exam_types')
    ], 
];

$hiddenFilters = [
    'email' => $email
];
require view('students/student.view.php');


