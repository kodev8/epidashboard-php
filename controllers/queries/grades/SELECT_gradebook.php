<?php 

// all respective students weighted grades per program per course
return "SELECT GRADE_STUDENT_EPITA_EMAIL_REF AS student_epita_email,
        ct.CONTACT_FIRST_NAME AS fname, ct.CONTACT_LAST_NAME AS lname,
        c.COURSE_NAME  AS course_name, LOWER(g.GRADE_COURSE_CODE_REF) AS course_code,
        sum(g.GRADE_SCORE * e.EXAM_WEIGHT)/sum(e.EXAM_WEIGHT) AS w_grade,
        CASE 
            WHEN sum(g.GRADE_SCORE * e.EXAM_WEIGHT)/sum(e.EXAM_WEIGHT) > 9 THEN 1
            ELSE 0
        END AS passed
        FROM  grades g 
        JOIN exams e ON e.EXAM_COURSE_CODE = g.GRADE_COURSE_CODE_REF
        JOIN courses c ON c.COURSE_CODE = g.GRADE_COURSE_CODE_REF 
        JOIN students s ON s.STUDENT_EPITA_EMAIL = g.GRADE_STUDENT_EPITA_EMAIL_REF 
        JOIN contacts ct ON s.STUDENT_CONTACT_REF  = ct.CONTACT_EMAIL 
        WHERE  STUDENT_POPULATION_CODE_REF  = :population_code AND STUDENT_POPULATION_YEAR_REF = :population_year
        AND STUDENT_POPULATION_PERIOD_REF LIKE :population_intake
        GROUP BY GRADE_STUDENT_EPITA_EMAIL_REF, GRADE_COURSE_CODE_REF
        ORDER BY STUDENT_EPITA_EMAIL, w_grade DESC";
?>


