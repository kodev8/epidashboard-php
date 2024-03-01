<?php
// 

use Core\Validator;
use Core\LiveSearch;
use Core\Router;
use Core\App;
use Core\DB;
use controllers\populations\PopulationStore;


if ($_SERVER['REQUEST_METHOD'] == 'GET'){


    try {
        // set up classes and resolve routes
        $db = App::resolve(DB::class);
        $fields = Router::resolveFields('population');
        $popStore = App::resolve(PopulationStore::class);

        $active_population;
        $resolvedRoute = Router::resolveRoute($fields, $matches, $popStore->getPopulations());
        if ($resolvedRoute) {
            $active_population = $resolvedRoute['actives']['slug'];
            $url_mapping = $resolvedRoute['url_mapping'];
        };

        // initialize search params and filters
        $expectedSearchFields = [
            'course_code' =>  'c.course_code',
            'course_name'   => 'c.COURSE_NAME ',
        ];

        $active = [           
            'population_code' => $active_population['population_code'], 
            'population_intake' => $active_population['intake'],
        ];
            
        $output  = LiveSearch::buildQuery($_GET, $expectedSearchFields, prefix: ' AND ');

        // create search varibale to replace dynamic queries
        $search = $output['search'];
        $get_filtered_courses_and_sessions_query = require controller('queries/courses/SELECT_courses_and_sessions.php');

        $filtered_courses_and_sessions =  $db -> query($get_filtered_courses_and_sessions_query, 
                            array_merge($active, $output['finalParams'])) -> fetchAll();

        

        if (empty($filtered_courses_and_sessions)) {
            LiveSearch::returnResults(null);
        }

        foreach($filtered_courses_and_sessions as &$course_session){
            $course_session['course_url'] = $active_population['slug'] . '/'. $course_session['course_code'];
        }
        
        

        unset($course_session);

    



        LiveSearch::returnResults($filtered_courses_and_sessions);

        }
    catch (Error $err){
        
            (new Validator())->addError($err -> getMessage()) -> sendAPIErrors();
        
        }

}

?>

    

    
            
    
           