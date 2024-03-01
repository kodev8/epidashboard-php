<?php
// 

use Core\Validator;
use Core\LiveSearch;
use Core\App;
use Core\DB;

// same set up for searching as in other fiels
try {

    $db = App::resolve(DB::class);
    

    $expectedSearchFields = [
        'course_code' =>  'ATTENDANCE_COURSE_REF',
        'course_name'   => 'c.COURSE_NAME',
        'course_desc' => 'c.COURSE_DESCRIPTION'
    ];

    $expectedCategoryFields = [
    
        'population' => 's.STUDENT_POPULATION_CODE_REF',
        'year' => 'STUDENT_POPULATION_YEAR_REF',
        'intake' => 's.STUDENT_POPULATION_PERIOD_REF'

    ];

    $output  = LiveSearch::buildQuery($_GET, $expectedSearchFields, $expectedCategoryFields);


    $search = $output['search'];
    // echo $search;
    $get_filtered_courses_query = require controller('queries/courses/SELECT_all_courses.php');
    
    $filtered_courses = $db->query($get_filtered_courses_query, $output['finalParams'])->fetchAll();

    if (empty($filtered_courses)) {
        LiveSearch::returnResults(null);
    }

    // foreach($filtered_courses as &$course){
    //     $slug = slugify([$course['population_code'], $course['intake'], $course['_year']]);
    //     $course['population_slug'] = $slug;
    //     $course['course_url'] = $slug . '/' . lower($course['course_code']);
    // }
    
    unset($course);

    LiveSearch::returnResults($filtered_courses);

}
catch (Error $err){

    (new Validator())->addError($err -> getMessage()) -> sendAPIErrors();

}



?>

