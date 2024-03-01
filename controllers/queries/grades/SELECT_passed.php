<?php

// gets the number of course passed by a student based on their weighted grade
//:_population_code, :population_year, :population_intake
$get_wgrades_query = require controller('queries/grades/SELECT_gradebook.php');


return "SELECT student_epita_email, fname, lname, sum(passed) as passed_courses, 
            count(passed) as total_courses FROM (
        {$get_wgrades_query}   
            )  as wgrades
            ". ' ' . ($search ?? '') . ' ' . "
            GROUP BY wgrades.student_epita_email;";

