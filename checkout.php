<?php
session_start();
include("db.php");
if (!isset($_SESSION["cart"]) || empty($_SESSION["cart"])) {
    header("Location: index.php");
    exit();
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

$total_quantity = 0;
$total_price = 0;
$product_ids = []; // Store product IDs

foreach ($_SESSION["cart"] as $product_id => $quantity) {
    $query = "SELECT price FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        $price = $product['price'];
        $subtotal = $price * $quantity;
        $total_price += $subtotal;
        $total_quantity += $quantity;
        $product_ids[] = $product_id;
    }
}
$product_ids_string = implode(",", $product_ids);

// Insert single order row
$insert_order = "INSERT INTO orders (user_id, product_ids, total_quantity, total_price) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($insert_order);
$stmt->bind_param("isid", $user_id, $product_ids_string, $total_quantity, $total_price);
$stmt->execute();

// Clear the cart
unset($_SESSION["cart"]);

// Redirect to success page
header("Location: order_success.php");
exit();
?>
