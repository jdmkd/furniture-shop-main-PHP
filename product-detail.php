<?php include('include/header.php'); ?>

<div class="jumbotron">
  <h2 class="text-center mt-5">Product detail</h2>
</div>

<main>

  <div class="">
    <center>
      <div class="w-75">
        <?php
        if (isset($msg)) {
          echo $msg;
        }
        ?>
      </div>
    </center>
    <!--Section: Block Content-->
    <section class="container my-5 border rounded p-3 pt-5 hover-effect cursor-pointer">
      <div class="row">

        <?php
        if (isset($_GET['product_id'])) {
          $p_id = $_GET['product_id'];

          $pdetail_query = "SELECT * FROM furniture_product WHERE pid=$p_id";
          $pdetail_run   = mysqli_query($con, $pdetail_query);

          if (mysqli_num_rows($pdetail_run) > 0) {
            $pdetail_row = mysqli_fetch_array($pdetail_run);
            $pid = $pdetail_row['pid'];
            $title = $pdetail_row['title'];
            $category = $pdetail_row['category'];
            $detail = $pdetail_row['detail'];
            $price = $pdetail_row['price'];
            $size = $pdetail_row['size'];
            $img1 = $pdetail_row['image'];
          }
        }
        ?>
        <div class="col-md-5 mb-4 mb-md-0">
          <div class="view zoom z-depth-2 rounded">
            <img class="product_cards_details-img img-fluid w-100 cursor-grab " src="img/<?php echo $img1; ?>" alt="Chair">
          </div>
        </div>

        <div class="col-md-7">

          <h2 class="fw-normal border-bottom pb-2"><?php echo $title; ?></h2>
          <p class="mb-2 text-muted text-uppercase small">
            <?php
            $cat_query = "SELECT * FROM categories Where id=$category ORDER BY id ASC";
            $cat_run   = mysqli_query($con, $cat_query);
            if (mysqli_num_rows($cat_run) > 0) {
              $cat_row = mysqli_fetch_array($cat_run);
              echo  $cat_name = ucfirst($cat_row['category']);
            }
            ?>
          </p>
          <p><span class="mr-1"><strong>INR <?php echo $price; ?></strong></span></p>
          <p class="pt-1"><?php echo $detail; ?></p>
          <div class="table-responsive">
            <table class="table table-borderless mb-0">
              <tbody>
                <tr>
                  <th class="pl-0 w-25" scope="row"><strong>Size</strong></th>
                  <td><?php echo $size; ?></td>
                </tr>

              </tbody>
            </table>
          </div>
          <hr>
          <form method="post">
            <?php

            if (isset($_SESSION['email'])) {
              $custid = $_SESSION['id'];
              if (isset($_POST['submit'])) {
                $qty = $_POST['qty'];

                $sel_cart = "SELECT * FROM cart WHERE cust_id = $custid and product_id = $p_id ";
                $run_cart    = mysqli_query($con, $sel_cart);

                if (mysqli_num_rows($run_cart) == 0) {
                  $cart_query = "INSERT INTO `cart`(`cust_id`, `product_id`,quantity) VALUES ($custid,$p_id,$qty)";
                  if (mysqli_query($con, $cart_query)) {
                    header("location:product-detail.php?product_id=$p_id");
                  }
                }

                //                    if(mysqli_num_rows($run_cart) > 0){
                //                     while($row = mysqli_fetch_array($run_cart)){
                //                      $db_cart_pid = $row['product_id'];
                //                     
                //                      if($p_id !== $db_cart_pid){
                //                        $cart_query = "INSERT INTO `cart`(`cust_id`, `product_id`,quantity) VALUES ($custid,$p_id,$qty)";    
                //                        if(mysqli_query($con,$cart_query)){
                //                          header("location:product-detail.php?product_id=$p_id");
                //                        }
                //                      }
                //            
                //                      if($p_id==$db_cart_pid ){
                //                       
                //                            echo "<script> alert('⚠️ This product is already in your cart'); </script>";
                //                              
                //                                }
                //                           
                //                         }
                //                     }

                if (mysqli_num_rows($run_cart) > 0) {
                  while ($row = mysqli_fetch_array($run_cart)) {
                    $exist_pro_id = $row['product_id'];
                    if ($p_id == $exist_pro_id) {
                      echo "<script>alert('⚠️ This product is already in your cart '); </script>";
                    }
                  }
                }
              }
            } else if (!isset($_SESSION['email'])) {
              echo "<script> function a(){
                        alert('⚠️ Login is required to add this product into cart');
                          }</script>";
            }

            ?>
            <div class="form-group">
              <label>Quantity</label>
              <input type="number" name="qty" placeholder="Quantity" value="1" class="form-control w-25">
            </div>

            <button type="submit" onclick="a()" name="submit" class="btn btn-light btn-md mt-3 mr-1 mb-2 hover-effect"><i class="fas fa-shopping-cart pr-2"></i> Add to cart</button>

          </form>
        </div>

      </div>

    </section>
    <!--Section: Block Content-->



    <!--Section: New products-->
    <div>

      <h3 class="text-center pt-5 mb-5">Related Products </h3>

      <!-- Grid row -->
      <div class="col-12 row mt-4 ml-0 mr-0 justify-content-center ">

        <!-- Grid column -->
        <?php
        $p_query = "SELECT * FROM furniture_product WHERE category LIKE '%$category%' order by title DESC LIMIT 4";
        $p_run   = mysqli_query($con, $p_query);

        if (mysqli_num_rows($p_run) > 0) {
          while ($p_row = mysqli_fetch_array($p_run)) {
            $pid      = $p_row['pid'];
            $ptitle  = $p_row['title'];
            $pcat    = $p_row['category'];
            $p_price = $p_row['price'];
            $size    = $p_row['size'];
            $img1    = $p_row['image'];
        ?>

                <div class="col-sm-5 col-md-4 col-lg-3 col-xl-2 mx-3 mb-4 border pt-3">
                  <div class="card h-100 border-0 shadow-sm">
                    <div class="position-relative" style="height: 200px; overflow: hidden;">
                      <img src="img/<?php echo $img1; ?>" class="card-img-top w-100 h-100 object-fit-cover rounded-top"
                          alt="<?php echo $ptitle; ?>">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between py-3 px-0 mx-0">
                      <!-- Product Title -->
                      <h5 class="card-title fw-bold text-truncate mb-2" title="<?php echo $ptitle; ?>">
                        <?php echo (strlen($ptitle) > 20) ? substr($ptitle, 0, 20) . '...' : $ptitle; ?>
                      </h5>

                      <!-- Price & Rating Section -->
                      <div class="mb-3">
                        <h6 class="card-subtitle text-danger fw-semibold">₹<?php echo number_format($p_price, 2); ?></h6>
                        <div class="text-warning">
                          ★★★★☆ <span class="text-muted fs-6"></span>
                        </div>
                      </div>

                      <!-- Buttons Section -->
                      <div class="d-grid gap-3 justify-content-center text-center">
                        <!-- Add to Cart Button -->
                        <a href="product.php?cart_id=<?php echo $pid; ?>" 
                          class="btn btn-primary btn-sm px-3 py-2 shadow-sm fw-semibold d-flex align-items-center justify-content-center mb-2 ">
                          <i class="fas fa-shopping-cart mx-2"></i> Add to Cart
                        </a>

                        <!-- View Details Button -->
                        <a href="product-detail.php?product_id=<?php echo $pid; ?>" 
                          class="btn btn-success btn-sm px-3 py-2 shadow-sm fw-semibold d-flex align-items-center justify-content-center">
                          <i class="fas fa-info-circle mx-2"></i> View Details
                        </a>
                      </div>

                    </div>

                  </div>
                </div>

        <?php
          }
        }
        ?>

      </div>
      <!-- Grid row -->

    </section>
    <!--Section: New products-->

  </div>

</main>

<?php include('include/footer.php'); ?>