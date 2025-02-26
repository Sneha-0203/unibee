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
    $offer = $_POST['offer'] ?? 0;
    $stock = $_POST['stock'];
    $supplier_name = $_POST['supplier_name'] ?? NULL;
    $supplier_contact = $_POST['supplier_contact'] ?? NULL;
    $supplier_email = $_POST['supplier_email'] ?? NULL;
    $supplier_address = $_POST['supplier_address'] ?? NULL;

    // ✅ Calculate Offer Price (if discount is provided)
    $offer_price = ($offer > 0) ? $price - ($price * ($offer / 100)) : $price;

    // ✅ Image upload handling
    $image_url = NULL;
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . "_" . basename($_FILES['image']['name']); // Unique file name
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = "uploads/" . $image_name;

        // Ensure the "uploads" directory exists
        if (!is_dir("uploads")) {
            mkdir("uploads", 0777, true);
        }

        if (move_uploaded_file($image_tmp, $image_path)) {
            $image_url = $image_path;
        } else {
            die("Error: Failed to upload image.");
        }
    }

    // ✅ Handle multiple selected sizes
    $size = isset($_POST['shoe_size']) ? implode(", ", $_POST['shoe_size']) : NULL;

    // ✅ Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO products (product_name, category, description, price, offer, offer_price, image_url, stock, supplier_name, supplier_contact, supplier_email, supplier_address, size) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("sssdddsisssss", $product_name, $category, $description, $price, $offer, $offer_price, $image_url, $stock, $supplier_name, $supplier_contact, $supplier_email, $supplier_address, $size);

    if ($stmt->execute()) {
        // ✅ Redirect to PHP product history page (Ensure it fetches from DB)
        header("Location: product-history.php?status=success");
        exit();
    } else {
        die("Error: " . $stmt->error);
    }

    // Close statement and connection
    $stmt->close();
}
$conn->close();

?>
