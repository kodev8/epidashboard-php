<?php

//get detailed grades for a student 
//it is essentially grades but includes project type and each grade for a program
return  "SELECT GRADE_STUDENT_EPITA_EMAIL_REF AS student_epita_email,
        c.CONTACT_FIRST_NAME AS fname, c.CONTACT_LAST_NAME AS lname,
        g.GRADE_EXAM_TYPE_REF as exam_type,
        co.course_name,
        GRADE_SCORE AS grade FROM  grades g 
        JOIN students s ON s.STUDENT_EPITA_EMAIL = g.GRADE_STUDENT_EPITA_EMAIL_REF 
        JOIN contacts c ON s.STUDENT_CONTACT_REF  = c.CONTACT_EMAIL 
        JOIN courses co on g.grade_course_code_ref = co.course_code
        WHERE  STUDENT_POPULATION_CODE_REF  = :population_code  AND STUDENT_POPULATION_YEAR_REF = :population_year  AND 
        STUDENT_POPULATION_PERIOD_REF = :population_intake AND g.GRADE_COURSE_CODE_REF = :population_course_code
        " . ' ' . ($search ?? '') . ' ' ."
        ORDER BY STUDENT_EPITA_EMAIL, grade desc;";