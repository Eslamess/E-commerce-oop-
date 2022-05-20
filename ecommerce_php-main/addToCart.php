<?php
    session_start();
    require_once './controllers/customer.controller.php';
    require_once './auth/auth.php';
    require './db/db.connection.php';
    Auth::checkCustomer();
    $user_id = $_SESSION['userId'];
    $product_id = $_GET['id'];

    // check if user already have this cart
    $sql = "SELECT c.* FROM products AS p
    LEFT JOIN cart_items AS c 
    ON p.id = c.product_id 
    WHERE c.client_id = $user_id AND c.product_id = $product_id;";
    $result = SQL::doQuery($con, $sql);
    $data = mysqli_fetch_assoc($result);
    var_dump($data);
    if (!$data){
        Customer::addCartItem($con, $product_id, 1);
    }
    else {
        if( $data['client_id'] != $user_id )
            exit();
        $quantity = $data['quantity'] + 1;
        Customer::editCartItem($con, $product_id, $quantity);
    }
    header("Location: cart.php");
?>