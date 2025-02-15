<?php
include('db.php');

$query = "SELECT * FROM producthistory ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

echo "<h2>Product History</h2>";
if (mysqli_num_rows($result) > 0) {
    echo "<table border='1' cellspacing='0' cellpadding='10'>
            <tr>
                <th>Product ID</th>
                <th>Action</th>
                <th>New Stock</th>
                <th>Details</th>
                <th>Date</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['product_id']}</td>
                <td>{$row['action']}</td>
                <td>{$row['new_stock']}</td>
                <td>{$row['details']}</td>
                <td>{$row['created_at']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No history available.</p>";
}
?>
