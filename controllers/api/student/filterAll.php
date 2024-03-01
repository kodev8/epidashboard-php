<?php
// 

use Core\Validator;
use Core\LiveSearch;
use Core\App;
use Core\DB;
// dont need to check for request method since router handles this
try {

    $db = App::resolve(DB::class);
    

    // set up expected search fields
    $expectedSearchFields = [
        'student_epita_email' =>  'STUDENT_EPITA_EMAIL',
        'fname'   => 'c.CONTACT_FIRST_NAME',
        'lname' => 'c.CONTACT_LAST_NAME'
    ];

    $expectedCategoryFields = [
    
        'population' => 'STUDENT_POPULATION_CODE_REF',
        'year' => 'STUDENT_POPULATION_YEAR_REF',
        'intake' => 'STUDENT_POPULATION_PERIOD_REF'

    ];

    // build queru dynamically
    $output  = LiveSearch::buildQuery($_GET, $expectedSearchFields, $expectedCategoryFields);

    // create the search variable which will be substituted into the filtered query
    $search = $output['search'];
    $get_filtered_students_query = require controller('queries/students/SELECT_all_students.php');

    $filtered_students = $db->query($get_filtered_students_query, $output['finalParams'])->fetchAll();

    if (empty($filtered_students)) {
        LiveSearch::returnResults(null);
    }


    LiveSearch::returnResults($filtered_students);

}
catch (Error $err){

    (new Validator())->addError($err -> getMessage()) -> sendAPIErrors();

}

?>

