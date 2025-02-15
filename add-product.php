<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp = $_FILES['product_image']['tmp_name'];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($product_image);

    if (move_uploaded_file($product_image_tmp, $target_file)) {
        $query = "INSERT INTO products (name, description, price, image) 
                  VALUES ('$product_name', '$product_description', '$product_price', '$target_file')";
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
