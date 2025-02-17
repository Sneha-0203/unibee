<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uni_bee";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle file uploads
function uploadImage($image) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if file is an image
    $check = getimagesize($image["tmp_name"]);
    if ($check === false) {
        return false;
    }

    // Check file size (5MB maximum)
    if ($image["size"] > 5000000) {
        return false;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        return false;
    }

    if (move_uploaded_file($image["tmp_name"], $target_file)) {
        return $target_file;
    } else {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_names = $_POST['product_name'];
    $categories = $_POST['category'];
    $prices = $_POST['product_price'];
    $offers = $_POST['offer'];
    $offer_prices = $_POST['offer_price'];
    $stock = $_POST['available_stock'];
    $product_images = $_FILES['product_image'];

    foreach ($product_names as $index => $product_name) {
        // Calculate offer price
        $offer_price = isset($offers[$index]) && $offers[$index] > 0 ? $prices[$index] - ($prices[$index] * $offers[$index] / 100) : $prices[$index];

        // Upload image
        $image_url = uploadImage($product_images['tmp_name'][$index]);
        if (!$image_url) {
            echo "Image upload failed for product: $product_name";
            continue;
        }

        // Prepare SQL query
        $stmt = $conn->prepare("INSERT INTO products (product_name, category, price, offer, offer_price, stock, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssddids", $product_name, $categories[$index], $prices[$index], $offers[$index], $offer_price, $stock[$index], $image_url);
        
        if ($stmt->execute()) {
            echo "Product '$product_name' added successfully.";
        } else {
            echo "Error adding product: " . $stmt->error;
        }
    }

    // Redirect to product history page after submission
    header("Location: product-history.php");
    exit();
}

$conn->close();
?>
