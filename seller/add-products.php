<?php
session_start();
include("db.php");

$message = "";

// Ensure the uploads directory exists
$upload_dir = "uploads/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    // Handle file upload
    if (!empty($_FILES['product_image']['name'])) {
        $image_name = basename($_FILES['product_image']['name']);
        $image_path = $upload_dir . time() . "_" . $image_name; // Unique filename
        $image_type = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));

        // Allow only certain file types
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (in_array($image_type, $allowed_types)) {
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $image_path)) {
                // Insert into database
                $sql = "INSERT INTO products (product_name, price, product_image) VALUES ('$product_name', '$price', '$image_path')";
                if ($conn->query($sql) === TRUE) {
                    $message = "<div class='alert alert-success'>Product added successfully!</div>";
                    header("Location: seller_dashboard.php");
                } else {
                    $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
                }
            } else {
                $message = "<div class='alert alert-danger'>Failed to upload image.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Invalid file type! Only JPG, PNG, GIF are allowed.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Please select an image.</div>";
    }
}

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $userQuery = "SELECT name FROM college_users WHERE id = ?";
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
    <title>Pro add products</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
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
                        <div class="mb-5">
                            <h4 class="mb-1">Add Product</h4>
                        </div>

                        <?php echo $message; ?>

                        <div class="booking-table">
                            <form class="row g-3" method="POST" enctype="multipart/form-data">
                                <div class="col-md-6">
                                    <label for="product_name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="price" name="price" required>
                                </div>
                                <div class="col-12">
                                    <label for="product_image" class="form-label">Product Image</label>
                                    <input type="file" class="form-control" id="product_image" name="product_image" required>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="py-3 bg-secondary text-center">
    <div class="container">
        <p class="mb-0 text-white">© 2025 All Rights Reserved.</p>
    </div>
</footer>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
