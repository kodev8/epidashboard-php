<?php

//all students weighted grades for a given course
return  "SELECT GRADE_STUDENT_EPITA_EMAIL_REF AS student_epita_email,
        c.CONTACT_FIRST_NAME AS fname, c.CONTACT_LAST_NAME as lname,
        sum(g.GRADE_SCORE * e.EXAM_WEIGHT)/sum(e.EXAM_WEIGHT) AS w_grade
        FROM  grades g 
        JOIN exams e ON e.EXAM_COURSE_CODE = g.GRADE_COURSE_CODE_REF
        JOIN students s ON s.STUDENT_EPITA_EMAIL = g.GRADE_STUDENT_EPITA_EMAIL_REF 
        JOIN contacts c ON s.STUDENT_CONTACT_REF  = c.CONTACT_EMAIL 
        WHERE  STUDENT_POPULATION_CODE_REF  = :population_code  AND STUDENT_POPULATION_YEAR_REF = :population_year  AND 
        STUDENT_POPULATION_PERIOD_REF = :population_intake AND g.GRADE_COURSE_CODE_REF = :population_course_code
        " . ' ' . ($search ?? '') . ' ' ."
        GROUP BY GRADE_STUDENT_EPITA_EMAIL_REF, GRADE_COURSE_CODE_REF
        ORDER BY w_grade DESC, STUDENT_EPITA_EMAIL;" ;

        