<?php

// $query = $search ?? '';

// get attendance and population count for programs
// return  "SELECT round(sum(program_rate)/count(student_ref),2) AS attendance_rate, 
//             sum(n_attended) AS total_attended, sum(n_sessions) AS total_sessions, 
//             count(student_ref) AS population_count, 
//             _year, 
//             intake_ref as intake, 
//             population_ref as population_code from
//                 (
//                 SELECT ATTENDANCE_STUDENT_REF AS student_ref,
//                 sum(ATTENDANCE_PRESENCE) AS n_attended, 
//                 count(ATTENDANCE_STUDENT_REF) AS n_sessions, 
//                 CAST(sum(ATTENDANCE_PRESENCE) * 100 AS decimal(10,3)) / count(ATTENDANCE_STUDENT_REF) AS program_rate
//                 FROM attendance a 
//                 GROUP BY ATTENDANCE_STUDENT_REF 
//                 ORDER BY program_rate, student_ref, n_attended
//                 ) AS program_attendance_rate
//                 JOIN
//                 (SELECT STUDENT_EPITA_EMAIL AS student_epita_email, 
//                 STUDENT_POPULATION_YEAR_REF as _year, 
//                 STUDENT_POPULATION_PERIOD_REF AS intake_ref,
//                 STUDENT_POPULATION_CODE_REF AS population_ref
//                 FROM students s
//                 ORDER BY _year) AS program
//                 ON program_attendance_rate.student_ref = program.student_epita_email" 
//                 . ' '. ($search ?? '') . ' ' .
//                 "GROUP BY _year, intake, population_code
//                 ORDER BY _year DESC, intake, population_code 
//                 ;";



// previous query relied on student which is not reliable when adding or removing students
// this achieves a similar result
return "SELECT 
            att.population_code AS population_code ,
            att.intake AS intake,
            att._year as _year,
            pcount.pop_count as population_count, 
            att.total_presence as total_attended,
            att.attendance_count as total_sessions,
            round(att.rate, 2) as attendance_rate
        FROM (
            SELECT
                s.student_population_code_ref AS population_code,
                s.student_population_period_ref AS intake,
                s.student_population_year_ref as _year,
                count(*) as pop_count
            FROM students s
            GROUP BY 
                    s.student_population_code_ref, 
                    s.student_population_period_ref,
                    s.student_population_year_ref
            ) AS pcount
        JOIN 
            (
            SELECT
                s.student_population_code_ref AS population_code,
                s.student_population_period_ref AS intake,
                s.student_population_year_ref as _year, 
                SUM(attendance_presence) AS total_presence,
                COUNT(attendance_presence) AS attendance_count,
                CAST(sum(ATTENDANCE_PRESENCE) * 100 AS decimal(10,3)) / COUNT(attendance_presence) 			AS rate
            FROM attendance a
            JOIN students s ON a.attendance_student_ref = s.student_epita_email
            GROUP BY 
                s.student_population_code_ref,
                s.student_population_period_ref,
                s.student_population_year_ref
            ) AS att
            
        ON att.population_code = pcount.population_code
        AND att.intake = pcount.intake
        ". ' '. ($search ?? '') . ' ' ." 
        ORDER BY
            att._year DESC,
            att.population_code";

?>