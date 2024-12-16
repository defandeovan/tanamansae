<?php
session_start();
include("../Controller/db.php");

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Class ProductController untuk mengelola produk
class ProductController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllProducts()
    {
        $sql = "SELECT id, name FROM products";
        return $this->conn->query($sql);
    }

    public function getProductById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateProduct($id, $name, $price, $description)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
            $stmt->bind_param("sdsi", $name, $price, $description, $id);
            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }
            return "Produk berhasil diperbarui!";
        } catch (Exception $e) {
            error_log("Database Error: " . $e->getMessage());
            return "Terjadi kesalahan saat memperbarui produk.";
        }
    }
}

// Inisialisasi controller
$controller = new ProductController($conn);
$product_id = null;
$product = null;
$message = "";

// Jika ada parameter ID, ambil data produk
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $product = $controller->getProductById($product_id);

    if (!$product) {
        $message = "Produk dengan ID $product_id tidak ditemukan!";
    }
}

// Proses update produk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $product_id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);

    // Validasi input
    if (!empty($name) && is_numeric($price) && !empty($description)) {
        $result = $controller->updateProduct($product_id, $name, floatval($price), $description);
        $message = $result;

        if ($result === "Produk berhasil diperbarui!") {
            header("Location: products.php");
            exit();
        }
    } else {
        $message = "Harap isi semua data dengan benar!";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Edit Product</h1>

    <?php if ($message): ?>
        <div class="alert alert-info">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if ($product): ?>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="products.php" class="btn btn-secondary">Cancel</a>
        </form>
    <?php else: ?>
        <p>Product not found.</p>
        <a href="products.php" class="btn btn-secondary">Back to Products</a>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>
