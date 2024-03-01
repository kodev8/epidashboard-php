<?php
// 

use Core\App;
use Core\CustomException;
use Core\DB;
use Core\Validator;

require component('actionModal.php');
require_once component('custom-btn.php');

$content = trim(file_get_contents("php://input"));
$_arr = json_decode($content, true); 
$db = App::resolve(DB::class);

if($_SERVER['REQUEST_METHOD'] == 'GET'){

   // initialize avatar path
$url = base_path('public/static/assets/avatars/');


   // start initial capture of html 
   ob_start()


   ?>

   <div class="avatar-select-container">
   <?php

   // get all png files in at folder
   foreach (glob($url . '*.png') as $file):
      $filename = pathinfo($file, PATHINFO_FILENAME);
   ?>

   
   <div id="<?= $filename ?>" class="avatar-select-item">
      <img src='<?= static_url("assets/avatars/{$filename}.png") ?>'>
   </div>

   <?php endforeach; ?>

   <div class='btn-container span2' style="margin-top:2rem">

<?= customBtn('Confirm Changes', "fa-solid fa-circle-check", 'green hide',  id: 'confirm-avatar'); ?>

</div>

   </div>
  

   <?php
   $modalContent = ob_get_clean(); // get ouput of html

   if (empty($avatars)){
      echo null;
   }

   // create a new modal with some avatars to select from
   $modal = (new ActionModal()) -> modal('Select An avatar', $modalContent);

   header('Content-Type: text/html');
   echo $modal;

}elseif ($_SERVER['REQUEST_METHOD'] == 'PATCH') {

   try{
      if( empty($_arr['avatar_name'])){
         throw new CustomException('toast-error', 'Avatar name is required');
      }

      $_arr['avatar_name'] = lower($_arr['avatar_name']);

      // check if admin exists 

      $avatar = $db->query( require controller('queries/admin/SELECT_admin.php'), [
         'admin_email' => $_SESSION['admin']['email'],
      ])->fetchOne();

      if( empty($avatar)){
         throw new CustomException('toast-error', 'Could not retrieve avatar');
      }

      // check if avatar is the same
      if(lower($avatar['avatar']) ==  $_arr['avatar_name'] ){
         $response = [
            'type' => 'info',
            'message' => 'Avatar is the same, no update made'
         ];
      }
      else{

      
         try {

            $db->query( require controller('queries/admin/UPDATE_avatar.php'), [
               'admin_id' => $_SESSION['admin']['id'],
               'avatar_name' => $_arr['avatar_name']
            ]);

            

         }
         catch(Exception $e){
            throw new CustomException('toast-error', 'Unable to update avatar!');
            
         }

         $_SESSION['admin']['avatar'] = $_arr['avatar_name'];

         $response = [
            'type' => 'success',
            'message' => 'Avatar updated successfully'
         ];
         }

      header('Content-Type: application/json');
      echo json_encode($response);
      die();


   }
   catch (CustomException $e){
      (new Validator())->addError($e->_getType(), $e->getMessage()) -> sendAPIErrors($e->getCode());

   }
  
   //
}
