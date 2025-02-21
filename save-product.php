<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "unibee";

// Connect to Database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect Form Data
$product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
$category = mysqli_real_escape_string($conn, $_POST['category']);
$product_description = mysqli_real_escape_string($conn, $_POST['product_description']);
$product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
$offer = mysqli_real_escape_string($conn, $_POST['offer']);
$offer_price = mysqli_real_escape_string($conn, $_POST['offer_price']);
$available_stock = mysqli_real_escape_string($conn, $_POST['available_stock']);
$supplier_email = mysqli_real_escape_string($conn, $_POST['supplier_email']);
$supplier_contact = mysqli_real_escape_string($conn, $_POST['supplier_contact']);

// Insert Data into Table
$sql = "INSERT INTO product_history 
    (product_name, category, product_description, product_price, offer, offer_price, available_stock, supplier_email, supplier_contact)
    VALUES ('$product_name', '$category', '$product_description', '$product_price', '$offer', '$offer_price', '$available_stock', '$supplier_email', '$supplier_contact')";

if ($conn->query($sql) === TRUE) {
    $product_id = $conn->insert_id; // Get the last inserted Product ID
    header("Location: product-history.php?product_id=" . $product_id);
    exit;
} else {
    echo "<script>alert('Error: " . $conn->error . "'); window.history.back();</script>";
}

$conn->close();
?>
