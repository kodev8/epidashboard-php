<?php

// inserts null grades when adding a new course or new student
return "INSERT INTO grades (
grade_student_epita_email_ref,	
grade_course_code_ref,	
grade_course_rev_ref,	
grade_exam_type_ref,	
grade_score
)

VALUES (
:student_epita_email,
:course_code,
:course_rev,
:exam_type,
NULL
)";
