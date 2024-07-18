<?php
$servername = "localhost"; // Change if your database server is different
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "food_wrld"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $user_username = $_POST['username'];
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form data
    if ($user_password === $confirm_password) {
        // Hash the password
        $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO usrmgm (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $user_username, $user_email, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: index.html"); // Redirect to a welcome page
            exit(); // Ensure no further script execution after redirect
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Passwords do not match.";
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - The Food World</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>

<header>
    <h1>The Food World</h1>
    <p>Create your account</p>
</header>

<nav>
    <a href="index.html">Home</a>
    <a href="login.html">Login</a>
    <a href="signup.html">Sign Up</a>
    <a href="contact.html">Contact Us</a>
</nav>

<div class="signup-container">
    <form action="signup.php" method="post">
        <h2>Sign Up</h2>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <button type="submit">Sign Up</button>
    </form>
</div>

</body>
</html>
