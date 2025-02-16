<?php
// Connect to database
include 'db.php';

// Fetch all product history
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['product_id']}</td>
                <td>{$row['product_name']}</td>
                <td>{$row['category']}</td>
                <td>{$row['description']}</td>
                <td>{$row['price']}</td>
                <td>{$row['offer']}</td>
                <td>{$row['offer_price']}</td>
                <td><img src='uploads/{$row['product_image']}' alt='Image' width='50'></td>
                <td>{$row['stock']}</td>
                <td>{$row['supplier_name']}</td>
                <td>{$row['supplier_contact']}</td>
                <td>{$row['supplier_email']}</td>
                <td>{$row['supplier_address']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='14'>No product history found.</td></tr>";
}

mysqli_close($conn);
?>
