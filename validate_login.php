<?php
session_start();
$conn = new mysqli("localhost", "root", "", "lms");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve username and password from POST request
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

echo "Username:".$username;
echo "password:".$password;

// Query the database for the user
$sql = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username,$password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
        // Redirect to dashboard
        $_SESSION['username'] = $username;
        header("Location: main.php");
        exit;
    
}else{
    // Redirect back to login with an error message
    header("Location: login.php?error=1");
    exit;
}
?>
