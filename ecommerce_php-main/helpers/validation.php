<?php
class Validation {
    public static $errors = [];
    public static function required_input ( $name, $value ){
        if ( empty($value) ) self::$errors[$name] = "{$name} is required";
    }
    public static function minLength ( $name, $value, $length ){
        if ( strlen($value) < $length ){
            self::$errors[$name] = "minimum length of " . $name . " is " . $length;
        };
    }
    public static function validate_string ( $name, $value ){
        if ( !ctype_alpha(str_replace(' ', '', $value)) ){
            self::$errors[$name] = $name . " can contain only letters ( A-Z, a-z ) and spaces";
        };
    }
    public static function checkEmail ($value) {
        if( !filter_var($value, FILTER_VALIDATE_EMAIL) ){
            self::$errors['email'] = "please enter valid email";
        }
    }
    public static function validateDate ($name, $value) {
        $test_arr  = explode('-', $value);
        if (!checkdate($test_arr[1], $test_arr[2], $test_arr[0])) {
            self::$errors['Date'] = "please enter valid Date";
        };
    }
    public static function filterData ($value) {
        return stripslashes(strip_tags(trim( $value )));
    }
    public static function checkNumber($name, $value){
        if(!is_numeric($value)){
            self::$errors[$name] = "$name has to be a valid number";
            return;
        }
        else {
            $value = strval($value);
            return str_replace('-', '', $value);
        }
    }
    public static function checkPhone ($value) {
        if( !preg_match("/^01[0-2,5][0-9]{8}$/",$value)){
            self::$errors['phone'] = "please enter valid phone";
        }
    }
}
?>