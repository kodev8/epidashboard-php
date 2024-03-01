<?php
// 

// fitlters Session Performance
use Core\Validator;
use Core\LiveSearch;
use Core\Router;
use controllers\populations\PopulationStore;
use Core\App;
use Core\DB;

if ($_SERVER['REQUEST_METHOD'] == 'GET'){


    try {

        // same search set up as other search files
   
        $fields = Router::resolveFields('population');
        $popStore = App::resolve(PopulationStore::class);


        $active_population;
        $resolvedRoute = Router::resolveRoute($fields, $matches, $popStore->getPopulations());
        
        if ($resolvedRoute) {
            $active_population = $resolvedRoute['actives']['slug'];
            $url_mapping = $resolvedRoute['url_mapping'];
        };


        $expectedSearchFields = [
            'student_epita_email' =>  'student_epita_email',
            'fname'   => 'fname',
            'lname' => 'lname'
        ];

        $active = [           
            'population_code' => $active_population['population_code'], 
            'population_intake' => $active_population['intake'],
            'population_year' => $active_population['_year'], 
        ];
            
            $output  = LiveSearch::buildQuery($_GET, $expectedSearchFields);


            $search = $output['search'];
            $get_filtered_passed_query = require controller('queries/grades/SELECT_passed.php');

            $db = App::resolve(DB::class);
            $filtered_student_performance =  $db -> query($get_filtered_passed_query, 
                                array_merge($active, $output['finalParams'])) -> fetchAll();

            

            if (empty($filtered_student_performance)) {
                LiveSearch::returnResults(null);
            }


            // create token for form validation and passinf encrypted data
            foreach($filtered_student_performance as &$student){
                $student['token'] = Validator::encryptData(json_encode([
                    'population_code' => $active_population['population_code'], 
                    'population_intake' => $active_population['intake'],
                    'population_year' => $active_population['_year'],
                    'population_sudo' => $active_population['sudo'],
                    'student_epita_email' => $student['student_epita_email']
                ]));
            }

            unset($stduent);



            LiveSearch::returnResults($filtered_student_performance);

    }
    catch (Error $err){
        
            (new Validator())->addError($err -> getMessage()) -> sendAPIErrors();
        
        }

}

?>

    

    
            
    
           