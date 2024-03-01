<?php 
// inserts a new activity like ediit delete or add to the db
return "INSERT INTO activity 
(
admin_email,
action,
description
)
VALUES (
:admin_email,
:action,
:description
)";