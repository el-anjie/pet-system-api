<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "api_playroom");

$pet_id = $_POST['pet_id'];
$pet_name = $_POST['pet_name'];
$pet_type = $_POST['pet_type'];

if (!$pet_id) {
    echo json_encode([
        "status" => "error",
        "message" => "pet_id is required"
    ]);
    exit();
}

$sql = "UPDATE pets 
        SET pet_name='$pet_name', pet_type='$pet_type' 
        WHERE id='$pet_id'";

if ($conn->query($sql)) {
    if ($conn->affected_rows > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "Pet updated successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "warning",
            "message" => "No pet found or no changes made"
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Update failed"
    ]);
}

?>