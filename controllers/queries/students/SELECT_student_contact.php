<?php
// selects the contact info of a student
return "SELECT 
contact_email as student_personal_email,
contact_first_name as fname,
contact_last_name as lname,
contact_address as address,
contact_city as city,
contact_country as country,
contact_birthdate as dob,
student_epita_email,
student_population_period_ref as intake, 
student_population_year_ref as _year, 
student_population_code_ref as population_code,
s.student_enrollment_status as enrol_status FROM `contacts` c
JOIN students s on s.student_contact_ref = c.contact_email
where s.student_epita_email = :student_epita_email";