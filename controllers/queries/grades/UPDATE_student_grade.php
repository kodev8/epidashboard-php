<?php 
// changes a student grade
return "UPDATE grades 
        SET grade_score = :grade
        WHERE grade_student_epita_email_ref = :student_epita_email and 
        grade_course_code_ref = :course_code and
        grade_exam_type_ref = :exam_type
        ";