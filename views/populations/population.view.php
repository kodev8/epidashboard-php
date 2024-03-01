<?php

$title = $active_population['sudo'];

$header1 = "{$active_population['population_code']} {$active_population['intake']} {$active_population['_year']}";

$childView1 = 'populations/partials/student_performance.php';

$childView2 = 'courses/partials/course_sessions.php';
require view('template/base.template.php');