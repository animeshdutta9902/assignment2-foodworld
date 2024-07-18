<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "food_wrld";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $user_id = $_SESSION['user_id'];
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form data
    if ($new_password === $confirm_password) {
        if (!empty($new_password)) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE usrmgm SET username = ?, email = ?, password = ? WHERE id = ?");
            $stmt->bind_param("sssi", $new_username, $new_email, $hashed_password, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE usrmgm SET username = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $new_username, $new_email, $user_id);
        }

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['username'] = $new_username; // Update session username
            header("Location: dashboard.php"); // Redirect to the dashboard
            exit();
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
