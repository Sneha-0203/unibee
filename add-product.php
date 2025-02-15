<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'][0];
    $product_description = $_POST['product_description'][0];
    $product_price = $_POST['product_price'][0];
    $offer = $_POST['offer'][0];
    $offer_price = $_POST['offer_price'][0];
    $available_stock = $_POST['available_stock'][0];
    $product_image = $_FILES['product_image']['name'][0];
    $product_image_tmp = $_FILES['product_image']['tmp_name'][0];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($product_image);

    if (move_uploaded_file($product_image_tmp, $target_file)) {
        $query = "INSERT INTO products (name, description, price, offer, offer_price, stock, image) 
                  VALUES ('$product_name', '$product_description', '$product_price', '$offer', '$offer_price', '$available_stock', '$target_file')";
        if (mysqli_query($conn, $query)) {
            $product_id = mysqli_insert_id($conn);
            header("Location: add-product-details.php?product_id=$product_id");
            exit();
        } else {
            echo "<script>alert('Error adding product.');</script>";
        }
    } else {
        echo "<script>alert('Error uploading image.');</script>";
    }
}
?>