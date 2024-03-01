<?php 
// inserts a new reistartion request
return "INSERT INTO  registrations (`first_name`, `last_name`, `epita_email`, `password`, `status`)
       VALUES (:fname, :lname, :epita_email, :password_hash, 'pending')";

