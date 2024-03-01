<?php
// 

use Core\Validator;
use Core\LiveSearch;
use Core\Router;
use controllers\populations\PopulationStore;
use Core\App;
use Core\DB;

if ($_SERVER['REQUEST_METHOD'] == 'GET'){



    try {
    
        // set up classes and resolve routes
        $fields = Router::resolveFields('course');
        $popStore = App::resolve(PopulationStore::class);


        $active_population;
        $resolvedRoute = Router::resolveRoute($fields, $matches, $popStore->getPopulations());
        if ($resolvedRoute) {
            $active_population = $resolvedRoute['actives']['slug'];
            $url_mapping = $resolvedRoute['url_mapping'];
        };

        $db = App::resolve(DB::class);
    

    // initialize search params and filters f
    $expectedSearchFields = [
        'student_epita_email' =>  'GRADE_STUDENT_EPITA_EMAIL_REF',
        'fname'   => 'c.CONTACT_FIRST_NAME',
        'lname' => 'c.CONTACT_LAST_NAME'
    ];

    $expectedCategoryFields = [
        'exam_type' => 'g.GRADE_EXAM_TYPE_REF',
    ];

        // see other search files and it is essentially the same set up
        $output  = LiveSearch::buildQuery($_GET, $expectedSearchFields, $expectedCategoryFields, prefix: ' AND ');

        $search = $output['search'];

        $active = ['population_code' => $active_population['population_code'],
        'population_intake' => $active_population['intake'],
        'population_year' => $active_population['_year'], 
        'population_course_code' => $url_mapping['course']
            ];

        $finalParams = array_merge($active, $output['finalParams']);

   
        $get_filtered_population_course_detailed_grades_query = require controller('queries/grades/SELECT_population_course_detailed_grades.php');
  

        $filtered_population_course_detailed_grades = $db->query($get_filtered_population_course_detailed_grades_query, $finalParams)->fetchAll();

        if (empty($filtered_population_course_detailed_grades)) {
            LiveSearch::returnResults(null);
        }


        foreach($filtered_population_course_detailed_grades as &$population_detailed_course){

            // set up grade class  and grades for rebuilt tables on search
            if ($population_detailed_course['grade']){
                $population_detailed_course['grade'] =  round((float) $population_detailed_course['grade'], 2);
                $population_detailed_course['grade_class'] =  $population_detailed_course['grade'] < 10 ? 'failed' : ($population_detailed_course['grade'] > 13 ? 'success' : 'average') ;
            }else {
                
                $population_detailed_course['grade'] =  '';
                $population_detailed_course['grade_class'] = '';
            }

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

        LiveSearch::returnResults($filtered_population_course_detailed_grades);

    }
    catch (Error $err){

        (new Validator())->addError($err -> getMessage()) -> sendAPIErrors();
    
    }

}

?>