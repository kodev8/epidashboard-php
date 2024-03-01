<?php
// removes all students grades when deleteing a studnet
return "DELETE FROM grades
WHERE grade_student_epita_email_ref = :student_epita_email";