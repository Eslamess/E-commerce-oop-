<?php
class Auth {
    public static function checkLogin(){
        if(!$_SESSION['userType']){
            header("Location: index.php");
            exit();
        }
    }
    public static function checkSeller(){
        self::checkLogin();
        if($_SESSION['userType'] != 'seller'){
            header("Location: index.php");
            exit();
        }
    }
    public static function checkCustomer(){
        self::checkLogin();
        if($_SESSION['userType'] != 'customer'){
            header("Location: index.php");
            exit();
        }
    }
    public static function checkNotLoggedIn(){
        if($_SESSION['userType']){
            header("Location: index.php");
            exit();
        }
    }
} 

?>