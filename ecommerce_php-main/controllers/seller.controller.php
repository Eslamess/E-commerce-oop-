<?php

require_once __DIR__ .'/../auth/auth.php';
require_once __DIR__ .'/../db/db.controller.php';

class Seller {
    public static function addProduct($con, $productName, $category_id, $price, $description, $image){
        Auth::checkSeller();
        $user_id = $_SESSION['userId'];
        SQL::insert(
            $con,
            'products',
            '(productName, category_id, price, seller_id, productDescription , productImage )',
            "('$productName','$category_id','$price', '$user_id', '$description', '$image')",
            'Your product was added successfully'
        );

    }
    public static function deleteProduct($con, $productId){
        Auth::checkSeller();
        $user_id = $_SESSION['userId'];
        SQL::delete(
            $con, 
            'products',
            "id = '$productId' AND seller_id = '$user_id'",
            'Your product was deleted successfully'
        );
    }
    public static function editProduct($con, $productId, $productName, $category_id, $price, $productDescription, $productImage){
        Auth::checkSeller();
        $user_id = $_SESSION['userId'];
        SQL::update(
            $con, 
            'products',
            "productName = '$productName', category_id = '$category_id', price = '$price', productDescription = '$productDescription', productImage = '$productImage'",
            "id = '$productId' AND seller_id = '$user_id'",
            'Your product was edited successfully'
        );
    }
    public static function showSellerProducts($con){
        Auth::checkSeller();
        $user_id = $_SESSION['userId'];
        $today = date("Ymd");
        $sql = "SELECT p.*, r.percent FROM products AS p 
        LEFT JOIN (SELECT * FROM discounts WHERE expiry_date > '$today') AS r
        ON p.id = r.product_id
        WHERE p.seller_id = $user_id";
        $op =  mysqli_query($con,$sql);
        SQL::checkQuery($con, $op);
        $no_of_rows = mysqli_num_rows($op);
        return [$no_of_rows, $op];
    }
    /** discounts */
    public static function addDiscount($con, $product_id, $percent, $expiry_date){
        Auth::checkSeller();
        $user_id = $_SESSION['userId'];
        // left join with discounts table
        $today = date("Ymd");
        $sql = "SELECT * FROM products 
        LEFT JOIN (SELECT * FROM discounts WHERE expiry_date > '$today') AS r
        ON products.id = r.product_id
        WHERE products.id = $product_id AND products.seller_id = $user_id";
        $op =  mysqli_query($con,$sql);
        SQL::checkQuery($con, $op);
        $no_of_rows = mysqli_num_rows($op);
        $data = mysqli_fetch_assoc($op);
        // does this seller has this product
        if($no_of_rows == 0){
            $_SESSION['mssg'] = "You choosed wrong product";
            return;
        }
        // seller is not allowed to add discount if there is existing one
        if($data['percent']){
            $_SESSION['mssg'] = "You already have discount on this product";
            return;
        }
        SQL::insert(
            $con,
            'discounts',
            '(product_id, percent, expiry_date)',
            "($product_id, $percent, '$expiry_date')",
            "Your discount was added"
        );
    }
    function deleteDiscount($con, $product_id){
        Auth::checkSeller();
        $today = date("Ymd");
        $user_id = $_SESSION['userId'];

        // check this seller has this product and it is not already expired
        $sql = "SELECT r.* FROM products AS p
        LEFT JOIN (SELECT * FROM discounts WHERE expiry_date > '$today') AS r
        ON products.id = r.product_id
        WHERE products.id = $product_id AND products.seller_id = $user_id";
        $op =  mysqli_query($con,$sql);
        SQL::checkQuery($con, $op);
        $no_of_rows = mysqli_num_rows($op);
        $data = mysqli_fetch_assoc($op);
        if($no_of_rows == 0){
            $_SESSION['mssg'] = "You choosed wrong product";
            return;
        }
        if(!$data['percent']){
            $_SESSION['mssg'] = "You do not have discount on this product";
            return;
        }
        $discountId = $data['id'];
        SQL::update(
            $con,
            'discounts',
            "expiry_date = $today",
            "id = $discountId"
        );
    }
}

?>