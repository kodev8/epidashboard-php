<?php 
//selects a singular course grade of an exam for a student 
return "SELECT grade_score as grade from grades 
        WHERE grade_student_epita_email_ref = :student_epita_email and
        grade_course_code_ref = :course_code and
        grade_exam_type_ref = :exam_type
        ";