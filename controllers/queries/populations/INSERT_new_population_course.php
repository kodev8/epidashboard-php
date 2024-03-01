<?php
// adds a course to a population
return  "INSERT INTO programs (
program_course_code_ref,
program_course_rev_ref,	
program_assignment
)
VALUES 
(
:course_code,
:course_rev,
:population_code
)
";