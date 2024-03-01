<?php

// single entry point to the application 
$router->get('/', 'index.php'); 


// GEUST ROUTES
// log in route
$router->get('/login', 'admin/login.php')->addAccessAuth('guest');
$router->post('/login', 'api/admin/login.php')->addAccessAuth('guest');

//reset password - from login
$router->get('/reset-password', 'admin/reset-password-anon.php')->addAccessAuth('guest');; // get reset pag
$router->post('/reset-password', 'api/admin/reset-password-anon.php')->addAccessAuth('guest');; // send reset request

//registration routes
$router->get('/register', 'admin/register.php')->addAccessAuth('guest');
$router->post('/register', 'api/admin/register.php')->addAccessAuth('guest'); // send a registration request



// ADMIN ROUTES

// log out route - https://stackoverflow.com/questions/3521290/logout-get-or-post
$router->post('/logout', 'admin/logout.php')->addAccessAuth('admin'); 


//dashboard routes
$router->get('/dashboard', 'admin/dashboard.php')->addAccessAuth('admin'); 

//population routes
$router->get('/populations', 'populations/index.php')->addAccessAuth('admin'); // get all populations
$router->get('regex#^/populations/([a-zA-Z-_0-9]+)/?$#', 
                'populations/population.php')->addAccessAuth('admin'); //get a single population
$router->get('regex#^/populations/([a-zA-Z-_0-9]+)/([a-zA-Z-_0-9]+)/?$#', 
            'courses/course.php')->addAccessAuth('admin'); // get the courses for a single population

$router->get('/api/populations', 'api/populations/filterAll.php')->addAccessAuth('admin'); // filter all populations
$router->get('regex#^/api/filterSP/([a-zA-Z-_0-9]+)/?$#', 
                'api/populations/filterSP.php')->addAccessAuth('admin');  //filter student student performance

$router->get('regex#^/api/filterCoSe/([a-zA-Z-_0-9]+)/?$#', 
                'api/populations/filterCoSe.php')->addAccessAuth('admin');  //filter courses_and_sessions using regex to resolve routes
                
$router->get('regex#^/api/population/([a-zA-Z-_0-9]+)/([a-zA-Z-_0-9]+)/filterWG#', 
                'api/populations/filterCourseWG.php')->addAccessAuth('admin'); // filter weighted grade table of a course using regex to resolve routes

$router->get('regex#^/api/population/([a-zA-Z-_0-9]+)/([a-zA-Z-_0-9]+)/filterDG#',  
                'api/populations/filterCourseDG.php')->addAccessAuth('admin'); // filter detailed grade table of a course using regex to resolve routes


// student routes
$router->get('/students', 'students/index.php')->addAccessAuth('admin'); // all students
$router->get('/student', 'students/student.php')->addAccessAuth('admin'); // get a single student

$router->post('/api/student/modal', 'api/student/modal.php')->addAccessAuth('admin'); // add/delete a student
$router->patch('/api/student/edit', 'api/student/edit.php')->addAccessAuth('admin'); // confirm edit a student
$router->delete('/api/student/delete', 'api/student/delete.php')->addAccessAuth('admin'); // confirm delete a student
$router->post('/api/student/add', 'api/student/add.php')->addAccessAuth('admin'); //  confirm add a student

$router->get('/api/students', 'api/student/filterAll.php')->addAccessAuth('admin'); // filter all populations
$router->get('/api/student', 'api/student/filterSIG.php')->addAccessAuth('admin'); // filter all student individual grades



//gradebook routes
$router->get('/gradebook', 'grades/index.php')->addAccessAuth('admin'); // all the grades

// api grade routes
$router->post('/api/grade/modal', 'api/grade/modal.php')->addAccessAuth('admin'); // modal to edit/delete a grade
$router->patch('/api/grade/edit', 'api/grade/edit.php')->addAccessAuth('admin'); // confirm edit a student grade
$router->delete('/api/grade/delete', 'api/grade/delete.php')->addAccessAuth('admin'); // confirm delete a student grade


//course routes
$router->get('/courses', 'courses/index.php')->addAccessAuth('admin'); // get all the courses
$router->get('/api/courses', 'api/course/filterAll.php')->addAccessAuth('admin'); // filter all courses


$router->post('/api/course/modal', 'api/course/modal.php')->addAccessAuth('admin'); // modal to add a course
$router->post('/api/course/add', 'api/course/add.php')->addAccessAuth('admin'); // add a course

//profile
$router->get('/profile', 'admin/profile.php')->addAccessAuth('admin'); // get admin profile

$router->get('/avatars', 'api/admin/avatars.php')->addAccessAuth('admin'); // get the avatar modal
$router->patch('/avatars', 'api/admin/avatars.php')->addAccessAuth('admin'); // update the avatar


//activity 
$router->get('/activity', 'api/admin/activity.php')->addAccessAuth('admin'); // get the admin page
$router->post('/activity', 'api/admin/activity.php')->addAccessAuth('admin'); // controls the tabs on activity section

$router -> post('/admin/accept', 'api/admin/accept.php')->addAccessAuth('admin'); // approve a registration request
$router -> post('/admin/reject', 'api/admin/reject.php')->addAccessAuth('admin'); // reject a registration request

//reset password
$router->get('/admin/reset-password', 'api/admin/reset-password-admin.php')->addAccessAuth('admin'); // get reset pag
$router->patch('/admin/reset-password', 'api/admin/reset-password-admin.php')->addAccessAuth('admin'); // send reset request


$router->patch('/reset-password/send', 'api/admin/reset-password-send.php')->addAccessAuth('admin'); // send reset request










