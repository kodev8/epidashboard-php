<?php
// 

use Core\App;
use Core\CustomException;
use Core\DB;
use Core\Validator;

require component('actionModal.php');
require_once component('custom-btn.php');


$db = App::resolve(DB::class);
$validator = new Validator();

if($_SERVER['REQUEST_METHOD'] == 'GET'){

    $modal = (new ActionModal) -> moddalReset();


   // create a new modal with some avatars to select from

   header('Content-Type: text/html');
   echo $modal;

}

elseif ($_SERVER['REQUEST_METHOD'] == 'PATCH') {

    // handle all password vallidation


    
    try{

        $content = trim(file_get_contents("php://input"));
        $_PATCH = json_decode($content, true); 

        $_PATCH = $validator -> normalize_array($_PATCH);
        
        $requiredFields = [
            'old-password' => 'Old Password',
            'new-password' => 'New Password',
            'conf-password' => 'New Password Confirmation'
        ];

        foreach($requiredFields as $field => $display){
            if (!$validator->validate_text($_PATCH[$field], min: 1)){
                throw new CustomException('inline-error', $display . ' is Required!');
            }
        }

        if (!$validator->validate_password($_PATCH['new-password'])){
            throw new CustomException('inline-error', 
            'Passwords must conatin at least: 8 characters, 1 Upper Case Letter, 1 Lower Case Letter, 1 Number and 1 Special Character!');
        }

        if (!$validator->validate_match($_PATCH['new-password'], $_PATCH['conf-password'])){
            throw new CustomException('inline-error', 'Passwords do not match!',);
        }

        $check_existing_admin = $db -> query(require controller('queries/admin/SELECT_admin.php'), [ 
            'admin_email' => $_SESSION['admin']['email']
            ])-> fetchOne();

        if (!password_verify($_PATCH['old-password'], $check_existing_admin['password'])) {
            throw new CustomException('inline-error','Incorrect Old Password.');
        }

        if (password_verify($_PATCH['new-password'], $check_existing_admin['password'])) {
            throw new CustomException('inline-error',"You can't use the same old and new passwords.");
        }

        $check_existing_admin = $db -> query(require controller('queries/admin/UPDATE_admin_password.php'), [ 
            'admin_email' => $_SESSION['admin']['email'],
            'password' => password_hash($_PATCH['new-password'], PASSWORD_BCRYPT)
            ])-> fetchOne();


        $response = [
            'type' => 'success',
            'message' => 'Password Updated Successfully'
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        die();


    }
    catch (CustomException $e){
        (new Validator())->addError($e->_getType(), $e->getMessage()) -> sendAPIErrors($e->getCode());

    }
  
   //
}
