<?php
    session_start();
    require_once './controllers/customer.controller.php';
    require_once './auth/auth.php';
    require './db/db.connection.php';

    Auth::checkCustomer();
    $user_id = $_SESSION['userId'];
    $product_id = $_GET['id'];

    Customer::deleteCartItem($con, $product_id);
    header("Location: cart.php");
?>