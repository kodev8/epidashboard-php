<?php
// selects all the courses for a particular program with exam data
return "SELECT 
            program_course_code_ref as course_code, 
            p.program_course_rev_ref as course_rev, 
            e.exam_type as exam_type 
        from programs p 
        join exams e 
        on e.exam_course_code = p.program_course_code_ref
        where p.program_assignment = :population_code";



