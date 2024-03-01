<?php
// select all unique exam types in the db
return "SELECT DISTINCT(exam_type) FROM exams where exam_type != 'admin override' ";