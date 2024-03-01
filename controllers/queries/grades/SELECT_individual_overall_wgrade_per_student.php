<?php 

//overall weighted grade for a single student
return "SELECT round(CAST(sum(g.GRADE_SCORE * e.EXAM_WEIGHT) 
        AS decimal(10,3))/sum(e.EXAM_WEIGHT),2) AS overall_weighted_grade
        FROM  grades g 
        JOIN exams e ON GRADE_EXAM_TYPE_REF = e.EXAM_TYPE  
        AND e.EXAM_COURSE_CODE = g.GRADE_COURSE_CODE_REF 
        WHERE GRADE_STUDENT_EPITA_EMAIL_REF = :student_epita_email ";