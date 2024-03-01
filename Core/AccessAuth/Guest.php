<?php

namespace Core\AccessAuth;

class Guest {


    public function handleAccess () {

        if (!empty($_SESSION['auth']) ) {  
            header('Location: /dashboard');
            die();   
        }


    }

}