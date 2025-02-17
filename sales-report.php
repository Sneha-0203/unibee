<?php
// sales-report.php for Uni Bee Sales Report Page

// Database Connection
include 'db_connection.php';

$category = isset($_GET['category']) ? $_GET['category'] : 'All';
$sql = "SELECT p.product_name, p.category, s.quantity_sold, s.total_revenue, s.sale_date
        FROM sales s
        JOIN products p ON s.product_id = p.product_id";
if ($category !== 'All') {
    $sql .= " WHERE p.category = '" . mysqli_real_escape_string($conn, $category) . "'";
}
$sql .= " ORDER BY s.sale_date DESC";
$result = mysqli_query($conn, $sql);
?>