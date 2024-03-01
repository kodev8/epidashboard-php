<?php
// get the contact email of a student to  check if personal email is already used when adding a new one
return "SELECT contact_email from contacts 
    where contact_email = :personal_email";