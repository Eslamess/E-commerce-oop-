<?php
    require_once __DIR__ . '/validation.php';

    class Helpers{
        public static function Url($input){
            return "http://".$_SERVER['HTTP_HOST']."/e-commerce-php/".$input; 
        }
        public static function uploadImage($file_name){
            $Image = $_FILES[$file_name];
            if( $Image['size'] == 0 ){
                Validation::$errors['File'] = "{$file_name} is required";
                return;
            };
            $fileArray = explode('/', $Image['type']);
            $fileExtension = strtolower(end( $fileArray ));
            $allowedExtensions = ['jpeg', 'jpg', 'png'];
            if( !in_array($fileExtension, $allowedExtensions) ){
                Validation::$errors['File'] = 'allowed extensions are only jpeg ,jpg and png';
                return;
            };
            $FinalName = time() . rand() . '.' . $fileExtension;
            $disImg = 'uploads/' . $FinalName;
            if ( !move_uploaded_file($Image['tmp_name'], $disImg) ) {
                Validation::$errors['File'] = 'could not upload the file please try again';
                return;
            };
            return $disImg;
        }
        public static function echoError($name){
            if(isset(Validation::$errors[$name])){
                echo "<div style=color:red class=mb-5>".Validation::$errors[$name]."</div>";
                unset(Validation::$errors[$name]); 
            };
        }
    }
?>