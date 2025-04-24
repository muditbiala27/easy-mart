<?php
session_start();
include("db.php");
$sql = "SELECT * FROM products ORDER BY id DESC";
$result = $conn->query($sql);
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
    <title>Pro Seller</title>
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
                    <h4 class="mb-1">Products List</h4>
                 </div>
                 <div class="booking-table">
                    <div class="table-responsive">
                       <table class="table table-bordered table-striped">
                          <thead>
                             <tr>
                                <th class="text-center" scope="col">#</th>
                                <th class="text-center" scope="col">Product Name</th>
                                <th class="text-center" scope="col">Price</th>
                                <th class="text-center" scope="col">Product Image</th>
                                <th class="text-center" scope="col">Action</th>
                             </tr>
                          </thead>
                          <tbody>
                             <?php
                             if ($result->num_rows > 0) {
                                 $count = 1;
                                 while ($row = $result->fetch_assoc()) { ?>
                                     <tr>
                                        <td class="text-center"><?php echo $count++; ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($row['product_name']); ?></td>
                                        <td class="text-center">$<?php echo htmlspecialchars($row['price']); ?></td>
                                        <td class="text-center">
                                          <img src="<?php echo htmlspecialchars($row['product_image']); ?>" width="70" height="70">
                                        </td>
                                        <td class="text-center">
                                            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                     </tr>
                                <?php }
                             } else { ?>
                                <tr>
                                   <td colspan="5" class="text-center">No products found</td>
                                </tr>
                             <?php } ?>
                          </tbody>
                       </table>
                    </div>
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
