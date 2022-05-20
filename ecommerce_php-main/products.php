<?php
   require_once 'components/header.php';
   require_once './db/db.controller.php';
   require_once './db/db.connection.php';
   // logic start
   $today = date("Ymd");
   $sql = "SELECT p.*, d.percent, d.expiry_date from products AS p 
   LEFT JOIN discounts AS d 
   ON p.id = d.product_id
   ";
   $result = SQL::doQuery($con, $sql);

   // logic end
?>
      <div class="hero_area">
         <!-- header section strats -->
         <header class="header_section">
            <div class="container">
               <?php
                  require_once 'components/nav.php';
               ?>
            </div>
         </header>
         <!-- end header section -->
      </div>
      <!-- inner page section -->
      <section class="inner_page_head">
         <div class="container_fuild">
            <div class="row">
               <div class="col-md-12">
                  <div class="full">
                     <h3>Product Grid</h3>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- end inner page section -->
      <!-- product section -->
      <section class="product_section layout_padding">
         <div class="container">
            <div class="heading_container heading_center">
               <h2>
                  Our <span>products</span>
               </h2>
            </div>
            <div class="row">
               <?php
               while($row = mysqli_fetch_assoc($result)){
                  $final_price = $row['price'];
                  if($row['percent'] && $row['expiry_date'] > time())
                     $final_price = $row['price'] * $row['percent'] / 100;
                  echo "
                  <div class='col-sm-6 col-md-4 col-lg-4'>
                     <div class='box'>
                        <div class='option_container'>
                           <div class='options'>
                              <a href = addToCart.php?id=".$row['id']." class='option1'>
                                 Add to Cart
                              </a>
                              <a href = addToCart.php?id=".$row['id']." class='option2'>
                                 Buy Now
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
                        </div>
                     </div>
                  </div> 
                  ";
               };
               ?>
            </div>        
            <div class="btn-box">
               <a href="">
                  View All products
               </a>
            </div>
         </div>
      </section>
      <!-- end product section -->
<?php
   require_once './components/footer.php';
?>