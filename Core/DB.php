<?php


namespace Core; 

use PDO; 
use Core\Router;

class DB {

    public $conn;
    private $stmt;
    
    public function __construct($db_config) {

    
    // create data source name string for PDO
    $dsn = 'mysql:' . http_build_query($db_config, '', ';');

    
    // use PDO to connect to database
    // avoided using mysqli because of more complex binding for queries
    $this->conn = new PDO($dsn, $db_config['username'],  $db_config['password'], [

        // default fetch is set to use an assoc array
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    }

    public function query(string $query, $params= [], $debug=false){
        $this->stmt = $this->conn->prepare($query);
        $this->stmt->execute($params);
        //for debugging 

        if($debug){
        $this->stmt->debugDumpParams();
        echo '<br>';}

      
        return $this;
    }

    // fetch or abort if no results
    private function fetchAbort($fetched){
        if(!$fetched){
            Router::abort();
        }

        return $fetched;
    }

    // fecth single result
    public function fetchOne(){

        $results = $this->stmt->fetch();
        
        if (empty($results)){
            return null;
        }
        
        return $results;
    }

    // fetch a single result or abort
    public function fetchOneOrAbort(){

        $results = $this->fetchAbort($this->stmt->fetch());
        
        if (count($results) == 0){
            return null;
        }
        
        return $results;

    }
    // fetch  all results 
    public function fetchAll(){

        $results = $this->stmt->fetchAll();
        
        if (empty($results)){
            return null;
        }
        
        return $results;

    }

    // fetch all results or abort
    public function fetchAllOrAbort(){

        $results = $this->fetchAbort($this->stmt->fetchAll());

        if (count($results) == 0){
            return null;
        }
        return $results  ;

    }
}