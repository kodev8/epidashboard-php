<?php 
// can use SELECT * but just to be explicit, select all the data for a given admin
return "SELECT id, first_name as fname, last_name as lname, epita_email, password, superuser, avatar from admins 
        WHERE epita_email = :admin_email
        ";