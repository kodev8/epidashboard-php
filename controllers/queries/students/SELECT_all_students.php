
<?php
// all students in epita database for the relevant acadameic years
return  "SELECT STUDENT_EPITA_EMAIL AS student_epita_email, c.CONTACT_FIRST_NAME AS fname, c.CONTACT_LAST_NAME AS lname,
        STUDENT_POPULATION_PERIOD_REF AS intake, STUDENT_POPULATION_CODE_REF AS population_code, 
        STUDENT_POPULATION_YEAR_REF  AS _year
        FROM students s 
        JOIN contacts c ON s.STUDENT_CONTACT_REF = c.CONTACT_EMAIL 
        " . ' ' . ($search ?? '') . ' ' ."
        ORDER BY _year DESC , intake, population_code, lname, fname;";
?>