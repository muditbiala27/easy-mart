<?php
session_start();

// Ensure user is logged in and email is available
if (!isset($_SESSION['email'])) {
    die("User email not found. Please log in.");
}

$userEmail = $_SESSION['email'];
$subject = "Order Confirmation";
$message = "
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h2>Thank You for Your Order!</h2>
    <p>Your order has been successfully placed. We will notify you once it is shipped.</p>
    <p><strong>Order Details:</strong></p>
    <ul>";

if (!empty($_SESSION["cart"])) {
    foreach ($_SESSION["cart"] as $id => $quantity) {
        $message .= "<li>Product ID: $id - Quantity: $quantity</li>";
    }
}

$message .= "
    </ul>
    <p>We appreciate your business!</p>
</body>
</html>";

// Save email to a file (simulating email sending)
$emailLog = "To: $userEmail\nSubject: $subject\n\n$message\n-----------------------\n";
file_put_contents("email_log.txt", $emailLog, FILE_APPEND);

$emailStatus = "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Placed</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5 text-center">
        <h2 class="text-success">Order Placed Successfully!</h2>
        <p>Thank you for your purchase. Your order has been recorded.</p>
        <p class="text-info"><?= $emailStatus; ?></p>
        <a href="index.php" class="btn btn-primary">Continue Shopping</a>
    </div>
</body>
</html>
