<?php
// selects all the courses that are not in a given program
return "SELECT  course_code, course_name FROM courses WHERE course_code NOT IN
(SELECT DISTINCT program_course_code_ref from programs p 
 where p.program_assignment = :population_code)
ORDER BY course_name
 ";
