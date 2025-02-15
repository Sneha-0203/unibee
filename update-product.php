<?php
// Include your database connection
include('db_connection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get data from the form
    $product_id = $_POST['product_id']; // Product ID to identify which product to update
    $product_name = $_POST['product_name']; // New product name
    $price = $_POST['price']; // New price
    $stock = $_POST['stock']; // New stock level

    // Sanitize input (in real-world applications, this should be more robust)
    $product_id = intval($product_id);
    $product_name = mysqli_real_escape_string($conn, $product_name);
