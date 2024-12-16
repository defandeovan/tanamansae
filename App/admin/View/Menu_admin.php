<?php
session_start();
include("../Controller/db.php");
include("../Controller/Edit.php");
// Query untuk mengambil semua produk
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
$controller = new ProductController($conn);

$product_id = null;
$product = null;
$message = "";

// Ambil semua produk untuk dropdown
$products = $controller->getAllProducts();

// Jika ada ID di URL, ambil detail produk
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $product = $controller->getProductById($product_id);

    if (!$product) {
        $message = "Produk dengan ID $product_id tidak ditemukan!";
    }
}

// Jika ada permintaan POST, update produk
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $product_id) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    if (!empty($name) && is_numeric($price) && !empty($description)) {
        $isUpdated = $controller->updateProduct($product_id, $name, $price, $description);
        if ($isUpdated) {
            $message = "Produk berhasil diperbarui!";
            $product = $controller->getProductById($product_id); // Refresh data produk
        } else {
            $message = "Terjadi kesalahan saat memperbarui produk.";
        }
    } else {
        $message = "Harap isi semua data dengan benar!";
    }
}


if (!isset($_SESSION['username'])) {
    header("Location:login.html");
    exit();






}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    
    <title>Add Product</title>
    <script>
        // Function to handle form submission via AJAX
        function addProduct(event) {
    event.preventDefault();

    // Get form data
    var formData = new FormData();
    formData.append("name", document.getElementById("name").value);
    formData.append("description", document.getElementById("description").value);
    formData.append("price", document.getElementById("price").value);
    formData.append("image", document.getElementById("image").files[0]);
    formData.append("image2", document.getElementById("image2").files[0]);
    formData.append("image3", document.getElementById("image3").files[0]);
    formData.append("image4", document.getElementById("image4").files[0]);

    // Create XMLHttpRequest object
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../Controller/add.php", true);

    // Handle response
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            alert(response.message);
            if (response.status === "success") {
                document.getElementById("addProductForm").reset();
            }
        }
    };

    // Send form data
    xhr.send(formData);
}

    </script>

</head>

<body>
<h2>Edit Product</h2>

<?php if ($products->num_rows > 0): ?>
    <form action="Menu_admin.php" method="GET">
        <label for="product_id">Select Product to Edit:</label>
        <select id="product_id" name="id" required>
            <option value="">-- Pilih Produk --</option>
            <?php while ($row = $products->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $product_id) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($row['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Edit Product</button>
    </form>
<?php else: ?>
    <p>Tidak ada produk tersedia untuk diedit!</p>
<?php endif; ?>

<?php if (!empty($message)): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<?php if ($product): ?>
    <form action="Menu_Admin.php?id=<?php echo htmlspecialchars($product_id); ?>" method="POST">
        <label for="name">Product Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br><br>

        <label for="price">Price:</label><br>
        <input type="number" step="0.01" id="price" name="price"
               value="<?php echo htmlspecialchars($product['price']); ?>" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" cols="50"
                  required><?php echo htmlspecialchars($product['description']); ?></textarea><br><br>

        <button type="submit">Update Product</button>
    </form>
<?php endif; ?>

<h1>Add Product</h1>
    <form action="../Controller/Add.php" method="POST" enctype="multipart/form-data">
        <!-- Input untuk Nama Produk -->
        <label for="name">Product Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <!-- Input untuk Deskripsi -->
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="5" required></textarea><br><br>

        <!-- Input untuk Harga -->
        <label for="price">Price:</label><br>
        <input type="number" id="price" name="price" step="0.01" min="0" required><br><br>

        <!-- Input untuk Gambar 1 -->
        <label for="image">Upload Image 1:</label><br>
        <input type="file" id="image" name="image" accept="image/*" required><br><br>

        <!-- Input untuk Gambar 2 -->
        <label for="image2">Upload Image 2:</label><br>
        <input type="file" id="image2" name="image2" accept="image/*" required><br><br>



        <!-- Tombol Submit -->
        <button type="submit">Add Product</button>
    </form>
    <?php

if ($result->num_rows > 0) {
    echo "<h2>Product List</h2>";
    echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Price</th><th>Description</th><th>Actions</th></tr>";

    // Loop untuk menampilkan semua produk
    while ($product = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $product['id'] . "</td>";
        echo "<td>" . $product['name'] . "</td>";
        echo "<td>" . $product['price'] . "</td>";
        echo "<td>" . $product['description'] . "</td>";
        echo "<td><a href='../Controller/Delete.php?id=" . $product['id'] . "' onclick='return confirm(\"Are you sure you want to delete this product?\");'>Delete</a></td>";

        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No products found.";
}
?>



  

</body>

</html>
<?php
$conn->close();
?>