<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_wrld";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user details
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email FROM usrmgm WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($current_username, $current_email);
$stmt->fetch();
$stmt->close();

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - The Food World</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<header>
    <h1>Welcome, <?php echo htmlspecialchars($current_username); ?>!</h1>
    <p>This is your dashboard.</p>
</header>

<nav>
    <a href="index.html">Home</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="dashboard-container">
    <h1>Dashboard</h1>
    <form action="update.php" method="post">
        <h2>Update Your Information</h2>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($current_username); ?>" required>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($current_email); ?>" required>
        <label for="password">New Password</label>
        <input type="password" id="password" name="password">
        <label for="confirm_password">Confirm New Password</label>
        <input type="password" id="confirm_password" name="confirm_password">
        <button type="submit">Update</button>
    </form>
    <form action="delete.php" method="post">
        <button type="submit" class="delete-button">Delete Account</button>
    </form>
</div>

</body>
</html>
