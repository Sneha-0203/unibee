
<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database_name');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Loop through each product entry
    foreach ($_POST['product_id'] as $index => $productId) {
        // Retrieve form data
        $productName = $_POST['product_name'][$index];
        $category = $_POST['category'][$index];
        $description = $_POST['product_description'][$index];
        $price = $_POST['product_price'][$index];
        $offer = $_POST['offer'][$index];
        $offerPrice = $_POST['offer_price'][$index];
        $stock = $_POST['available_stock'][$index];
        $supplierName = $_POST['supplier_name'][$index];
        $supplierContact = $_POST['supplier_contact'][$index];
        $supplierEmail = $_POST['supplier_email'][$index];
        $supplierAddress = $_POST['supplier_address'][$index];

        // Handle file upload (product image)
        $imageName = $_FILES['product_image']['name'][$index];
        $imageTmpName = $_FILES['product_image']['tmp_name'][$index];
        $imagePath = "uploads/" . basename($imageName);

        // Move uploaded file to the uploads directory
        if (move_uploaded_file($imageTmpName, $imagePath)) {
            // Insert product data into the database
            $stmt = $conn->prepare("INSERT INTO products (product_id, product_name, category, description, price, offer, offer_price, stock, supplier_name, supplier_contact, supplier_email, supplier_address, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssdddisssss", $productId, $productName, $category, $description, $price, $offer, $offerPrice, $stock, $supplierName, $supplierContact, $supplierEmail, $supplierAddress, $imagePath);
            $stmt->execute();
        } else {
            echo "Error uploading file.";
        }
    }

    // Close the database connection
    $stmt->close();
    $conn->close();

    // Redirect to the Product History page
    header("Location: product-history.php");
    exit();
} else {
    echo "Invalid request method.";
}
?>