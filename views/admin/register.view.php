<?php
$title = 'Register';
$intro_folder = lower($title);
require component('custom-input.php');
require component('inline-error.php');


// buffer for registration form content
ob_start()
?>
    
<form class="register-form" id='register-form' >

<?= customInput(label: 'First Name', name: 'fname', input_classes: ['admin'], )?>
<?= customInput(label: 'Last Name', name: 'lname', input_classes: ['admin']) ?>

<?= customInput(label: 'EPITA Email', name: 'epita_email', input_classes: ['admin', 'span2']) ?>
<?= customInput(label: 'Password', name: 'password', input_classes: ['admin'],  type: 'password')?>
<?= customInput(label: ' Confirm Password', name: 'conf-password', input_classes: ['admin'], type: 'password')?>

<?php inlineError(); ?>


<?php
$terminal_content = ob_get_clean();
// buffer for registration button content
ob_start();

?>

    <div class="top">
        <a href="/login" class="login-btn">Back To Log in</a>
        <input type='submit'  value='Send Request' id='register-submit-btn' class='login-btn'></input>
    </div>
 </form>

 <?php
 $button_content = ob_get_clean();

require view('template/login.template.php');


?>

<script src='<?= static_url("scripts/_register.js") ?>' defer></script>


