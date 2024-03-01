<?php
// updates the names of a student
return "UPDATE contacts 
        set contact_first_name = :fname,
        contact_last_name = :lname
        where contact_email = :student_contact_email";