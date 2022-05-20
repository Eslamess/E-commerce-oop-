<?php

require_once __DIR__ .'/../db/db.controller.php';
require_once __DIR__ .'/../auth/auth.php';

class Product {
    public static function addView($con, $product_id, $product_category_id ){
        $user_id = $_SESSION['userId'];
        SQL::insert(
            $con,
            'views',
            '(user_id, product_id, product_category_id )',
            "('$user_id', '$product_id', '$product_category_id')"
        );
    }
    public static function showProduct($con, $product_id){
        $result = SQL::read(
            $con,
            'productName, category_id, price, seller_id, productDescription , productImage',
            'products',
            "id = $product_id"
        );
        return $result;
    }
    public static function showCategoryProducts($con, $category_id){
        $result = SQL::read(
            $con, 
            'productName, category_id, price, seller_id, description, image',
            'products',
            "category_id  = '$category_id'"
        );
        return $result;
    }
    public static function showCategories($con){
        $result = SQL::read(
            $con,
            '*',
            'categories',
            '1=1'
        );
        return $result;
    }
    public static function showProductRatings($con, $product_id){
        $result = SQL::read(
            $con,
            'user_id, stars, text',
            'ratings',
            "product_id = '$product_id'"
        );
        return $result;
    }
};

?>