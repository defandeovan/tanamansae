<?php
session_start();
include("../Controller/db.php");
include("../Controller/Edit.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
$controller = new ProductController($conn);

$product_id = null;
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $product_id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);

    if (!empty($name) && is_numeric($price) && $price > 0 && !empty($description)) {
        $isUpdated = $controller->updateProduct($product_id, $name, $price, $description);

        if ($isUpdated) {
            $_SESSION['success_message'] = "Produk berhasil diperbarui!";
            header("Location: products.php");
            exit();
        } else {
            $message = "Gagal memperbarui produk. Silakan coba lagi.";
        }
    } else {
        $message = "Harap isi semua data dengan benar! Pastikan harga adalah angka positif.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flora Trade Indonesia</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../Style/style.css">
</head>

<body>
    <div class="sidebar">
        <div class="profile">
            <img src="../../../../../Assets/img/logo/logo.svg" alt="Profile Picture">
            <h3>TanamanSae</h3>
        </div>
        <ul class="menu">
            <li><a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
            <li><a href="products.php" class="<?= basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : '' ?>">Products</a></li>
        </ul>
    </div>

    <div class="content">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success_message']; ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            <div class="alert alert-danger">
                <?= $message; ?>
            </div>
        <?php endif; ?>

        <h2>Product List</h2>
        <?php if ($result->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $product['id']; ?></td>
                            <td><?= $product['name']; ?></td>
                            <td><?= $product['price']; ?></td>
                            <td><?= $product['description']; ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" onclick='populateEditForm(<?= json_encode($product); ?>)'>Edit</button>
                                |
                                <a href="../Controller/Delete.php?id=<?= $product['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>

        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editForm" method="POST" action="">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit-id" name="id">
                            <div class="mb-3">
                                <label for="edit-name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="edit-name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-price" class="form-label">Price</label>
                                <input type="number" class="form-control" id="edit-price" name="price" required step="0.01">
                            </div>
                            <div class="mb-3">
                                <label for="edit-description" class="form-label">Description</label>
                                <textarea class="form-control" id="edit-description" name="description" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Save Changes</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function populateEditForm(product) {
            if (product) {
                document.getElementById('edit-id').value = product.id || '';
                document.getElementById('edit-name').value = product.name || '';
                document.getElementById('edit-price').value = product.price || '';
                document.getElementById('edit-description').value = product.description || '';
            } else {
                alert("Invalid product data!");
            }
        }
    </script>
</body>

</html>
<?php $conn->close(); ?>
