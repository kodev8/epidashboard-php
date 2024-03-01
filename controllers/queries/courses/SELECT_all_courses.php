<?php

// all courses offered by epita in the database for the acadameic year 2020/2021
return "SELECT  DISTINCT  LOWER(ATTENDANCE_COURSE_REF) AS course_code, c.COURSE_NAME AS course_name, c.COURSE_DESCRIPTION AS course_description ,
        s.STUDENT_POPULATION_CODE_REF AS population_code, STUDENT_POPULATION_YEAR_REF AS _year,
        s.STUDENT_POPULATION_PERIOD_REF AS intake FROM attendance a
        JOIN students s ON a.ATTENDANCE_STUDENT_REF = s.STUDENT_EPITA_EMAIL
        JOIN courses c ON c.COURSE_CODE = a.ATTENDANCE_COURSE_REF "
        . ' '. ($search ?? '') . ' ' . "
        GROUP BY ATTENDANCE_STUDENT_REF, ATTENDANCE_COURSE_REF 
        ORDER BY  _year DESC, population_code, course_name, course_description;";


?>