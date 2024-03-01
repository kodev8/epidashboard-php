<?php
// 

use Core\Validator;
use Core\LiveSearch;
use Core\Router;
use controllers\populations\PopulationStore;
use Core\App;
use Core\DB;

if ($_SERVER['REQUEST_METHOD'] == 'GET'){

 // same set up for filterCourseDG

    try {
    
        $fields = Router::resolveFields('course');
        $popStore = App::resolve(PopulationStore::class);


        $active_population;
        $resolvedRoute = Router::resolveRoute($fields, $matches,  $popStore->getPopulations());
        if ($resolvedRoute) {
            $active_population = $resolvedRoute['actives']['slug'];
            $url_mapping = $resolvedRoute['url_mapping'];
        };

    

    $expectedSearchFields = [
        'student_epita_email' =>  'GRADE_STUDENT_EPITA_EMAIL_REF',
        'fname'   => 'c.CONTACT_FIRST_NAME',
        'lname' => 'c.CONTACT_LAST_NAME'
    ];

        $output  = LiveSearch::buildQuery($_GET, $expectedSearchFields, prefix: ' AND ');


        $search = $output['search'];

        $active = ['population_code' => $active_population['population_code'],
        'population_intake' => $active_population['intake'],
        'population_year' => $active_population['_year'], 
        'population_course_code' => $url_mapping['course']
            ];

        $db = App::resolve(DB::class);

        $finalParams = array_merge($active, $output['finalParams']);

        $get_filtered_population_course_wgrades_query = require controller('queries/grades/SELECT_population_course_wgrades.php');
        
        
        $filtered_population_course_wgrades = $db->query($get_filtered_population_course_wgrades_query, $finalParams)->fetchAll();

        if (empty($filtered_population_course_wgrades)) {
            LiveSearch::returnResults(null);
        }


        foreach($filtered_population_course_wgrades as &$population_course){

            if ($population_course['w_grade']){
                $population_course['w_grade'] =  round((float) $population_course['w_grade'], 2);
                $population_course['grade_class'] =  $population_course['w_grade'] < 10 ? 'failed' : ($population_course['w_grade'] > 13 ? 'success' : 'average') ;
            }else {
                
                // na*
                $population_course['w_grade'] =  '';
                $population_course['grade_class'] = '';
            }
        
        }
        unset($population_course);

        LiveSearch::returnResults($filtered_population_course_wgrades);

    }
    catch (Error $err){

        (new Validator())->addError($err -> getMessage()) -> sendAPIErrors();
    
    }

}

?>