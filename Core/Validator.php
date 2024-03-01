<?php

namespace Core;
use Core\Response;
use DateTime;
use DateInterval;

class Validator {

    // const SALT = 'kalev05042001xyz'; // initially used fo form validation
    
    // errors store to send to client
    protected $errors = [];

    //regex patterns to validate certien fields
    const student_epita_email_pattern = '/^[a-zA-Z0-9._%+-]+@epita\.fr$/'; 
    const password_pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9\s]).{8,}/'; // satck overflow
    const name_pattern = '/^[a-zA-z][a-zA-z-.\'\s]*[a-zA-z]?$/';
    const course_pattern = '/^[a-zA-z][a-zA-z_]+[a-zA-z]?$/';

   

    public function validate_text($text, $min = 1, $max = 100, $trim = true) {
        // trims text and ensures it is between min and max
        $val = $trim ? trim($text) : $text;
        return strlen($val) >= $min &&  strlen($val) <= $max;
    }

    //validate any email pattern
    public function validate_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // validate any epita email
    public function validate_epita_email($email) {
        // validates epita email using regex
        return $this->validate_email($email) && preg_match($this::student_epita_email_pattern, $email);
    }

    
    public function validate_password($password) {
        // validates password using regex at least 8 char, 1 upper, 1 lower, 1 number 1 special char
       return preg_match($this::password_pattern, $password);
        
    }


    public  function validate_int($num, $min = 0, $max= 20) {
        // validate nums to be within a given range
        return (is_numeric($num) && $num >= $min && $num <= $max) ;
    }

    // check if two strings are the same
    public  function validate_match($string1, $string2) {
        // validate nums to be 0 - 20
        return $string1 == $string2 ;
    }

    // validate name, course names, student names etc
    public function validate_name($name) {
        $name = trim($name);
        return $this->validate_text($name, min: 1) && preg_match($this::name_pattern, $name);
    }

    // validate course codes - only letter and underscores
    public function validate_course_code($cname) {
        $cname = trim($cname);
        return $this->validate_text($cname, min: 3) && preg_match($this::course_pattern, $cname);
    }

    //minimum 15 years olf for students
    public function validate_dob($date) {

        // must be at least 15 years old
        $currentDate = new DateTime();
        $currentDate->sub(new DateInterval('P15Y'));
        $fifteenYearsAgo = $currentDate->format('Y-m-d');

        
        //format input date
        $inputDate = date('Y-m-d', strtotime($date));
        return $inputDate < $fifteenYearsAgo;
    }


    public function normalize_text($text){
        return trim(lower($text));
    }

    public function normalize_array($_arr) {
        if (!empty($_arr)){
            foreach($_arr as $key => $value){
                $_arr[$key] = $this->normalize_text($value);
            }
        }
        return $_arr;
    }


    // Encryption function for generating tokens
    public static function encryptData($data) {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $_SESSION['token'], 0, $iv);
        $result = base64_encode($iv . $encrypted);

        return $result;
    }

    // Decryption function for gettin info back from tokens
    public static function decryptData($data) {
        // Decode the data
        $decoded = base64_decode($data);
        $iv = substr($decoded, 0, openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = substr($decoded, openssl_cipher_iv_length('aes-256-cbc'));
        $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $_SESSION['token'], 0, $iv);

        return $decrypted;
    }

    
    // add en error to the the errors error
    public function addError(string $errorType='toast-error', string $message='An error occured') {
        // add error for json response to createToast/other handle
        $this->errors = [
            'type' => $errorType,
            'message' => $message
    ];
        return $this;
    }

    // Send errors back to the client
    public function sendAPIErrors($code=RESPONSE::BAD_REQUEST){
        if (!empty($this->errors)){ 
            http_response_code($code);
            header('Content-Type: text/json');
            echo json_encode($this->errors);
            die();
        }
    }
}


