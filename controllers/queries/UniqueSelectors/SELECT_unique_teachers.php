<?php 
// select all unique teachers in in the db for for selecting when creating a new course
return "SELECT DISTINCT teacher_epita_email FROM teachers
ORDER BY  teacher_epita_email";