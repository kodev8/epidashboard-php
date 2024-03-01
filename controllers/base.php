<?php

use controllers\populations\PopulationStore;
use Core\App;
use Core\DB;

if (!empty($_SESSION['auth']) && $_SESSION['auth']){

        $db = App::resolve(DB::class);

        $get_populations_query = require controller('queries/populations/SELECT_all_populations.php');

        $population_by_year = [];

        $populations = $db->query($get_populations_query)->fetchAllOrAbort();
        $populationStore = App::resolve(PopulationStore::class);

        foreach($populations as $population){
                $populationStore->addPopulation($population);
        }
}








