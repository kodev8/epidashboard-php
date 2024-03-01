<?php
use Core\LiveSearch;
use Core\App;
use Core\DB;

    $db = App::resolve(DB::class);
    $get_all_students_query = require controller('queries/students/SELECT_all_students.php');

    $students = $db -> query($get_all_students_query) -> fetchAllOrAbort();

    $searchFilters =[ 
        [
            'key' => 'student_epita_email', 
            'display' => 'Student Email',
            'default' => true
        ], 
        [
            'key' => 'fname', 
            'display' => 'First Name',  
        ],
        [
            'key' => 'lname', 
            'display' => 'Last Name',  
        ]
        ];
  
    $categoryFilters =  [

        [
            'key' => 'population',
            'display' => 'Population',
            'fields' => LiveSearch::resolveCategories('populations')
        ], 
          
          [
              'key' => 'intake',
              'display' => 'Intake',
              'fields' => LiveSearch::resolveCategories('intakes')
            
          ], 
          [
            'key' => 'year',
            'display' => 'Year',
            'fields' => LiveSearch::resolveCategories('years')
        ], 
    
          
        ];

    require view('students/students.view.php')
?>
