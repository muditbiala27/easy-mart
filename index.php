
<?php
session_start();
include("db.php"); // Include database connection
$dashboard = "users/users_dashboard.php"; // Default user dashboard
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === "Seller") {
    $dashboard = "seller/seller_dashboard.php"; // Redirect seller to their dashboard
}
$sql = "SELECT * FROM products ORDER BY id DESC";
$result = $conn->query($sql);
$products = $result->fetch_all(MYSQLI_ASSOC); // Fetch all products

if (isset($_SESSION['user_id']) && !isset($_SESSION['user_name'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $_SESSION['user_name'] = $row['name']; // Set the name in session
    }
}
?>


<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pro</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg bg-primary-subtle ">
  <div class="container">
    <a class="navbar-brand text-primary" href="index.html"> <strong>LOGO</strong> </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link" href="#">Products</a>
        </li> -->

      </ul>

    </div>
    <div class="d-flex ms-3">
      <?php if (!empty($_SESSION['user_name'])) { ?>
        <a href="<?= htmlspecialchars($dashboard); ?>" class="text-primary fw-bold me-3 text-decoration-none">
            Welcome, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?>!
        </a>
          <a class="btn btn-danger" href="logout.php" role="button">Logout</a>
      <?php } else { ?>
          <a class="btn btn-primary me-2" href="login.php" role="button">Login</a>
          <a class="btn btn-secondary" href="register.php" role="button">Sign Up</a>
      <?php } ?>
  </div>



</nav>


<section>
  <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="sliderss" style="background-image: url('./images/slider-1.jpg');">
          <div class="overlay"></div>
          <div class="container py-5 position-relative">
            <div class="row justify-content-center">
              <div class="col-lg-6">
                <div class="text-center">
                  <h1 class="text-white">Lorem Ipsum</h1>
                  <p class="text-white">Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.</p>
                  <a class="btn btn-primary me-2" href="#" role="button">view all products</a>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="carousel-item">
        <div class="sliderss" style="background-image: url('./images/slider-2.jpg');">
          <div class="overlay"></div>
          <div class="container py-5 position-relative">
            <div class="row justify-content-center">
              <div class="col-lg-6">
                <div class="text-center">
                  <h1 class="text-white">Lorem Ipsum</h1>
                  <p class="text-white">Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.</p>
                  <a class="btn btn-primary me-2" href="#" role="button">view all products</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div class="sliderss" style="background-image: url('./images/slider-3.jpg');">
          <div class="overlay"></div>
          <div class="container py-5 position-relative">
            <div class="row justify-content-center">
              <div class="col-lg-6">
                <div class="text-center">
                  <h1 class="text-white">Lorem Ipsum</h1>
                  <p class="text-white">Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.</p>
                  <a class="btn btn-primary me-2" href="#" role="button">view all products</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</section>

<section>
  <div class="container py-5">
    <div class="row">
      <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
         <img src="images/offer-1.jpg" width="100%" class="img-fluid rounded-2 " alt="...">
      </div>
      <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
        <img src="images/offer-2.jpg" width="100%" class="img-fluid rounded-2" alt="...">
     </div>
     <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
      <img src="images/offer-3.jpg" width="100%" class="img-fluid rounded-2" alt="...">
   </div>
       </div>
       </div>
</section>

<section class="Products py-5">
  <div class="container">
    <h2 class="text-center pb-3">Products</h2>
    <div class="row">
      <?php if (!empty($products)) { ?>
        <?php foreach ($products as $product) { ?>
          <div class="col-lg-3 col-md-4 col-sm-4 col-6 mb-4">
            <div class="card bg-light">
              <div class="p-4 bg-light">
                <img src="seller/<?= htmlspecialchars($product['product_image']); ?>" width="220" height="200">
              </div>
              <div class="card-body text-center">
                <h5 class="card-title"><?= htmlspecialchars($product['product_name']) ?></h5>
                <p class="card-text fs-4">$<?= number_format($product['price'], 2) ?></p>

                <?php
                $product_id = $product['id'];
                if (isset($_SESSION['cart'][$product_id])) { ?>
                  <a href="cart.php" class="btn btn-success">View Cart</a>
                <?php } else { ?>
                  <button class="btn btn-primary add-to-cart" data-id="<?= $product_id ?>">Add to Cart</button>
                <?php } ?>

              </div>
            </div>
          </div>
        <?php } ?>
      <?php } else { ?>
        <p class="text-center">No products found.</p>
      <?php } ?>
    </div>
  </div>
</section>

<section class="offer mt-4" style="background-image: url('./images/309549.jpg');">
    <div class="container">
      <div class="row justify-content-end">
        <div class="col-lg-5">
          <div class=" ">
            <h2 class="fs-1">Get 50% off on your first order</h2>
            <p>Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.</p>
            <a href="#" class="btn btn-primary">Shop Now</a>
          </div>
        </div>
      </div>
    </div>
</section>



      <section class="Products py-5">
        <div class="container">
          <h2 class="text-center pb-3">Best Selling Products</h2>
          <div class="row">
            <?php if (!empty($products)) { ?>
              <?php foreach ($products as $product) { ?>
                <div class="col-lg-3 col-md-4 col-sm-4 col-6 mb-4">
                  <div class="card bg-light">
                    <div class="p-4 bg-light">
                      <img src="seller/<?= htmlspecialchars($product['product_image']); ?>" width="220" height="200">
                    </div>
                    <div class="card-body text-center">
                      <h5 class="card-title"><?= htmlspecialchars($product['product_name']) ?></h5>
                      <p class="card-text fs-4">$<?= number_format($product['price'], 2) ?></p>

                      <?php
                      $product_id = $product['id'];
                      if (isset($_SESSION['cart'][$product_id])) { ?>
                        <a href="cart.php" class="btn btn-success">View Cart</a>
                      <?php } else { ?>
                        <button class="btn btn-primary add-to-cart" data-id="<?= $product_id ?>">Add to Cart</button>
                      <?php } ?>

                    </div>
                  </div>
                </div>
              <?php } ?>
            <?php } else { ?>
              <p class="text-center">No products found.</p>
            <?php } ?>
          </div>
        </div>
      </section>


  <section class=" section footer overflow-hidden section-padding">
    <!-- container -->
    <div class="container">
       <div class="row ">
          <div class="col-lg-3">
             <a class="navbar-brand logo text-uppercase text-white fs-3" href="">
          <strong>LOGO</strong>
             </a>
             <p class="text-white mt-2 mb-0">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
             </p>

          </div>
          <div class="col-lg-2 offset-lg-1">
             <div class="text-start mt-4 mt-lg-0">
                <h5 class="text-white">Services</h5>
                <ul class="footer-item list-unstyled footer-link mt-3">
                   <li><a href=""> E-commerce </a></li>

                </ul>
             </div>
          </div>
          <div class="col-lg-2 ">
             <div class="text-start">
                <h5 class="text-white  ">Quick Links</h5>
                <ul class="footer-item list-unstyled footer-link mt-3">
                   <li><a href="">Products</a></li>
                </ul>
             </div>
          </div>
          <div class="col-lg-4">
             <h5 class="text-white">Subscribe</h5>
             <div class="input-group my-4">
                <input type="text" class="form-control p-3" placeholder="subscribe" aria-label="subscribe" aria-describedby="basic-addon2">
                <a href="" class="input-group-text bg-primary text-white px-4 border-0" id="basic-addon2">Go</a>
             </div>


          </div>
       </div>
    </div>

 </section>
 <footer>
    <div class="container text-center">
       <p class="text-white mb-0">© 2025 All Right Receved.</p>
    </div>
 </footer>
    <script src="js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $(".add-to-cart").click(function(){
        var product_id = $(this).data("id");
        var button = $(this);

        $.ajax({
            url: "add_to_cart.php",
            method: "POST",
            data: { product_id: product_id },
            success: function(response){
                if(response == "added"){
                    button.replaceWith('<a href="cart.php" class="btn btn-success">View Cart</a>');
                }
            }
        });
    });
});
</script>

  </body>
</html>
