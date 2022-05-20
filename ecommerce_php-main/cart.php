<?php
   require_once 'components/header.php'; 
   require_once 'components/nav.php';
   require_once './controllers/seller.controller.php';
   require_once './auth/auth.php';
   require './db/db.connection.php';
   require './helpers/validation.php';

   /* logic start */
   Auth::checkCustomer();
   $user_id = $_SESSION['userId'];
   $sql = " select c.quantity, p.*, d.percent, d.expiry_date from products AS p 
   left join cart_items AS c on p.id = c.product_id
   left join discounts As d on p.id = d.product_id 
   where c.client_id=$user_id";
   $result = SQL::doQuery($con, $sql);
   $no_of_products = mysqli_num_rows($result);
   /* logic end */
?>
<!-- products section -->
<section class="product_section layout_padding">
   <div class="container">
      <div class="heading_container heading_center">
         <h2>
            Your <span>Cart</span>
         </h2>
      </div>
      <div class="row">
         <?php
            if($no_of_products == 0){
               echo '<div class = text-center>
                        <p class = text-center > You do not have products yet.</p>
                     </div>';
            } 
            else {
               while($row = mysqli_fetch_assoc($result)){
                  $final_price = $row['price'];
                  if($row['percent'])
                     $final_price = $row['price'] * $row['percent'] / 100;
                  echo "
                  <div class='col-sm-6 col-md-4 col-lg-4'>
                     <div class='box'>
                        <div class='option_container'>
                           <div class='options'>
                              <a href=deleteFromCart.php?id=".$row['id']." class='option1'>
                                 Delete
                              </a>
                              <a href = editCart.php?s=plus&id=".$row['id']."&q=".$row['quantity']." class='option2'>
                                 +
                              </a>
                              <a href = editCart.php?s=minus&id=".$row['id']."&q=".$row['quantity']." class='option2'>
                                 -
                              </a>
                           </div>
                        </div>
                        <div class='img-box'>
                           <img src=".$row['productImage']." alt=''>
                        </div>
                        <div class='detail-box d-flex flex-column'>
                           <h5>
                              ".$row['productName']."
                           </h5>
                           <h6>
                              ".$final_price."
                           </h6>
                           <h6>
                              Qty: ".$row['quantity']."
                           </h6>
                        </div>
                     </div>
                  </div> 
                  ";
               }
            }
         ?>
      </div>
   </div>                     
   <div class="btn-box">
      <a href="products.php">
         Add a product
      </a>
   </div>
</section>
<!-- end product section -->
<?php
   require_once './components/footer.php';
?>