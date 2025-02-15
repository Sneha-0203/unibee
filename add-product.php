<?php
// Include database connection
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Loop through all products
    for ($i = 0; $i < count($_POST['product_name']); $i++) {
        // Retrieve product data from the form
        $product_name = $_POST['product_name'][$i];
        $product_description = $_POST['product_description'][$i];
        $product_price = $_POST['product_price'][$i];
        $product_image = $_FILES['product_image']['name'][$i];
        $product_image_tmp = $_FILES['product_image']['tmp_name'][$i];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($product_image);
        
        // Move uploaded file to target directory
        if (move_uploaded_file($product_image_tmp, $target_file)) {
            // Insert the product into the database
            $query = "INSERT INTO products (name, description, price, image) 
                      VALUES ('$product_name', '$product_description', '$product_price', '$target_file')";
            if (mysqli_query($conn, $query)) {
                // Get the last inserted product ID
                $product_id = mysqli_insert_id($conn);
                
                // Insert the product history (log the action)
                $action = 'Product Added';
                $old_price = NULL;  // Initially, there is no old price
                $new_price = $product_price;
                $old_stock = NULL;  // Initially, no stock information
                $new_stock = 0;  // You can set the initial stock to 0 or whatever value you prefer
                $details = 'Product added to the catalog with initial details.';
                
                // Insert into producthistory table
                $history_query = "INSERT INTO producthistory (product_id, action, old_price, new_price, old_stock, new_stock, details)
                                  VALUES ('$product_id', '$action', '$old_price', '$new_price', '$old_stock', '$new_stock', '$details')";
                if (!mysqli_query($conn, $history_query)) {
                    echo "<script>alert('Error logging product history.');</script>";
                    break;
                }
            } else {
                echo "<script>alert('Error adding product.');</script>";
                break;
            }
        } else {
            echo "<script>alert('Error uploading image for product $product_name.');</script>";
            break;
        }
    }

    // Redirect to the dashboard or another page after the products have been added
    echo "<script>alert('Products added successfully!'); window.location.href = 'admin-dashboard.php';</script>";
}
?>
