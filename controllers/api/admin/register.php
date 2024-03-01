<?php
// 

use Core\App;
use Core\DB;
use Core\Validator;
use Core\CustomException;

$validator = new Validator();
$db = App::resolve(DB::class);



try {


    // check all required fields to ensure they are in Post variables
    $requiredFields = [
        'fname' => 'First Name',
        'lname' => 'Last Name',
        'epita_email' => 'EPITA Email',
        'password' => 'Password',
        'conf-password' => 'Password Confirmation'
    ];

    $_POST = $validator -> normalize_array($_POST);


    foreach($requiredFields as $field => $display){
        if (!$validator->validate_text($_POST[$field], min: 1)){
            throw new CustomException('inline-error', $display . ' is Required!');
        }

    }

    // validate all the fields accordingly

    if(!$validator->validate_name( $_POST['fname'] ) || !$validator->validate_name( $_POST['lname'])){
        throw new CustomException('inline-error', 'Please Check First Name And/Or Last Name!', );
    }

    if(!$validator->validate_epita_email( lower( $_POST['epita_email'] ) ) ){
        throw new CustomException('inline-error', 'Invalid Email! Must be from Epita Organization!', );
    }

    if (!$validator->validate_password($_POST['password'])){
        throw new CustomException('inline-error', 
        'Passwords must conatin at least: 8 characters, 1 Upper Case Letter, 1 Lower Case Letter, 1 Number and 1 Special Character!');
    }

    if (!$validator->validate_match($_POST['password'], $_POST['conf-password'])){
        throw new CustomException('inline-error', 'Passwords do not match!',);
    }

    $check_existing_admin = $db -> query(require controller('queries/admin/SELECT_admin.php'), [ 
        'admin_email' => $_POST['epita_email']
        ])-> fetchOne();

    if (!empty($check_existing_admin)){
        throw new CustomException('toast-error', 'An admin with this email already exists!');
    }

    $check_existing_registration = $db -> query(require controller('queries/admin/SELECT_registration.php'), [ 
        'epita_email' => $_POST['epita_email']
        ])-> fetchOne(); 

    if (!empty($check_existing_registration)){
        throw new CustomException('toast-error', 'A registration request has already been sent!');
    }
 
    // insert into registration table with status pending
    $db->query(require controller('queries/admin/INSERT_new_registration.php'), [ 
        'fname' => title($_POST['fname']), 
        'lname' => title($_POST['lname']),
        'epita_email'=> lower($_POST['epita_email']),
        'password_hash' => password_hash($_POST['password'], PASSWORD_BCRYPT)
    ]);;

    $response['type'] = 'success';
    $response['message'] = 'Registration request sent successfully!';


    header('Content-Type: application/json');
    echo json_encode($response);
    die();
}

catch (CustomException $e) {

    $validator->addError($e->_getType(), $e->getMessage()) -> sendAPIErrors();

}
catch (Exception $e) {
    $validator->addError(message:  $e->getMessage()) -> sendAPIErrors();
}

// };