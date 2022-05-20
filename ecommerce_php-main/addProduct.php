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
    $categories = Product::showCategories($con);
    $op = $categories[1];

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
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

        $dis_Image = Helpers::uploadImage('Image');

        if( count(Validation::$errors) == 0 )
            Seller::addProduct($con, $productName, $category, $price, $desc, $dis_Image);
    };
    /* logic end */
?>
<section class="why_section layout_padding">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>
                Add <span>Product</span>
            </h2>
        </div>
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="full">
                    <form action= <?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?> method="post" enctype="multipart/form-data">
                    <fieldset>
                        <label>Product Name</label>
                        <input type="text" placeholder="Enter your product Name" name="productName" required minlength="6" />
                        <?php
                            Helpers::echoError('product name');
                        ?>

                        <div class="d-flex flex-column my-3">
                            <label>Category</label>
                            <select name="Category">
                                <option disabled value="">--choose category</option>
                                <?php
                                    while($row = mysqli_fetch_assoc($op)){
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
                        <input type="text" placeholder="Enter price" name="price" required />
                        <?php
                            Helpers::echoError('price');
                        ?>

                        <label>Product Description</label>
                        <textarea placeholder="Enter your product description" name='desc' required minlength="20"></textarea>
                        <?php
                            Helpers::echoError('product description');
                        ?>

                        <label>Product Image</label>
                        <input type="file" name="Image"/>
                        <?php
                            Helpers::echoError('File');
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