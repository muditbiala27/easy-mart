<?php
session_start();
include("db.php");
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid Request");
}
$product_id = intval($_GET['id']);
$result = $conn->query("SELECT product_image FROM products WHERE id = $product_id");
$product = $result->fetch_assoc();
if (!$product) {
    die("Product not found");
}
if (!empty($product['product_image']) && file_exists($product['product_image'])) {
    unlink($product['product_image']);
}
$conn->query("DELETE FROM products WHERE id = $product_id");
header("Location: seller_dashboard.php");
exit();
?>
