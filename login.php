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

session_start(); // Start the session to store user information

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $user_username = $_POST['username'];
    $user_password = $_POST['password'];

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT id, password FROM usrmgm WHERE username = ?");
    $stmt->bind_param("s", $user_username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password);

    if ($stmt->num_rows > 0) {
        // Fetch result
        $stmt->fetch();

        // Verify password
        if (password_verify($user_password, $hashed_password)) {
            // Password is correct, set session variables and redirect to dashboard
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $user_username;
            header("Location: dashboard.php"); // Redirect to the dashboard
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that username.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - The Food World</title>
    <link rel="stylesheet" href="css(login).css">
</head>
<body>

<header>
    <h1>The Food World</h1>
    <p>Login to access your account</p>
</header>

<nav>
    <a href="index.html">Home</a>
    <a href="login.php">Login</a>
    <a href="signup.php">Sign Up</a>
    <a href="contact.html">Contact Us</a>
</nav>

<div class="login-container">
    <form action="login.php" method="post">
        <h2>Login</h2>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
