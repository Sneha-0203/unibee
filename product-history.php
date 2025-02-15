<?php
include('db.php');

$query = "SELECT * FROM producthistory";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo "<table border='1'>
            <tr>
                <th>Product ID</th>
                <th>Action</th>
                <th>Old Price</th>
                <th>New Price</th>
                <th>Old Stock</th>
                <th>New Stock</th>
                <th>Details</th>
                <th>Date</th>
            </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['product_id']}</td>
                <td>{$row['action']}</td>
                <td>" . ($row['old_price'] ? '$' . $row['old_price'] : 'N/A') . "</td>
                <td>" . ($row['new_price'] ? '$' . $row['new_price'] : 'N/A') . "</td>
                <td>" . ($row['old_stock'] ? $row['old_stock'] : 'N/A') . "</td>
                <td>" . ($row['new_stock'] ? $row['new_stock'] : 'N/A') . "</td>
                <td>{$row['details']}</td>
                <td>{$row['created_at']}</td>
            </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No product history available.</p>";
}
?>
