<?php
// deletes a single grade for a student in a particular course and a particular exam
return "UPDATE grades 
    SET grade_score = NULL
    WHERE grade_student_epita_email_ref = :student_epita_email and 
    grade_course_code_ref = :course_code and
    grade_exam_type_ref = :exam_type
    ";