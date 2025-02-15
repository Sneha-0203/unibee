<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $stock = $_POST['stock'];
    $discount = $_POST['discount'];
    $supplier_name = $_POST['supplier_name'];
    $supplier_contact = $_POST['supplier_contact'];
    $supplier_email = $_POST['supplier_email'];
    $supplier_address = $_POST['supplier_address'];

    // Record additional details in product history
    $action = "Product Added with Additional Details";
    $details = "Supplier: $supplier_name, Contact: $supplier_contact, Email: $supplier_email, Address: $supplier_address, Discount: $discount%";
    
    $history_query = "INSERT INTO producthistory (product_id, action, new_stock, details) 
                      VALUES ('$product_id', '$action', '$stock', '$details')";

    if (mysqli_query($conn, $history_query)) {
        header("Location: product-history.php");
        exit();
    } else {
        echo "<script>alert('Error logging history.');</script>";
    }
}
?>