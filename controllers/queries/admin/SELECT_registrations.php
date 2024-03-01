<?php
// get all registrations where the status is pending
return "SELECT first_name as fname, last_name as lname, request_at,  epita_email as admin_email
        from registrations
        WHERE status = 'pending'
        ORDER BY request_at
        ";