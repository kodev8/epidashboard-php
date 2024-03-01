<?php
use Core\App;
use Core\DB;
use Core\LiveSearch;
use controllers\populations\PopulationStore;

// resolve classes
$db = App::resolve(DB::class);
$popStore = App::resolve(PopulationStore::class);

$all_courses_query = require controller('queries/courses/SELECT_all_courses.php');

// fetch all courses from db
$courses = $db -> query($all_courses_query) -> fetchAllOrAbort();

// add a course_url using it's population's courses
foreach($courses as &$course){
    $population = $popStore->getPopulation($course['population_code'], $course['intake'], $course['_year']);
    $course['course_url'] = $population['slug'] . '/' . lower($course['course_code']);
    $course['population_slug']  =  $population['slug'];
}

unset($course);

// filters to search on
$searchFilters =[ 
    [
    'key' => 'course_code', 
    'display' => 'Course Code',
    ], 
    [
        'key' => 'course_name', 
        'display' => 'Course Name',  
    ],
    [
        'key' => 'course_desc', 
        'display' => 'Course Description',  
    ]
    ];

// fileters to checkbox 
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

require view('courses/courses.view.php');
