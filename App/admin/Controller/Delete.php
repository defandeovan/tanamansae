<?php
// Include koneksi database
include("db.php");

// Cek apakah ID produk diterima melalui metode GET
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Query untuk menghapus data produk
    $delete_sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $product_id);

    // Eksekusi query untuk menghapus
    if ($stmt->execute()) {
        echo "Produk berhasil dihapus!";
        header("Location: ../View/products.php"); // Redirect ke halaman list produk setelah berhasil menghapus
        exit();
    } else {
        echo "Terjadi kesalahan saat menghapus produk: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ID produk tidak ditemukan!";
}
?>
