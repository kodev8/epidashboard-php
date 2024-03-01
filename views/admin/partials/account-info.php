<?php
require_once component('custom-btn.php')
?>

<div class="account">
    
    <div class="account-container">

        <div id="avatar" class="account-avatar">
                <img src=<?php 
                $avatar = $_SESSION['admin']['avatar']  ?? 'hacker';
                echo static_url("assets/avatars/" . $avatar . ".png")
                 ?> 
                 alt="User avatar">

                <button id='change-avatar'>
                    <i class="fa-solid fa-user-pen"></i>
                </button>

        </div>
    </div> 


    <div class="account-container">

        <div class="account-details">

            <h2 class='title'>Account Details</h2>

            <div class='account-detail-item'>
                <h3> Email: </h3>
                <p> <?= lower($_SESSION['admin']['email']) ?> </p>
            </div>
            <div class='account-detail-item'>
                <h3> First Name: </h3>
                <p> <?= title($_SESSION['admin']['fname']) ?> </p>
            </div>

            <div class='account-detail-item'>
                <h3> Last Name: </h3>
                <p> <?= title($_SESSION['admin']['lname']) ?> </p>
            </div>


                <p class='text-muted sub-detail'>Admin Since <?= $_SESSION['admin']['since'] ?></p>

                <!-- <div class='reset-btn'> -->
                    <?php

                    
                    customBtn('Reset Password', "fa-solid fa-arrow-rotate-left fa-rotate-270", color : 'red', id: 'reset')
                    ?>
                <!-- </div> -->
            
        </div> 
     </div> 

  
            
            
            


</div>
<script src="<?= static_url('scripts/_avatars.js') ?>"></script>
<script src="<?= static_url('scripts/_reset-admin.js') ?>"></script>
