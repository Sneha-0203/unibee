<?php
// Include database connection
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    for ($i = 0; $i < count($_POST['product_name']); $i++) {
        $product_name = $_POST['product_name'][$i];
        $product_description = $_POST['product_description'][$i];
        $product_price = $_POST['product_price'][$i];
        $product_image = $_FILES['product_image']['name'][$i];
        $product_image_tmp = $_FILES['product_image']['tmp_name'][$i];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($product_image);

        if (move_uploaded_file($product_image_tmp, $target_file)) {
            $query = "INSERT INTO products (name, description, price, image) 
                      VALUES ('$product_name', '$product_description', '$product_price', '$target_file')";
            if (mysqli_query($conn, $query)) {
                // Get the inserted product ID
                $product_id = mysqli_insert_id($conn);

                // Redirect to details page with product ID
                header("Location: add-product-details.php?product_id=$product_id");
                exit();
            } else {
                echo "<script>alert('Error adding product.');</script>";
                break;
            }
        } else {
            echo "<script>alert('Error uploading image for product $product_name.');</script>";
            break;
        }
    }
}
?>
