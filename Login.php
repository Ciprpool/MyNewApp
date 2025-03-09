<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$database = "my_actual_database"; // Change this to your actual database name

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email']) || !isset($data['password'])) { // Role is NOT needed in login
    echo json_encode(["success" => false, "message" => "Missing email or password"]);
    exit();
}

$email = $conn->real_escape_string($data['email']);
$entered_password = $data['password']; // User's entered password

// Check if user exists
$query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($entered_password, $user['password'])) { // ✅ Correct usage of password_verify()
        echo json_encode(["success" => true, "role" => $user['role']]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid password"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "User not found"]);
}

$conn->close();
?>