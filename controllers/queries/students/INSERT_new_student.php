<?php
// inserts new student
return "INSERT INTO students (
`student_epita_email`,
`student_contact_ref`,	
`student_enrollment_status`,	
`student_population_period_ref`,	
`student_population_year_ref`,	
`student_population_code_ref`
)

VALUES (
:student_epita_email,
:personal_email,
:enrolment_status,
:population_intake,
:population_year,
:population_code)";
