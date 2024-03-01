<?php

$title = 'Login';
$intro_folder = lower($title);

require component('custom-input.php');
require component('inline-error.php');
// buffer for terminal content
ob_start()
?>
    <div class="terminal-line">
        <p class="terminal-text shell"> <?= $random_welcome["shell"] ?></p>
        <div class="block">
            <p id="typewriter-1" class="terminal-text"><?= $random_welcome["command"]  . ' ' ?> welcome.txt</p>
        </div>
    </div>

    <div class="terminal-line">
        <div class="block">
            <p id="typewriter-2" class="terminal-text">Welcome To Student Admin Page!</p>
        </div>
    </div>
        

    <div class="terminal-line">
        <p id="terminal-line-3" class="terminal-text shell"><?= $random_welcome["shell"] ?></p>
            <div class="block">
                <p id="typewriter-3" class="terminal-text"><?= $full_script ?> </p>
            </div>
        </div>
    
    <form id="login-form">
            <div class="admin-block">
                    
                
            <?= customInput(label: 'Email or Username', name: 'username', input_classes: ['admin','terminal-text'], divid: 'typewriter-4') ?>

                        
            </div>
     
            <div class="admin-block">
                
            <?= customInput(label: 'Password', name: 'password', input_classes: ['admin', 'terminal-text'], type: 'password', divid: 'typewriter-5') ?>

       
        </div>  
        <?php inlineError(); ?>

<?php 

$terminal_content = ob_get_clean();

//buffer for button content
ob_start();

?>

<div class="top">
    <input  id="login" type="submit" value="Log in" name="log-in" class="login-btn"></input>
    <a href="/register" class="login-btn">Request to Register</a>
</div>
    </form> 
    <span class='reset-link span2'>Having trouble Logging in? <a href="/reset-password">Request Password Reset</a></span>

<?php
$button_content = ob_get_clean();
require view('template/login.template.php')
?>

<script src= <?= static_url("scripts/_login.js") ?> defer></script>
