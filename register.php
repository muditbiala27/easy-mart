<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $user_type = trim($_POST['user_type']); // New user type field
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($name) || empty($email) || empty($phone) || empty($user_type) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (!in_array($user_type, ['Seller', 'User'])) {
        $error = "Invalid user type!";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already registered!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, phone, user_type, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $phone, $user_type, $hashed_password);
            if ($stmt->execute()) {
                header("Location: login.php?success=Registered successfully! Please login.");
                exit();
            } else {
                $error = "Error registering user!";
            }
        }
        $stmt->close();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pro | Register</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-primary-subtle">
        <div class="container">
          <a class="navbar-brand logo text-uppercase text-white fs-3" href="index.php">
          <img src="images/easymart.png" alt="Logo" class="img-fluid" style="height: 70px; width:180px;">
        </a>
            <div class="d-flex">
                <a class="btn btn-primary me-2" href="login.php" role="button">Login</a>
            </div>
        </div>
    </nav>

    <section class="d-flex justify-content-center align-items-center" style="height: 100vh; background: url('./images/login.jpg'); background-size: cover;">
        <div class="col-lg-4">
            <h3 class="pb-2">Register Account</h3>
            <div class="bg-primary-subtle p-3 rounded-2 border shadow">
                <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">User Type</label>
                        <select class="form-control" name="user_type" required>
                            <option value="">Select User Type</option>
                            <option value="Seller">Seller</option>
                            <option value="User">User</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </section>
<br>
    <footer class="py-3 bg-secondary text-center">
        <div class="container">
            <p class="mb-0 text-white">© 2025 All Rights Reserved.</p>
        </div>
    </footer>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
