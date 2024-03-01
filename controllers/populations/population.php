<?php

use controllers\populations\PopulationStore;
use Core\Router;
use Core\Validator;
use Core\App;
use Core\DB;

// resolve classes
$fields = Router::resolveFields('population');
$popStore = App::resolve(PopulationStore::class);

// gets a single populating using a slug
$active_population;
$resolvedRoute = Router::resolveRoute($fields, $matches, $popStore->getPopulations());
if ($resolvedRoute) {
    $active_population = $resolvedRoute['actives']['slug'];
};

// only use db if resolved
$db = App::resolve(DB::class);

// returns email fname lname passed_course total_courses
$passed_query = require controller('queries/grades/SELECT_passed.php');

// get student performance 
$student_performance =  $db -> query($passed_query, [           
                        'population_code' => $active_population['population_code'], 
                        'population_intake' => $active_population['intake'],
                        'population_year' => $active_population['_year'], 
                        ]) -> fetchAllOrAbort();

// tokenise student perforamnce
foreach($student_performance as &$sp){
    $sp['token'] = Validator::encryptData(json_encode([
        'population_code' => $active_population['population_code'], 
        'population_intake' => $active_population['intake'],
        'population_year' => $active_population['_year'],
        'population_sudo' => $active_population['sudo'],
        'student_epita_email' => $sp['student_epita_email']
    ]));
}
unset($sp);

$population_token =  Validator::encryptData(json_encode([
    'population_code' => $active_population['population_code'], 
    'population_intake' => $active_population['intake'],
    'population_year' => $active_population['_year'],
    'population_sudo' => $active_population['sudo'] ]));
// sp is studentPerformance abbrevaiiton
// search fields
$spSearch  = [ 
    [
        'key' => 'student_epita_email', 
        'display' => 'Student Email',
        'default' => true
    ], 
    [
        'key' => 'fname', 
        'display' => 'First Name',  
    ],
    [
        'key' => 'lname', 
        'display' => 'Last Name',  
    ]
];

// for and table id
$spFormID =  $active_population['slug'] . '_spForm';
$spTableID = $active_population['slug'] . '_spTable';
                        
// returns course_code, course name, session count
$courses_and_sessions_query = require controller('queries/courses/SELECT_courses_and_sessions.php');
$courses_and_sessions =   $db->query($courses_and_sessions_query,[
                        'population_code' => $active_population['population_code'], 
                        'population_intake' => $active_population['intake'],
                        ]) -> fetchAllOrAbort() ;


// add token data 
foreach($courses_and_sessions as &$course_and_session){

    $course_and_session['token'] = Validator::encryptData(json_encode([ 
        'population_code' => $active_population['population_code'],
        'population_intake' => $active_population['intake'],
        'population_year' => $active_population['_year'], 
        'population_sudo' => $active_population['sudo'], 
        'population_course_code' => $course_and_session['course_code'],
    ])) ;
}
unset($course_and_session);
                        
// CoSe is course and sessions abbrevaiiton
$CoSeSearch  = [  // course sessionsearch fields
    [
        'key' => 'course_code', 
        'display' => 'Course Code',
        'default' => true
    ], 
    [
        'key' => 'course_name', 
        'display' => 'Course Name',  
    ],

];
//
// course_session_filter
$CoSeFormID =  $active_population['slug'] . '_CoSeForm';
$CoSeTableID = $active_population['slug'] . '_CoSeTable';



require view('populations/population.view.php', [
    
]);
