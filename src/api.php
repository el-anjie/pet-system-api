<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require "db.php";

// Only POST allowed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, "error", "Method Not Allowed. Use POST request");
}

// Get inputs
$action = $_POST['action'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

//
function respond($http_code, $status, $message, $data = null) {
    http_response_code($http_code);
    echo json_encode([
        "status" => $status,
        "message" => $message,
        "data" => $data
    ]);
    exit;
}

// REGISTER 
if ($action === "register") {
    if (!$username || !$password)
        respond(400, "error", "Please fill all fields");

    $check = $conn->prepare("SELECT id FROM users WHERE username=?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0)
        respond(409, "error", "Username already exists");

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users(username,password) VALUES(?,?)");
    $stmt->bind_param("ss", $username, $hash);

    $stmt->execute()
        ? respond(201, "success", "Account created successfully")
        : respond(500, "error", "Registration failed");
}

// INVALID
else {
    respond(400, "error", "Invalid request action or action not yet implemented");
}

$conn->close();
?>