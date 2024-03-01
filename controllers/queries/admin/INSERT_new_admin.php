<?php
// Inserts a new admin by a  user who has superuser
return "INSERT INTO admins
(
first_name,
last_name,	
epita_email,	
password,
superuser,
avatar
)
VALUES (
:fname,
:lname,
:epita_email,
:password,
:superuser, 
'user'
)
";