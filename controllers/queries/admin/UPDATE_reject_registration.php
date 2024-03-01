<?php 
// used to update the registrations to reject a reuquest

return "UPDATE registrations 
set status = 'rejected'
where epita_email = :epita_email"
;
