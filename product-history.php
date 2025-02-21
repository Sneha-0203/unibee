<?php
// Database connection
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "unibee");

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $delete_sql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    $response = ["status" => "error", "message" => "Failed to delete product."];
    
    if ($stmt->execute()) {
        $response = ["status" => "success", "message" => "Product deleted successfully."];
    }
    
    echo json_encode($response);
    exit;
}

// Fetch product history
$sql = "SELECT * FROM products ORDER BY product_id DESC";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Return JSON response
echo json_encode($products);
$conn->close();
?>
