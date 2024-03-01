<?php
// gets a specific course grade for a student in a given program
return "SELECT GRADE_STUDENT_EPITA_EMAIL_REF AS student_epita_email, 
        STUDENT_POPULATION_CODE_REF AS population_code, 
        STUDENT_POPULATION_PERIOD_REF AS intake, 
        STUDENT_POPULATION_YEAR_REF  AS _year,
        GRADE_COURSE_CODE_REF AS course_code, c.COURSE_NAME as course_name,
        GRADE_EXAM_TYPE_REF AS exam_type,
        GRADE_SCORE AS grade FROM grades g
        JOIN students s ON s.STUDENT_EPITA_EMAIL = g.GRADE_STUDENT_EPITA_EMAIL_REF 
        JOIN courses c ON c.COURSE_CODE = g.GRADE_COURSE_CODE_REF
        JOIN contacts ct ON ct.CONTACT_EMAIL = s.STUDENT_CONTACT_REF  
        WHERE GRADE_STUDENT_EPITA_EMAIL_REF = :student_epita_email AND
        GRADE_COURSE_CODE_REF = :course_code AND
        GRADE_EXAM_TYPE_REF = :exam_type
        ORDER BY course_code desc, grade DESC";