<?php


use Core\LiveSearch;
use Core\Router;
use Core\Validator;
use controllers\populations\PopulationStore;
use Core\App;
use Core\DB;

// epexcts a population followed by a course for routing which will be reolved b the router
$fields = Router::resolveFields('course');

// store of all the piopulation data
$popStore = App::resolve(PopulationStore::class);

// resolve the route to get active population course
$active_population;
$resolvedRoute = Router::resolveRoute($fields, $matches,  $popStore->getPopulations());
if ($resolvedRoute) {
    $active_population = $resolvedRoute['actives']['slug'];
    $url_mapping = $resolvedRoute['url_mapping'];
};

// set up db class
$db = App::resolve(DB::class);

//query all weighted grades
$population_courses = $db -> query(
    require controller('queries/grades/SELECT_population_course_wgrades.php'), [
        'population_code' => $active_population['population_code'],
        'population_intake' => $active_population['intake'],
        'population_year' => $active_population['_year'], 
        'population_course_code' => $url_mapping['course']
    ]
   ) -> fetchAllOrAbort();


   // query detialed courses grades
$population_detailed_courses = $db -> query(
    require controller('queries/grades/SELECT_population_course_detailed_grades.php'), [
        'population_code' => $active_population['population_code'],
        'population_intake' => $active_population['intake'],
        'population_year' => $active_population['_year'], 
        'population_course_code' => $url_mapping['course']
    ]
   ) -> fetchAllOrAbort();

// round and add class based on grade, or set N/A if empty
foreach($population_courses as &$population_course){

    if ($population_course['w_grade']){
        $population_course['w_grade'] =  round((float) $population_course['w_grade'], 2);
        $population_course['grade_class'] =  $population_course['w_grade'] < 10 ? 'failed' : ($population_course['w_grade'] > 13 ? 'success' : 'average') ;
    }else {
        
        $population_course['w_grade'] =  'N/A';
        $population_course['grade_class'] = '';
    }

}
unset($population_course);

//same
foreach($population_detailed_courses as &$population_detailed_course){

    if ($population_detailed_course['grade']){
        $population_detailed_course['grade'] =  round((float) $population_detailed_course['grade'], 2);
        $population_detailed_course['grade_class'] = $population_detailed_course['grade'] < 10 ? 'failed' : ($population_detailed_course['grade'] > 13 ? 'success' : 'average') ;
    }else {
        //na*
        $population_detailed_course['grade'] =  null;
        $population_detailed_course['grade_class'] = '';
    }

    // create token for each row which will be used to extract data from all request types
    $population_detailed_course['token'] = Validator::encryptData(json_encode([ 
        'population_code' => $active_population['population_code'],
        'population_intake' => $active_population['intake'],
        'population_year' => $active_population['_year'], 
        'population_sudo' => $active_population['sudo'], 
        'population_course_code' => $url_mapping['course'],
        'student_epita_email' => $population_detailed_course['student_epita_email'],
        'course_name' => $population_detailed_course['course_name'],
        'exam_type' => $population_detailed_course['exam_type'],
        'grade' => $population_detailed_course['grade']
    ])) ;

}

unset($population_detailed_course);

// searching
// WG  is weighted grade abbreviation
$WGSearch  = [ 
    [
        'key' => 'student_epita_email', 
        'display' => 'Student Email',
        // 'default' => true
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

$sudoAsID = str_replace(' ', '', $active_population['sudo']);

//search form and table id to pair a search bar to a table
$WGFormID =  $sudoAsID . '-'. $url_mapping['course'] .'_WGForm';
$WGTableID = $sudoAsID . '-'. $url_mapping['course'] .'_WG';
                        
// DG  is detailed grade abbreviation
$DGSearch  = $WGSearch;

// since detailed grades and w grades are similar they use the same search fields
// detailed grades has the categories to fileter by when searching
$DGCategory = [
    [
        'key' => 'exam_type',
        'display' => 'Exam Type',
        'fields' => LiveSearch::resolveCategories('exam_types')
    ], 

];

//set up table and form ids
$DGFormID =  $sudoAsID . '-'. $url_mapping['course'] .'_DGForm';
$DGTableID = $sudoAsID . '-'. $url_mapping['course'] .'_DGTable';

//xl file name fir downloading tables
$XLFileName = $active_population['sudo'] . '-' . $url_mapping['course'] . ' Data';
                        

require view('courses/course.view.php');





