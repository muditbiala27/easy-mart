<?php
session_start();

// Redirect to index.php if cart is empty
if (!isset($_SESSION["cart"]) || empty($_SESSION["cart"])) {
    header("Location: index.php");
    exit;
}

$isLoggedIn = isset($_SESSION["user_id"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container py-5">
        <h2 class="text-center">Your Cart</h2>
        <ul class="list-group" id="cart-list">
            <?php
            include("db.php");  // Include database connection

            foreach ($_SESSION["cart"] as $id => $quantity) {
                $stmt = $conn->prepare("SELECT product_name, product_image FROM products WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $product = $result->fetch_assoc();

                $productName = htmlspecialchars($product['product_name'] ?? "Unknown Product");
                $productImage = htmlspecialchars($product['product_image'] ?? "placeholder.jpg"); // Default image if not found
            ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                       <span><?="Product Name = " .  $productName ?> - Quantity: <?= $quantity ?></span>
                    </div>
                    <img src="seller/<?= $productImage ?>" width="100" height="100" class="me-3 rounded">
                    <button class="btn btn-danger btn-sm remove-from-cart" data-id="<?= $id ?>">Remove</button>
                </li>
            <?php
                $stmt->close();
            }
            ?>
        </ul>
        <div class="text-center mt-4">
            <a href="<?= $isLoggedIn ? 'checkout.php' : 'login.php' ?>" class="btn btn-primary" id="checkout-btn">Proceed to Checkout</a>
        </div>
    </div>

    <script>
    $(document).ready(function(){
        $(".remove-from-cart").click(function(){
            var product_id = $(this).data("id");
            var button = $(this);

            $.ajax({
                url: "remove_from_cart.php",
                method: "POST",
                data: { product_id: product_id },
                success: function(response){
                    if(response == "removed"){
                        button.closest("li").fadeOut(300, function(){
                            $(this).remove();
                            if ($("#cart-list").children().length === 0) {
                                window.location.href = "index.php";
                            }
                        });
                    }
                }
            });
        });
    });
    </script>
</body>
</html>
