<?php
session_start();
include("db.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);
$query = "SELECT id, user_id, product_ids, total_quantity, total_price FROM orders";
$result = $conn->query($query);
if (!$result) {
    die("Query failed: " . $conn->error);
}
function getProductNames($product_ids, $conn) {
    if (empty($product_ids)) return "N/A";
    $ids_array = explode(',', $product_ids);
    $ids_array = array_map('intval', $ids_array);
    $ids_string = implode(',', $ids_array);
    $query = "SELECT product_name FROM products WHERE id IN ($ids_string)";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $product_names = [];
        while ($row = $result->fetch_assoc()) {
            $product_names[] = $row['product_name'];
        }
        return implode(', ', $product_names);
    }
    return "N/A";
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
    <title>Pro Users</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
</head>
<body>
  <header class="bg-dark text-white py-3">
      <div class="container d-flex justify-content-between align-items-center">
          <h3 class="mb-0">Pro User</h3>

          <nav>
              <ul class="nav">
                  <li class="nav-item">
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
                            <h4 class="mb-1">Order List</h4>
                        </div>
                        <div class="booking-table">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Order ID</th>
                                            <th class="text-center">Product Name</th>
                                            <th class="text-center">Total Quantity</th>
                                            <th class="text-center">Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result->num_rows > 0): ?>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td class="text-center"><?= htmlspecialchars($row['id']); ?></td>
                                                    <td class="text-center"><?= htmlspecialchars(getProductNames($row['product_ids'], $conn)); ?></td>
                                                    <td class="text-center"><?= htmlspecialchars($row['total_quantity']); ?></td>
                                                    <td class="text-center">$<?= htmlspecialchars($row['total_price']); ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="4" class="text-center">No orders found.</td></tr>
                                        <?php endif; ?>
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
