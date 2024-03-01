<?php
use controllers\populations\PopulationStore;
use Core\DB;
use Core\App;

// read only table of the grades 
$gradebook_query = require controller('queries/grades/SELECT_gradebook.php');

$gradebook = [];
$db = App::resolve(DB::class);
$popStore = App::resolve(PopulationStore::class);
$populations =  $popStore->getPopulations();


foreach ($populations as $population){

    $results = $db -> query($gradebook_query, 
        [
        'population_code' => $population['population_code'], 
        'population_intake' => $population['intake'],
        'population_year' => $population['_year'], 
        ]) ->fetchAllOrAbort();

    // gtoup by code, year intake for display
    $gradebook[$population['population_code']]
    [$population['intake']]
    [$population['_year']]
    = ['results' => $results,
    'slug'  => $popStore->getPopulation($population['population_code'], 
    $population['intake'], $population['_year'])['slug']
        ];
};

require view('gradebook/gradebook.view.php');
