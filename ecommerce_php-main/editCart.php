<?php
    session_start();
    require_once './controllers/customer.controller.php';
    require_once './auth/auth.php';
    require './db/db.connection.php';

    Auth::checkCustomer();
    $user_id = $_SESSION['userId'];
    if(!isset($_GET['q']) || !isset($_GET['id']) || !isset($_GET['s']))
        header("Location: cart.php");

    $s = $_GET['s'];
    $q = $_GET['q'];
    $product_id = $_GET['id'];

    $quantity = ($s == 'plus') ? $q + 1 : $q - 1;
    Customer::editCartItem($con, $product_id, $quantity);

    header("Location: cart.php");
?>