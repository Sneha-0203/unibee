<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'unibee');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Save multiple products
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_ids = $_POST['product_id'];
    $product_names = $_POST['product_name'];
    $categories = $_POST['category'];
    $descriptions = $_POST['product_description'];
    $prices = $_POST['product_price'];
    $offers = $_POST['offer'];
    $offer_prices = $_POST['offer_price'];
    $stocks = $_POST['available_stock'];
    $supplier_names = $_POST['supplier_name'];
    $supplier_contacts = $_POST['supplier_contact'];
    $supplier_emails = $_POST['supplier_email'];
    $supplier_addresses = $_POST['supplier_address'];

    for ($i = 0; $i < count($product_ids); $i++) {
        $sql = "INSERT INTO products 
                (product_id, product_name, category, description, price, offer, offer_price, stock, supplier_name, supplier_contact, supplier_email, supplier_address) 
                VALUES 
                ('{$product_ids[$i]}', '{$product_names[$i]}', '{$categories[$i]}', '{$descriptions[$i]}', '{$prices[$i]}', '{$offers[$i]}', '{$offer_prices[$i]}', '{$stocks[$i]}', '{$supplier_names[$i]}', '{$supplier_contacts[$i]}', '{$supplier_emails[$i]}', '{$supplier_addresses[$i]}')";

        $conn->query($sql);
    }

    // Redirect to Product History
    header("Location: product-history.html");
    exit;
}

$conn->close();
?>
