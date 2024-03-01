<?php
// used to chekc if a course belongs to a population by subquerying its courses and sessions
$courses_and_sessions = require controller('queries/courses/SELECT_courses_and_sessions.php');
return "SELECT course_code, course_name
        from (
        {$courses_and_sessions}
        ) as courses_and_sessions
        WHERE course_code = :course_code
        LIMIT 1
    ";
