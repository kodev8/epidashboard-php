<?php

use controllers\populations\PopulationStore;
use Core\App;
use Core\LiveSearch;

// resolve class
$popStore = App::resolve(PopulationStore::class);
$populations = $popStore->getPopulations();

//jsonify populations for tables
    $json_populations = json_encode(array_values($populations));

    // search on population code only 
    $searchFilters = [ 
        [
        'key' => 'population', 
        'display' => 'Population Code',
        'default' => true // default search filters cannot be disabled
        ]
    ];

    //chekc box filters
    $categoryFilters =  [
        [
        'key' => 'year',
        'display' => 'Year',
        'fields' => LiveSearch::resolveCategories('years')
        ], 
        [
        'key' => 'intake',
        'display' => 'Intake',
        'fields' => LiveSearch::resolveCategories('intakes')
        ]
    ];
// }
require view('populations/populations.view.php')

?>



