<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database conection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "coffeeshop";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Helper function for returning JSON response
function respond($status, $message, $data = null) {
    echo json_encode(["status" => $status, "message" => $message, "data" => $data]);
    exit();
}

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

// Create (POST): Add item to cart
if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['user_id']) && isset($data['product_id']) && isset($data['quantity'])) {
        $user_id = $data['user_id'];
        $product_id = $data['product_id'];
        $quantity = $data['quantity'];
        $created_at = date("Y-m-d H:i:s"); // Current timestamp

        $sql = "INSERT INTO cart (user_id, product_id, quantity, created_at) 
                VALUES ('$user_id', '$product_id', '$quantity', '$created_at')";

        if ($conn->query($sql) === TRUE) {
            respond('success', 'Item added to cart successfully');
        } else {
            respond('error', 'Failed to add item to cart', $conn->error);
        }
    } else {
        respond('error', 'Missing required fields');
    }
}

// Read (GET): Retrieve cart items for a specific user
else if ($method === 'GET') {
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

    if ($user_id) {
        $sql = "SELECT * FROM cart WHERE user_id = '$user_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $items = [];
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            respond('success', 'Cart items retrieved successfully', $items);
        } else {
            respond('error', 'No cart items found for this user');
        }
    } else {
        respond('error', 'User ID is required');
    }
}

// Update (PUT): Update cart item quantity
else if ($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['cart_id']) && isset($data['quantity'])) {
        $cart_id = $data['cart_id'];
        $quantity = $data['quantity'];

        $sql = "UPDATE cart SET quantity = '$quantity' WHERE cart_id = '$cart_id'";

        if ($conn->query($sql) === TRUE) {
            respond('success', 'Cart item updated successfully');
        } else {
            respond('error', 'Failed to update cart item', $conn->error);
        }
    } else {
        respond('error', 'Missing required fields');
    }
}

// Delete (DELETE): Remove item from cart
else if ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['cart_id'])) {
        $cart_id = $data['cart_id'];

        $sql = "DELETE FROM cart WHERE cart_id = '$cart_id'";

        if ($conn->query($sql) === TRUE) {
            respond('success', 'Cart item removed successfully');
        } else {
            respond('error', 'Failed to remove cart item', $conn->error);
        }
    } else {
        respond('error', 'Cart ID is required');
    }
}

// Close the connection
$conn->close();
?>
