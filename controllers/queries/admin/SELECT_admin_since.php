<?php 
return "
select a.epita_email, r.confirm_at as confirm_at from admins a 
LEFT OUTER join registrations r 
on a.epita_email = r.epita_email 
where a.epita_email = :epita_email
";


