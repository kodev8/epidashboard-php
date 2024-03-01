<?php

use Core\App;
use Core\DB;
use Core\Validator;
use Core\CustomException;
use Core\Response;

$validator = new Validator();
$db = App::resolve(DB::class);


try {

    $requiredFields = [
        'username' => 'Email or Username',
        'password' => 'Password',
    ];



    // validate the login form fields to ensure that they are not empty
    foreach($requiredFields as $field => $display){

        // check if to trim with validate text, (should not trim passwords)
        $trim = true;
        if (str_contains($field, 'password')){
            $trim = false;
        }

        if (!$validator->validate_text($_POST[$field], min: 1, trim: $trim)){
            throw new CustomException('inline-error', $display . ' is Required!');
        }
    }

    $_POST['username']= $validator -> normalize_text($_POST['username']);


    
    // if the pattern does not match try via username
    $username = $_POST['username'];

    if (!$validator->validate_epita_email($username)){
        $username = $username . '@epita.fr';
    }

    $user = $db -> query(require controller('queries/admin/SELECT_admin.php'), [ 
                'admin_email' => $username
                ])-> fetchOne();
    

    if (empty($user) || !password_verify($_POST['password'], $user['password'])){
        throw new CustomException('toast-error', 'Unable to Log In! Check your Email/Username and Password', Response::UNAUTHORIZED);  
    }

    $admin_since = $db -> query(require controller('queries/admin/SELECT_admin_since.php'), [
        'epita_email' => $user['epita_email']
    ])->fetchOne();

    $confirm_at = !empty($admin_since['confirm_at']) ? date("M d, Y", strtotime($admin_since['confirm_at']))  : 'ORIGIN';


    // if all validation passed, add session variables and authenticate the session
    $_SESSION['admin'] = [

        'id' => $user['id'],
        'email' => $user['epita_email'],
        'fname' => $user['fname'],
        'lname' => $user['lname'],
        'superuser' => $user['superuser'],
        'avatar' => $user['avatar'],
        'username' => rtrim($user['epita_email'], '@epita.fr'),
        'since' => $confirm_at

    ];

    $_SESSION['auth'] = true;

    if (empty($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(random_bytes(10));
    }


    

    $response = ['type' => 'success',
                'message' => 'Login successful: ' . $user['epita_email']
                ];
    
    header('Content-Type: application/json');
    echo json_encode($response);

}catch (CustomException $e) {

    $validator->addError($e->_getType(), $e->getMessage()) -> sendAPIErrors($e->getCode());
}
catch (Exception $e) {
    $validator->addError(message: $e->getMessage()) -> sendAPIErrors();

}
