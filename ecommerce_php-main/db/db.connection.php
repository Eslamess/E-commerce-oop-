<?php  
$server = "localhost";
$dbName = "e_commerce_design";
$dbUser = "root";
$dbPassword= "";

$con = mysqli_connect($server,$dbUser,$dbPassword,$dbName);

if(!$con){
    die('Error '.mysqli_connect_error() );
}

?>