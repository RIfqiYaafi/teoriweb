<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "coffeeshop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['username']) && isset($data['email']) && isset($data['password'])) {
        $username = $data['username'];
        $email = $data['email'];
        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Data inserted successfully."]);
        } else {
            echo json_encode(["error" => "Failed to insert data.", "details" => $conn->error]);
        }
    } else {
        echo json_encode(["error" => "Missing required fields."]);
    }
}

$conn->close();
?>
