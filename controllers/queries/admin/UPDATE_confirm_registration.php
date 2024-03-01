<?php 
// used to update the registrations to accept a reuqest
return "UPDATE registrations 
set status = 'accepted',  
confirm_at = :confirmation_time
where epita_email = :epita_email";
