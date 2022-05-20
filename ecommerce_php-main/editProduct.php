<?php
    require_once 'components/header.php'; 
    require_once 'components/nav.php';

    require_once './controllers/seller.controller.php';
    require_once './controllers/product.controller.php';
    require_once './auth/auth.php';
    require './db/db.connection.php';
    require_once './helpers/validation.php';
    require_once './helpers/functions.php';

    /* logic start */
    Auth::checkSeller();

    if(!isset($_GET['edit']))
        header("Location: index.php");

    if( ($_SERVER['REQUEST_METHOD'] == 'POST') && $_POST['percent'] && $_POST['expiry_date']){
        $old_row = $_SESSION['product_row'];

        $new_percent = Validation::filterData($_POST['percent']);
        $new_expiry_date = Validation::filterData($_POST['expiry_date']);

        $new_percent = Validation::checkNumber('discount percent', $new_percent);
        Validation::validateDate('expiry date', $new_expiry_date);
        if ($new_expiry_date < time())
            Validation::$errors['expiry date'] = 'please choose right date';

            
        if(!$old_row['percent'] && count(Validation::$errors) == 0){
            Seller::addDiscount($con, $_GET['edit'], $new_percent, $new_expiry_date);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $old_row = $_SESSION['product_row'];

        $productName = Validation::filterData($_POST['productName']);
        $category = Validation::filterData($_POST['Category']);
        $price = Validation::filterData($_POST['price']);
        $desc = Validation::filterData($_POST['desc']);

        Validation::required_input('product name', $productName);
        Validation::minLength('product name', $productName, 6);
        Validation::validate_string('product name', $productName);

        Validation::required_input('product category', $category);
        $category = Validation::checkNumber('product category', $category);

        Validation::required_input('price', $price);
        Validation::checkNumber('price', $price);

        Validation::required_input('product description', $desc);
        Validation::minLength('product description', $desc, 20);

        $dis_Image = $old_row['productImage'];
        if ($_FILES['Image']['size'] != 0){
            $dis_Image = Helpers::uploadImage('Image');
        };

        // check and save in sql
        if( count(Validation::$errors) == 0 ){
            Seller::editProduct($con, $_GET['edit'], $productName, $category, $price, $desc, $dis_Image);
            if($dis_Image != $old_row['productImage'])
                unlink($old_row['productImage']);
        };
        unset($_SESSION['product_row']);
    };

    $edit_id = $_GET['edit'];
    $seller_id = $_SESSION['userId'];
    $categories = Product::showCategories($con);
    $op_categories = $categories[1];
    $today = date("Ymd");

    // get edit product data
    $sql = "SELECT p.productName, p.price, p.seller_id, p.productDescription, p.productImage, 
    c.id , d.percent, d.expiry_date 
    From products AS p LEFT JOIN categories AS c
    ON p.category_id = c.id 
    LEFT JOIN discounts AS d
    ON p.id = d.product_id
    WHERE p.id = $edit_id AND p.seller_id = $seller_id AND d.expiry_date > '$today';
    ";
    $op_product =  mysqli_query($con,$sql);
    SQL::checkQuery($con, $op_product);
    $no_of_rows = mysqli_num_rows($op_product); 
    if ( $no_of_rows == 0 )
        header("Location: error404.php");
    $product_row = mysqli_fetch_assoc($op_product);
    $_SESSION['product_row'] = $product_row;
    /* logic end */
?>
<section class="why_section layout_padding">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>
                Edit <span>product</span>
            </h2>
        </div>
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="full">
                    <form action= <?php echo htmlspecialchars($_SERVER['PHP_SELF'])."?edit=".$_GET['edit']?> method="post" enctype="multipart/form-data">
                    <fieldset>
                        <label>Product Name</label>
                        <input value=<?php echo $product_row['productName']?> type="text" placeholder="Enter your product Name" name="productName" required minlength="6" />
                        <?php
                            Helpers::echoError('product name');
                        ?>

                        <div class="d-flex flex-column my-3">
                            <label>Category</label>
                            <select value=<?php echo $product_row['id']?> name="Category">
                                <option disabled value="">--choose category</option>
                                <?php
                                    while($row = mysqli_fetch_assoc($op_categories)){
                                    echo "
                                    <option value=".$row['id']." >".$row['category_name']."</option>
                                    ";
                                    };
                                ?>
                            </select>
                        </div>
                        <?php
                            Helpers::echoError('product category');
                        ?>

                        <label>Price</label>
                        <input value=<?php echo $product_row['price']?> type="text" placeholder="Enter price" name="price" required />
                        <?php
                            Helpers::echoError('price');
                        ?>

                        <label>Product Description</label>
                        <textarea placeholder="Enter your product description" name='desc' required minlength="20">
                            <?php echo $product_row['productDescription']?> 
                        </textarea>
                        <?php
                            Helpers::echoError('product description');
                        ?>

                        <label>Product Image</label>
                        <img width="100" height="100" src=<?php echo $product_row['productImage']?>>
                        <input type="file" name="Image"/>
                        <?php
                            Helpers::echoError('File');
                        ?>

                        <?php
                        if ($product_row['percent']){
                            echo "
                                <label>Discount percent</label>
                                <input value= ".$product_row['percent']." type='text' 
                                placeholder='Enter discount percent' name='percent'/>";
                                Helpers::echoError('discount percent');

                            echo "    
                                <label>Expiry Date</label>
                                <input value= ".$product_row['expiry_date']."
                                type='date' placeholder='Enter expiry date' name='expiry_date'/>";
                                Helpers::echoError('expiry date');
                        }
                        else {
                            echo "
                                <label>Discount percent</label>
                                <input type='text' 
                                placeholder='Enter discount percent' name='percent'/>";
                                Helpers::echoError('discount percent');

                            echo "
                                <label>Expiry Date</label>
                                <input type='date' placeholder='Enter expiry date' name='expiry_date'/>";
                                Helpers::echoError('expiry date');
                        }
                        ?>

                        <input type="submit" value="Submit" />
                    </fieldset>
                    </form>
                    <?php
                        require_once './components/mssg.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>