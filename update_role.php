<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "my_actual_database";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    
    // Update user role
    $stmt = $conn->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        echo "User successfully updated to admin!";
    } else {
        echo "Error updating user role.";
    }
    
    $stmt->close();
}

$conn->close();
?>
