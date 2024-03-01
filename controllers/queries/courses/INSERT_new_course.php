<?php
// Insert a new course to the db
return "INSERT INTO courses (
course_code,
course_rev,	
duration,	
course_last_rev,
course_name,
course_description
)
VALUES (
:course_code,
:course_rev,
:duration, 
:course_last_rev,
:course_name, 
:course_desc
)
";