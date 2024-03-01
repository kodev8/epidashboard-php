<?php
// 

use controllers\populations\PopulationStore;
use Core\Validator;
use Core\LiveSearch;
use Core\App;
use Core\DB;


try {

    //set up classes
    $db = App::resolve(DB::class);
    $popStore = App::resolve(PopulationStore::class);
    

    // set up categories and search fields
    $expectedSearchFields = [
        'population' =>  'att.population_code',    
    ];

    $expectedCategoryFields = [
    
    'year' => 'att._year',
    'intake' => 'att.intake'

    ];

    // build dynamic query
    $output  = LiveSearch::buildQuery($_GET, $expectedSearchFields, $expectedCategoryFields);


    // craete search variable to replace dynamic query
    $search = $output['search'];
    $get_filtered_populations_query = require controller('queries/populations/SELECT_all_populations.php');

    // filter the populations
    $filtered_populations = $db->query($get_filtered_populations_query, $output['finalParams'])->fetchAll();

    
    if (empty($filtered_populations)) {
        LiveSearch::returnResults(null);
    }

    // resolve slugs for rebuilt tables
    foreach($filtered_populations as &$population){
        $population['slug'] = $popStore->getPopulation($population['population_code'], $population['intake'], $population['_year'])['slug'];
    }

    unset($population);


    LiveSearch::returnResults($filtered_populations);

}
catch (Error $err){

    (new Validator())->addError($err -> getMessage()) -> sendAPIErrors();

}



?>

