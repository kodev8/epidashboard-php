<?php
// selects all activiity that does not belong to the current user
return "SELECT  act.id, act.admin_email as admin, act.action, act.description as des , act.timestamp as time, avatar
from activity act
join admins on admin_email = epita_email
where admin_email != :admin_email
and act.action != 'request_handled'
order by timestamp desc
";