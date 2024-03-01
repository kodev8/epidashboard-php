<?php

namespace Core;
use Core\Response;
use Exception;

class CustomException extends Exception{
    // PHP Exception class has built in getters for message and code
    //  getMessage() and getCode()
    // these will be used to send responses to the clien
    protected $type;

    function __construct($type='toast-error',$message='An error has occured', $code=Response::BAD_REQUEST) {
        // type is whethter to display inlone or via toast
        parent::__construct($message, $code);

        $this->type = $type;
    }

    public function _getType() {
        // getter for the type of the error
        return $this->type;
    }
}

