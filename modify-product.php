<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $stock = $_POST['stock'];
    $discount = $_POST['discount'];
    $supplier_name = $_POST['supplier_name'];
    $supplier_contact = $_POST['supplier_contact'];
    $supplier_email = $_POST['supplier_email'];
    $supplier_address = $_POST['supplier_address'];

    $query = "UPDATE producthistory SET new_stock='$stock', details='Supplier: $supplier_name, Contact: $supplier_contact, Email: $supplier_email, Address: $supplier_address, Discount: $discount%' WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        header("Location: product-history.php");
        exit();
    } else {
        echo "<script>alert('Error updating product.');</script>";
    }
}

$id = $_GET['id'];
$query = "SELECT * FROM producthistory WHERE id='$id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>