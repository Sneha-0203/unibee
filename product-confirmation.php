<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "unibee";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("<h3 style='color:red;'>❌ Connection failed: " . $conn->connect_error . "</h3>");
}

$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
        .container { max-width: 500px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; box-shadow: 2px 2px 10px rgba(0,0,0,0.1); }
        .success { color: green; }
        .error { color: red; }
        .btn { display: inline-block; margin-top: 15px; padding: 10px 15px; text-decoration: none; background: #007BFF; color: white; border-radius: 5px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>

<div class="container">
    <?php
    if ($product_id > 0) {
        $sql = "SELECT * FROM product_history WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            echo "<h3 class='success'>✅ Product Added Successfully! Product ID: #" . $product['product_id'] . "</h3>";
            echo "<p><strong>Name:</strong> " . htmlspecialchars($product['product_name']) . "</p>";
            echo "<p><strong>Category:</strong> " . htmlspecialchars($product['category']) . "</p>";
            echo "<p><strong>Price:</strong> $" . htmlspecialchars($product['price']) . "</p>";
            echo "<p><strong>Stock:</strong> " . htmlspecialchars($product['stock']) . "</p>";
            echo "<p><strong>Description:</strong> " . htmlspecialchars($product['description']) . "</p>";
            echo "<p><strong>Added On:</strong> " . htmlspecialchars($product['created_at']) . "</p>";
        } else {
            echo "<h3 class='error'>❌ Product Not Found!</h3>";
        }
        $stmt->close();
    } else {
        echo "<h3 class='error'>❌ No Product ID Provided!</h3>";
    }

    $conn->close();
    ?>
    <br>
    <a href="product-history.php" class="btn">🔙 Back to Product History</a>
</div>

</body>
</html>
