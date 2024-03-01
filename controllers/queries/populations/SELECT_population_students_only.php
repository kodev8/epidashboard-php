<?php
// select students pf a particular population considering a population as unique group of porogram,  course and year
return "SELECT student_epita_email from students
WHERE  STUDENT_POPULATION_CODE_REF  = :population_code AND STUDENT_POPULATION_YEAR_REF = :population_year
AND STUDENT_POPULATION_PERIOD_REF LIKE :population_intake
";