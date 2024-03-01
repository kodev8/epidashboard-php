<?php
// get all activity for the current user
return "SELECT admin_email as admin, action, description as des , timestamp as time, avatar
from activity
join admins on admin_email = epita_email
where admin_email = :admin_email AND action NOT LIKE '%request%'
order by timestamp desc
";