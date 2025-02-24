<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product History</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 90%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        img {
            width: 80px;
            height: auto;
        }
        .delete-btn {
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Product History</h1>
        <table id="productTable">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Price ($)</th>
                    <th>Offer (%)</th>
                    <th>Offer Price ($)</th>
                    <th>Image</th>
                    <th>Stock</th>
                    <th>Supplier</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conn = new mysqli("localhost", "root", "", "unibee");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                
                $sql = "SELECT product_id, product_name, category, description, price, offer, offer_price, image_url, stock, supplier_name FROM products";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr id='row_" . $row['product_id'] . "'>
                            <td>{$row['product_id']}</td>
                            <td>{$row['product_name']}</td>
                            <td>{$row['category']}</td>
                            <td>{$row['description']}</td>
                            <td>\${$row['price']}</td>
                            <td>{$row['offer']}%</td>
                            <td>\${$row['offer_price']}</td>
                            <td><img src='{$row['image_url']}' alt='Product Image'></td>
                            <td>{$row['stock']}</td>
                            <td>{$row['supplier_name']}</td>
                            <td><button class='delete-btn' onclick='deleteProduct({$row['product_id']})'>Delete</button></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No products found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function deleteProduct(productId) {
            if (confirm("Are you sure you want to delete this product?")) {
                $.post("delete-product.php", { delete_id: productId }, function(response) {
                    if (response.status === "success") {
                        $("#row_" + productId).remove();
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }
                }, "json");
            }
        }
    </script>
</body>
</html>