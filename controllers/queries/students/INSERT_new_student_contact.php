<?php
// inserts new contact info
return "INSERT INTO contacts (
`contact_email`,	
`contact_first_name`,	
`contact_last_name`,	
`contact_address`,
`contact_city`,	
`contact_country`,
`contact_birthdate`
)
VALUES 
(
:personal_email,
:fname,
:lname,
:address,
:city,
:country,
:dob
)";