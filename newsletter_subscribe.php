<?php
include "db_connection.php";

$response = ["status" => "error", "message" => ""]; // Default response

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $conn->real_escape_string($_POST['email']);

    $checkEmail = "SELECT * FROM newsletter WHERE email = '$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        $response["status"] = "exists";
        $response["message"] = "You are already subscribed!";
    } else {
        $insertQuery = "INSERT INTO newsletter (email) VALUES ('$email')";
        if ($conn->query($insertQuery) === TRUE) {
            $response["status"] = "success";
            $response["message"] = "Thank you for subscribing!";
        } else {
            $response["message"] = "Error: " . $conn->error;
        }
    }
}

$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
