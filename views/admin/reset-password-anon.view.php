<?php

$title = 'Reset Password';
$intro_folder ='request_reset_password';
require component('custom-input.php');

ob_start()
?>
    
<form class="register-form" id='reset-form' >

<?= customInput(label: 'EPITA Email', name: 'epita_email', input_classes: ['admin', 'span2']) ?>

<div id='inline-error' class="inline-error span2 hide red">
    <i class="fa-solid fa-circle-exclamation"></i>
     <span></span>
    <i  class="fa-solid fa-xmark"></i>
</div>

<?php
$terminal_content = ob_get_clean();
// buffer for registration button content
ob_start();

?>


<a href="/login" class="login-btn">Back To Log in</a>
<input type='submit'  value='Send Reset Request' id='reset-submit-btn' class='login-btn'></input>
 </form>

 <?php
 $button_content = ob_get_clean();

require view('template/login.template.php');


?>

<script src='<?= static_url("scripts/_reset-password-anon.js") ?>' defer></script>




