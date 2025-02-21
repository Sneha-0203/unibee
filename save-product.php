<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "unibee");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $category = $_POST['category'];
    $description = $_POST['description'] ?? NULL;
    $price = $_POST['price'];
    $offer = $_POST['offer'] ?? NULL;
    $offer_price = $_POST['offer_price'] ?? NULL;
    $stock = $_POST['stock'];
    $supplier_name = $_POST['supplier_name'] ?? NULL;
    $supplier_contact = $_POST['supplier_contact'] ?? NULL;
    $supplier_email = $_POST['supplier_email'] ?? NULL;
    $supplier_address = $_POST['supplier_address'] ?? NULL;

    // Image upload handling
    $image_url = NULL;
    if (!empty($_FILES['image']['name'])) {
        $image_name = basename($_FILES['image']['name']);
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = "uploads/" . $image_name;
        
        if (move_uploaded_file($image_tmp, $image_path)) {
            $image_url = $image_path;
        } else {
            echo "Failed to upload image.";
            exit();
        }
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO products (product_name, category, description, price, offer, offer_price, image_url, stock, supplier_name, supplier_contact, supplier_email, supplier_address) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("sssdddsissss", $product_name, $category, $description, $price, $offer, $offer_price, $image_url, $stock, $supplier_name, $supplier_contact, $supplier_email, $supplier_address);

    if ($stmt->execute()) {
        header("Location: product-history.html"); // Redirect to product history page
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
}
$conn->close();
?>
