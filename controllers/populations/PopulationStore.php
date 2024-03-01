<?php 

namespace controllers\populations;
use Exception; 

class PopulationStore {

    // attempty to buield singleto store of poulation data
    private static $instance;

    // holds population data
    private static $populations = [];

    // gorups populations by year to for dropdown
    private static $populationByYear = [];

    // expected keys to be in results after query
    private static $popualtion_keys = [
            'population_code',
            'intake',
            '_year',
            'population_count',
            'attendance_rate',
            'total_sessions',
            'total_attended'
        ];

        // no construct or clone method allowed
    protected function __construct() { }

    protected function __clone() { }

    // always returns current instance
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // add a population to the  to the array of beds anwger
    public function addPopulation($population) {

        foreach(self::$popualtion_keys as $popKey) {

            if (!array_key_exists($popKey, $population)){
                throw new Exception("Invalid key: " . $popKey);
            }

        }
    
        $key = self::slugify([$population['population_code'], $population['intake'], $population['_year']]);

        if (array_key_exists($key, self::$populations)){
            return; 
        }

        // additional info for a population i.e population +
        $populationPlus = array_merge($population, [ 
                
            'slug' => $key,
            'sudo' => upper($population['population_code'] . ' ' . substr($population['intake'], 0, 1)) . $population['_year'],
    ]);


        // 
        self::$populations[$key] = $populationPlus;
        // set by year value byt continuously appending
        self::$populationByYear[title("{$population['intake']} {$population['_year']}")][] = $populationPlus;
    }

    // get a population by a unique pair of code intake and year
    public function getPopulation($code, $intake, $year) { 

        $key = self::slugify([$code, $intake, $year]);

        if (!array_key_exists($key, self::$populations)){
            return; 
        }

        return self::$populations[$key];
    }

    

    //getter for populations data
    public function getPopulations($useYear=false) { 
        return $useYear ? self::$populationByYear : self::$populations;
    }

    // to crate sting in the form - - 
    private function slugify(array $to_slugify){
    
        return join('-', array_map('lower', $to_slugify));
    }
    

}

