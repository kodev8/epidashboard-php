<?php
// select the exams for a given course
return "SELECT 
            exam_course_code as course_code,
            exam_course_rev as course_rev,
            exam_type 
        from exams  
        where exam_course_code = :course_code ";