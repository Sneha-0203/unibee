<?php
$conn = new mysqli("localhost", "root", "", "unibee");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete request
if (isset($_POST['delete_id'])) {
    $product_id = intval($_POST['delete_id']);
    $sql = "DELETE FROM products WHERE product_id = $product_id";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Product deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error deleting product"]);
    }
    exit;
}

// Handle update request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $category = $conn->real_escape_string($_POST['category']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $offer = floatval($_POST['offer']);
    $offer_price = floatval($_POST['offer_price']);
    $stock = intval($_POST['stock']);
    $supplier_name = $conn->real_escape_string($_POST['supplier_name']);

    $sql = "UPDATE products SET 
                product_name='$product_name', category='$category', description='$description', 
                price='$price', offer='$offer', offer_price='$offer_price', 
                stock='$stock', supplier_name='$supplier_name' 
            WHERE product_id=$product_id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Product updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating product"]);
    }
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product History</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    /* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    height: 100vh;
}

/* Container */
.container {
    max-width: 80%;
    margin: auto;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

/* Table Styles */
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

/* Image Styling */
img {
    width: 80px;
    height: auto;
}

/* Buttons */
.delete-btn, .edit-btn, .save-btn {
    padding: 5px 10px;
    border: none;
    cursor: pointer;
    border-radius: 4px;
    font-size: 14px;
}
.delete-btn { background: red; color: white; }
.edit-btn { background: orange; color: white; }
.save-btn { background: green; color: white; display: none; }

/* Sidebar */
.sidebar {
    width: 250px;
    background-color: #2a9d8f;
    color: white;
    padding-top: 20px;
    position: fixed;
    height: 100%;
    overflow-y: auto;
    transition: transform 0.3s ease-in-out;
}
.sidebar-header {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    padding-bottom: 20px;
}
.sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}
.sidebar-menu li {
    padding: 15px 20px;
}
.sidebar-menu li a {
    color: white;
    text-decoration: none;
    font-size: 18px;
    display: block;
    transition: background 0.3s, padding-left 0.3s;
}
.sidebar-menu li a:hover {
    background-color: #21867a;
    border-radius: 5px;
    padding-left: 15px;
}
.logout-button {
    color: #e63946 !important;
    font-weight: bold;
}

/* Main Content */
.main-content {
    margin-left: 250px;
    padding: 40px;
    background-color: #f4f4f4;
    transition: margin-left 0.3s;
}

/* Mobile Menu */
.hamburger {
    display: none;
    font-size: 30px;
    cursor: pointer;
    color: #2a9d8f;
    padding: 10px;
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1000;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    .main-content {
        margin-left: 0;
        padding: 20px;
    }
    .hamburger {
        display: block;
    }
    .sidebar.open {
        transform: translateX(0);
    }
}

    </style>
</head>
<body>
<div class="hamburger" onclick="toggleSidebar()">☰</div>
    
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">Uni Bee</div>
        <ul class="sidebar-menu">
            <li><a href="admin-dashboard.html">Dashboard</a></li>
            <li><a href="add-product.html">Add Products</a></li>
            <li><a href="product-history.php">Product History</a></li>
            <li><a href="sales-report.html">Sales Report</a></li>
            <li><a href="#" class="logout-button" onclick="confirmLogout()">Logout</a></li>
        </ul>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }

        function confirmLogout() {
            const saveChanges = confirm("Do you want to save your changes before logging out?");
            if (saveChanges) {
                alert("Changes saved successfully!");
            }
            const confirmLogout = confirm("Are you sure you want to log out?");
            if (confirmLogout) {
                window.location.href = 'index.html';
            }
        }
    </script>
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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT product_id, product_name, category, description, price, offer, offer_price, image_url, stock, supplier_name FROM products";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr id='row_{$row['product_id']}'>
                        <td>{$row['product_id']}</td>
                        <td contenteditable='false' class='editable'>{$row['product_name']}</td>
                        <td contenteditable='false' class='editable'>{$row['category']}</td>
                        <td contenteditable='false' class='editable'>{$row['description']}</td>
                        <td contenteditable='false' class='editable'>{$row['price']}</td>
                        <td contenteditable='false' class='editable'>{$row['offer']}</td>
                        <td contenteditable='false' class='editable'>{$row['offer_price']}</td>
                        <td><img src='{$row['image_url']}' alt='Product Image'></td>
                        <td contenteditable='false' class='editable'>{$row['stock']}</td>
                        <td contenteditable='false' class='editable'>{$row['supplier_name']}</td>
                        <td>
                            <button class='edit-btn' onclick='editProduct({$row['product_id']})'>Edit</button>
                            <button class='save-btn' onclick='saveProduct({$row['product_id']})'>Save</button>
                            <button class='delete-btn' onclick='deleteProduct({$row['product_id']})'>Delete</button>
                        </td>
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
    function editProduct(productId) {
        let row = document.getElementById("row_" + productId);
        let cells = row.getElementsByClassName("editable");

        for (let cell of cells) {
            cell.contentEditable = "true";
            cell.style.background = "#f9f9a3";
        }

        row.querySelector(".edit-btn").style.display = "none";
        row.querySelector(".save-btn").style.display = "inline-block";
    }

    function saveProduct(productId) {
        let row = document.getElementById("row_" + productId);
        let cells = row.getElementsByClassName("editable");

        let data = {
            product_id: productId,
            product_name: cells[0].innerText,
            category: cells[1].innerText,
            description: cells[2].innerText,
            price: cells[3].innerText,
            offer: cells[4].innerText,
            offer_price: cells[5].innerText,
            stock: cells[6].innerText,
            supplier_name: cells[7].innerText
        };

        $.post("product-history.php", data, function(response) {
            alert(response.message);
            if (response.status === "success") {
                for (let cell of cells) {
                    cell.contentEditable = "false";
                    cell.style.background = "none";
                }
                row.querySelector(".edit-btn").style.display = "inline-block";
                row.querySelector(".save-btn").style.display = "none";
            }
        }, "json");
    }

    function deleteProduct(productId) {
        if (confirm("Are you sure you want to delete this product?")) {
            $.post("product-history.php", { delete_id: productId }, function(response) {
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
