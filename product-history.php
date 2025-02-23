<?php
// Set response type to JSON
header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "root", "", "unibee");

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Handle DELETE request securely
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    
    // Verify if product exists before deleting
    $check_sql = "SELECT product_id FROM products WHERE product_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $delete_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $delete_sql = "DELETE FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $delete_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Product deleted successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete product."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Product not found."]);
    }

    $check_stmt->close();
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
echo json_encode([
    "status" => "success",
    "message" => count($products) > 0 ? "Products retrieved successfully." : "No products found.",
    "data" => $products
]);

$conn->close();
?>
