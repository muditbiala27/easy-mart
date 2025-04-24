<?php
session_start();
include("db.php");

$message = "";

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid Request");
}

$product_id = intval($_GET['id']);

// Fetch product details
$result = $conn->query("SELECT * FROM products WHERE id = $product_id");
$product = $result->fetch_assoc();
if (!$product) {
    die("Product not found");
}

// Handle product update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    // Handle image upload
    if (!empty($_FILES['product_image']['name'])) {
        $upload_dir = "uploads/";
        $image_name = basename($_FILES['product_image']['name']);
        $image_path = $upload_dir . time() . "_" . $image_name;
        $image_type = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));

        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (in_array($image_type, $allowed_types)) {
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $image_path)) {
                // Delete old image if exists
                if (!empty($product['product_image']) && file_exists($product['product_image'])) {
                    unlink($product['product_image']);
                }
                $conn->query("UPDATE products SET product_name = '$product_name', price = '$price', product_image = '$image_path' WHERE id = $product_id");
            } else {
                $message = "<div class='alert alert-danger'>Failed to upload image.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Invalid file type! Only JPG, PNG, GIF are allowed.</div>";
        }
    } else {
        // Update without changing the image
        $conn->query("UPDATE products SET product_name = '$product_name', price = '$price' WHERE id = $product_id");
    }

    // Redirect to seller_dashboard.php after update
    header("Location: seller_dashboard.php");
    exit();
}


if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $userQuery = "SELECT name FROM users WHERE id = ?";
    $stmt = $conn->prepare($userQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($userName);
    $stmt->fetch();
    $stmt->close();
    if (empty($userName)) {
        $userName = "Guest";
    }
} else {
    $userName = "Guest";
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Product</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <header class="bg-dark text-white py-3">
      <div class="container d-flex justify-content-between align-items-center">
          <h3 class="mb-0">Pro Seller</h3>

          <nav>
              <ul class="nav">
                  <li class="nav-item dropdown">
                      <a class="nav-link text-white" href="#" role="button" data-bs-toggle="dropdown">
                          <?php echo $userName; ?> <i class="fas fa-user-circle"></i>
                      </a>
                  </li>
              </ul>
          </nav>
      </div>
  </header>
<section class="py-lg-7 py-5 bg-primary-subtle" style="min-height: 100vh;">
    <div class="container">
        <div class="row">
            <?php include("sidebar.php"); ?>
            <div class="col-lg-9 col-md-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-lg-5">
                        <h4 class="mb-1">Edit Product</h4>
                        <br>
                        <?php echo $message; ?>
                        <div class="booking-table">
                            <form class="row g-3" method="POST" enctype="multipart/form-data">
                                <div class="col-md-6">
                                    <label for="product_name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" name="product_name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($product['price']); ?>" id="price" name="price" required>
                                </div>
                                <div class="col-12">
                                    <label for="product_image" class="form-label">Product Image</label>
                                    <input type="file" class="form-control" id="product_image" name="product_image">
                                    <br>
                                    <img src="<?php echo htmlspecialchars($product['product_image']); ?>" width="100">
                                </div>
                                <div class="col-12">
                                    <button type="submit" name="update_product" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
