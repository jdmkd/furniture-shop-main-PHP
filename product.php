<?php include('include/header.php');

if (isset($_GET['page'])) {
  $page_id = $_GET['page'];
} else {
  $page_id = 1;
}

$required_pro = 12;

$query = "SELECT * FROM furniture_product WHERE status = 'publish' ORDER BY pid";
$run   = mysqli_query($con, $query);
$count_rows = mysqli_num_rows($run);

$pages = ceil($count_rows / $required_pro);
$product_start = ($page_id - 1) * $required_pro;

if (isset($_SESSION['id'])) {
  $custid = $_SESSION['id'];

  if (isset($_GET['cart_id'])) {
    $p_id = $_GET['cart_id'];

    $sel_cart = "SELECT * FROM cart WHERE cust_id = $custid AND product_id = $p_id";
    $run_cart    = mysqli_query($con, $sel_cart);

    if (mysqli_num_rows($run_cart) == 0) {
      $cart_query = "INSERT INTO `cart`(`cust_id`, `product_id`, quantity) VALUES ($custid, $p_id, 1)";
      if (mysqli_query($con, $cart_query)) {
        header("location:product.php");
      }
    } else {
      $error = "<script>alert('⚠️ This product is already in your cart'); </script>";
    }
  }
} else if (!isset($_SESSION['email'])) {
  echo "<script> function a(){alert('⚠️ Login is required to add this product into cart');}</script>";
}
?>

<div class="jumbotron text-center mb-4">
  <h2>Choose Products</h2>
</div>

<div class=" mx-1 mx-lg-4 my-4">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 mb-4">
      <div class="list-group">
        <a href='product.php' class='list-group-item list-group-item-action'><i class='fal fa-home ml-2'></i> Products</a>
        <?php
        $cat_query = "SELECT * FROM categories ORDER BY id ASC";
        $cat_run   = mysqli_query($con, $cat_query);
        if (mysqli_num_rows($cat_run) > 0) {
          while ($cat_row = mysqli_fetch_array($cat_run)) {
            $cid      = $cat_row['id'];
            $cat_name = ucfirst($cat_row['category']);
            $font     = $cat_row['fontawesome-icon'];
            echo " <a href='product.php?cat_id=$cid' class='list-group-item list-group-item-action'><i class='fal $font ml-2'></i> $cat_name</a>";
          }
        } else {
          echo " <a class='list-group-item list-group-item-action'> No Category </a>";
        }
        ?>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-md-10">
      <div class="row">
        <div class="col-md-12">
          <div class="row mb-3">
            <div class="col-sm-11 col-md-9 col-lg-10 col-xl-11">
              <form method="post">
                <div class="input-group">
                  <input type="text" class="form-control" name="search" placeholder="Search Products">
                  <div class="input-group-append">
                    <button class="btn btn-primary" type="submit" name="sear_submit">Search</button>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <?php
          if (isset($msg)) {
            echo $msg;
          } else if (isset($error)) {
            echo $error;
          }
          ?>

          <!-- Product List -->
          <div class="row">
            <?php
            if (isset($_GET['cat_id'])) {
              $catid = $_GET['cat_id'];
              $cat_query = "SELECT * FROM categories WHERE id=$catid";
              $run   = mysqli_query($con, $cat_query);
              $row   = mysqli_fetch_array($run);
              $catname = $row['category'];

              $p_query = "SELECT * FROM furniture_product WHERE category=$catid ORDER BY pid DESC LIMIT $product_start,$required_pro";
            } else if (isset($_POST['sear_submit'])) {
              $search = $_POST['search'];
              $p_query = "SELECT * FROM furniture_product WHERE title LIKE '%$search%' ORDER BY pid DESC LIMIT $product_start, $required_pro";
            } else {
              $p_query = "SELECT * FROM furniture_product WHERE status='publish' ORDER BY pid DESC LIMIT $product_start,$required_pro";
            }

            $p_run   = mysqli_query($con, $p_query);

            if (mysqli_num_rows($p_run) > 0) {
              while ($p_row = mysqli_fetch_array($p_run)) {
                $pid      = $p_row['pid'];
                $ptitle   = $p_row['title'];
                $p_price  = $p_row['price'];
                $img1     = $p_row['image'];
            ?>
                
                <div class="col-sm-5 col-md-4 col-lg-3 col-xl-2 col-2xl-2 mx-2 mb-4 border pt-3">
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
            } else {
              echo "<h3 class='text-center'>Products Are Not Available Yet</h3>";
            }
            ?>
          </div>

          <!-- Pagination -->
          <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center mt-4">
              <?php for ($i = 1; $i <= $pages; $i++) {
                echo "<li class='page-item " . ($i == $page_id ? ' active ' : '') . "'><a class='page-link' href='product.php?page=$i'>$i</a></li>";
              } ?>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include('include/footer.php'); ?>
