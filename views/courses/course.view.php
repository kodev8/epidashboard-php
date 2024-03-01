<?php

$header1 = "{$active_population['population_code']} {$active_population['intake']} {$active_population['_year']} - ". upper( $url_mapping['course']) . " Grades";
$childView1 = 'courses/partials/course-wgrade-table.php';

$childView2 = 'courses/partials/course-detailed-grade-table.php';

require view('template/base.template.php');
?>
