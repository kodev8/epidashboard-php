<?php
//select the registration where the status is pending and matches a given evemail
return "SELECT first_name as fname, last_name as lname, request_at,  epita_email as admin_email, password
        from registrations
        WHERE status = 'pending' and
        epita_email = :epita_email
        ";