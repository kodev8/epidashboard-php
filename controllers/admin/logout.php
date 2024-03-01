<?php

// destroys session
startSession();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['auth']) && $_SESSION['auth'] ) {
    session_destroy();
    session_unset();
    header('Location: /login');
    die();

}

