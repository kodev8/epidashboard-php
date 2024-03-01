<?php
// get the courses and number sessions per course
// this works  by getting all the sessions through the attendance table
// then filtering by the total sessions one student should have attended
// return "SELECT DISTINCT LOWER(ATTENDANCE_COURSE_REF) as course_code,   c.COURSE_NAME AS course_name, 
//         count(ATTENDANCE_STUDENT_REF) as session_count FROM attendance a
//         JOIN students s ON a.ATTENDANCE_STUDENT_REF = s.STUDENT_EPITA_EMAIL
//         JOIN courses c ON c.COURSE_CODE = a.ATTENDANCE_COURSE_REF 
//         WHERE  STUDENT_POPULATION_CODE_REF  = :population_code AND STUDENT_POPULATION_YEAR_REF = :population_year  AND STUDENT_POPULATION_PERIOD_REF LIKE :population_intake
//         AND s.STUDENT_EPITA_EMAIL = (
//         SELECT s2.STUDENT_EPITA_EMAIL FROM students s2 WHERE STUDENT_POPULATION_CODE_REF  = :population_code AND 
//         STUDENT_POPULATION_YEAR_REF = :population_year  AND STUDENT_POPULATION_PERIOD_REF LIKE :population_intake LIMIT 1 
//         ) "
//         GROUP BY ATTENDANCE_COURSE_REF 
//         ORDER BY course_name";

// v2 , previous query relied on students which is not reliablel rehen adding and removing students
return "SELECT
p.program_assignment as population_code,
s.session_population_period as intake,
LOWER(c.course_code) as course_code,
c.course_name as course_name, 
c.course_rev as course_rev,
COUNT(s.session_date) as session_count
FROM sessions s
RIGHT OUTER JOIN programs p ON
s.session_course_ref = p.program_course_code_ref AND
s.session_course_rev_ref = p.program_course_rev_ref
JOIN courses c on 
c.course_code = p.program_course_code_ref
WHERE 
p.program_assignment = :population_code AND
(s.session_population_period is NUll or s.session_population_period = :population_intake)
". ' '. ($search ?? '') . ' ' . "
GROUP BY
p.program_assignment,
c.course_code,
s.session_course_ref,
s.session_course_rev_ref,
s.session_population_period
ORDER BY
session_population_period DESC,
program_assignment,
session_population_period,
session_count desc;
"

?>