<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "unibee";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    
    // Fetch product details from database
    $sql = "SELECT * FROM product_history WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo "<h3>✅ Product Added Successfully! Product ID: #" . $product['product_id'] . "</h3>";
        echo "<p><strong>Name:</strong> " . htmlspecialchars($product['product_name']) . "</p>";
        echo "<p><strong>Category:</strong> " . htmlspecialchars($product['category']) . "</p>";
        echo "<p><strong>Price:</strong> $" . htmlspecialchars($product['price']) . "</p>";
        echo "<p><strong>Stock:</strong> " . htmlspecialchars($product['stock']) . "</p>";
        echo "<p><strong>Description:</strong> " . htmlspecialchars($product['description']) . "</p>";
        echo "<p><strong>Added On:</strong> " . htmlspecialchars($product['created_at']) . "</p>";
    } else {
        echo "<h3>❌ Product Not Found!</h3>";
    }
    
    $stmt->close();
} else {
    echo "<h3>❌ No Product ID Provided!</h3>";
}

$conn->close();
?>
