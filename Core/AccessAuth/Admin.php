<?php

namespace Core\AccessAuth;

class Admin {


    public function handleAccess () {

        if (empty($_SESSION['auth']) ) {  
            header('Location: /login');
            die();   
        }

    }

}