<?php

namespace Core; 

use Core\App;
use Core\DB;

class LiveSearch  {

    // used for live search feature of the tables by dynamically building
    // queries to send to the back end

    private static function buildSearchQuery($params, $expected) {

        // builds search queries by checking all the fields it can be searched on 
        // and concatenating each by OR

        $paramsAsKey = array_flip($params);
        $queryAccept = array_intersect_key($expected, $paramsAsKey);

        $searchSql = [];
        foreach($queryAccept as $sqlKey){
            $searchSql[] = "$sqlKey LIKE :search";
        }
        
        return " ( " . implode(' OR ', $searchSql) . " ) ";
    }

    private static function buildCategoryQuery($params, $expected) {

        // filter array for any non empty category paramaters sent by the search
        $filtered = array_filter($params, function($param) {
            return !empty($param);
        });
    
    
        $queryParams = []; // stores the params sent to the sql query
        $sql = []; // sql generated for :key placeholders
        
        //expected is a key val pairing of the name expected in the form and 
        // the column name in for searching sql

        foreach($expected as $key => $value){

            // checks if the expected key is in the filtered array
            if (array_key_exists($key, $filtered)){

                // if yes, we take the value (column name) and add IN
                $tempSql = $value . ' IN (';

                // a count for generating a unique key name to substiute with 
                $count = 0;

                //since we already checked using arraykey exists, we get the values of the form submitted
                // using the expected key
                foreach($params[$key] as $formVal){
                    $sqlParam = $key . $count; // we create a unique name for the sql param
                    $queryParams[$sqlParam] = $formVal; // assing the unique name to the value from the form

                    // to build the correct query, we check if item is the first, if it is not, it is prefect with a comma
                    $tempSql .= ($count != 0 ? ', ' : '') . ":{$sqlParam}" ;
                    // increment the count
                    $count++;
                }
                // end the query with by clossing the search array
                $tempSql .= ')';
                // append the temp sql to the sql array
                $sql[] = $tempSql;
            }
        }

    return [

        'sql' => implode(' AND ', $sql), // final sql to be be placed in the sql query
        'qv' => $queryParams // the query values that would be replaced in the sql
    ];

    }

    public static function buildQuery($get,
                                    $expectedSearchFields = [],
                                    $expectedCategoryFields = [] ,
                                    $prefix = 'WHERE'   
                                    ){

        $finalParams = [];
        $search = '';

        if (!empty($get['searchFilterBy']) && !empty($get['query'])){
            $search = self::buildSearchQuery($get['searchFilterBy'], $expectedSearchFields);
            $finalParams['search'] = "%{$get['query']}%";
            
        }

        if (!empty($get['categoryFilterBy'])){
            // build the category query
            $catQuery = self::buildCategoryQuery($get['categoryFilterBy'], $expectedCategoryFields);

            // if search is alrready initialized, we add the category query prefoxed by and,
            $search .= !empty($search) ? ' AND ' .  $catQuery['sql']: $catQuery['sql'];

            // merge the params for the query with the query values generated
            $finalParams = array_merge($finalParams, $catQuery['qv']);
        }

        // once we have some final params, it is prefixed by where clause
        if (!empty($finalParams)){
            $search = $prefix . ' '.  $search;
            // $finalParams['searchFS'] = $search;
        }


        return [
            'search' => $search,
            'finalParams' => $finalParams
        ];
    }

    public static function resolveCategories($key) {

        // resolves categories by name to return flat array of uniques values 
        // used either for dropdowns or checkboc filters
        $path = 'queries/UniqueSelectors/';
        
        $categoryMap = [
            'populations' => require controller($path . 'SELECT_unique_population_codes.php'),
            'intakes' => require controller($path . 'SELECT_unique_intake_periods.php'),
            'years' => require controller($path . 'SELECT_unique_years.php'),
            'exam_types' => require controller($path . 'SELECT_unique_exam_types.php'),
            'enrolment_status' => require controller($path . 'SELECT_unique_enrolment_status.php'),
            
        ];

        if (array_key_exists($key, $categoryMap)) {
            $db = App::resolve(DB::class);
            $res = $db->query($categoryMap[$key])->fetchAll();

            if(empty($res)){
                return $res;
            }

            $values =[];

                foreach ($res as $item) {
                    foreach ($item as $value) {
                        $values[] = trim($value);
                    }
                }
            return $values;

        }
        return null;
    }

    // return results to the client via json
    public static function returnResults($results){
        header('Content-Type: application/json');
        echo json_encode(['results' => $results]);
        die();

}
}