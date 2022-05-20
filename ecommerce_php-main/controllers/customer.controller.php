<?php
require_once __DIR__ .'/../auth/auth.php';
require_once __DIR__ .'/../db/db.controller.php';

class Customer {
    /** Ratings */
    public static function addRating($con, $stars, $text){
        Auth::checkCustomer();
        $user_id = $_SESSION['userId'];
        SQL::insert(
            $con,
            'ratings',
            '(user_id, stars, text)',
            "('$user_id','$stars','$text')"
        );
    }
    public static function editRating($con, $product_id, $stars, $text){
        Auth::checkCustomer();
        $user_id = $_SESSION['userId'];
        SQL::update(
            $con,
            'ratings',
            "stars = '$stars', text = '$text'",
            "user_id = '$user_id' AND product_id = $product_id"
        );
    }
    public static function deleteRating($con, $product_id){
        Auth::checkCustomer();
        $user_id = $_SESSION['userId'];
        SQL::delete(
            $con,
            'ratings',
            "user_id = '$user_id' AND product_id = '$product_id'"
        );
    }
    /** cart Items */
    public static function addCartItem( $con, $product_id, $quantity){
        Auth::checkCustomer();
        $user_id = $_SESSION['userId'];
        SQL::insert(
            $con,
            'cart_items',
            '(client_id, product_id, quantity)',
            "('$user_id', '$product_id', '$quantity')",
            'Item was added to the product successfully'
        );
    }
    public static function deleteCartItem($con, $product_id){
        Auth::checkCustomer();
        $user_id = $_SESSION['userId'];
        SQL::delete(
            $con,
            'cart_items',
            "product_id = '$product_id' AND client_id = '$user_id'"
        );
    }
    public static function editCartItem( $con, $product_id, $quantity ){
        Auth::checkCustomer();
        $user_id = $_SESSION['userId'];
        if ($quantity == 0){
            self::deleteCartItem($con, $product_id);
            return;
        };
        SQL::update(
            $con,
            'cart_items',
            "quantity = '$quantity'",
            "product_id = '$product_id' AND client_id = '$user_id'"
        );
    }
}
?>