<?php
// used to check if a user has previous registered
return "SELECT epita_email
        from registrations where
        epita_email = :epita_email
        ";