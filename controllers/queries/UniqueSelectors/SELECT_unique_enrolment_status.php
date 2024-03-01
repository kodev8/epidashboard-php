<?php
// select all unique enrolment types in the db
return "SELECT DISTINCT(student_enrollment_status) as enrolment_status FROM students";