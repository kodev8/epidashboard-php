<?php
// inserts an exam type of admin override for new course
return "INSERT INTO  Exams (
exam_course_code,	
exam_course_rev,
exam_weight,
exam_type
)
VALUES (
:course_code,
:course_rev,
1, 
'Admin Override'
)
";