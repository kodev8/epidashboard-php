<?php 
// updates when resent links are successfully sent
return "UPDATE activity
set action = 'request_handled'
where  id = :activity_id";

