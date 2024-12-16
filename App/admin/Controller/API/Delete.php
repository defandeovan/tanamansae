<?php
// Database connection
include("../db.php");

// Debug: Periksa koneksi database
if (!$conn) {
    die(json_encode(["status" => "error", "message" => "Database connection failed."]));
}

// Ambil data dari JSON
$data = json_decode(file_get_contents("php://input"), true);

// Debug: Cek apakah JSON diterima dengan benar
if ($data) {
    // Validasi data (Pastikan ID produk, artikel, dan banner ada dan merupakan angka)
    if (isset($data['product_id'], $data['article_id'], $data['banner_id']) && 
        !empty($data['product_id']) && is_numeric($data['product_id']) &&
        !empty($data['article_id']) && is_numeric($data['article_id']) &&
        !empty($data['banner_id']) && is_numeric($data['banner_id'])) {

        // Escape data untuk mencegah SQL injection
        $product_id = intval($data['product_id']); // Konversi menjadi integer untuk keamanan
        $article_id = intval($data['article_id']); // Konversi menjadi integer untuk keamanan
        $banner_id = intval($data['banner_id']);   // Konversi menjadi integer untuk keamanan

        // SQL query untuk menghapus produk, artikel, dan banner berdasarkan ID
        $sqlProduct = "DELETE FROM products WHERE id = ?";
        $sqlArticle = "DELETE FROM article WHERE id = ?";
        $sqlBanner = "DELETE FROM bhanner WHERE id = ?";

        $response = [];

        // Hapus dari tabel products
        if ($stmtProduct = $conn->prepare($sqlProduct)) {
            $stmtProduct->bind_param("i", $product_id);

            if ($stmtProduct->execute()) {
                if ($stmtProduct->affected_rows > 0) {
                    $response['products'] = ["status" => "success", "message" => "Product deleted successfully."];
                } else {
                    $response['products'] = ["status" => "error", "message" => "No product found with the given ID."];
                }
            } else {
                $response['products'] = ["status" => "error", "message" => "Failed to delete product. MySQL error: " . $stmtProduct->error];
            }

            $stmtProduct->close();
        } else {
            $response['products'] = ["status" => "error", "message" => "Failed to prepare SQL query for products. MySQL error: " . $conn->error];
        }

        // Hapus dari tabel article
        if ($stmtArticle = $conn->prepare($sqlArticle)) {
            $stmtArticle->bind_param("i", $article_id);

            if ($stmtArticle->execute()) {
                if ($stmtArticle->affected_rows > 0) {
                    $response['articles'] = ["status" => "success", "message" => "Article deleted successfully."];
                } else {
                    $response['articles'] = ["status" => "error", "message" => "No article found with the given ID."];
                }
            } else {
                $response['articles'] = ["status" => "error", "message" => "Failed to delete article. MySQL error: " . $stmtArticle->error];
            }

            $stmtArticle->close();
        } else {
            $response['articles'] = ["status" => "error", "message" => "Failed to prepare SQL query for articles. MySQL error: " . $conn->error];
        }

        // Hapus dari tabel bhanner
        if ($stmtBanner = $conn->prepare($sqlBanner)) {
            $stmtBanner->bind_param("i", $banner_id);

            if ($stmtBanner->execute()) {
                if ($stmtBanner->affected_rows > 0) {
                    $response['bhanner'] = ["status" => "success", "message" => "Banner deleted successfully."];
                } else {
                    $response['bhanner'] = ["status" => "error", "message" => "No banner found with the given ID."];
                }
            } else {
                $response['bhanner'] = ["status" => "error", "message" => "Failed to delete banner. MySQL error: " . $stmtBanner->error];
            }

            $stmtBanner->close();
        } else {
            $response['bhanner'] = ["status" => "error", "message" => "Failed to prepare SQL query for banner. MySQL error: " . $conn->error];
        }

        // Kirim respons JSON
        echo json_encode($response, JSON_PRETTY_PRINT);

    } else {
        // Jika ID tidak valid
        echo json_encode(["status" => "error", "message" => "Invalid input data. Ensure product, article, and banner IDs are provided and valid."]);
    }
} else {
    // Jika JSON tidak diterima
    echo json_encode(["status" => "error", "message" => "No JSON data received."]);
}

// Tutup koneksi database
$conn->close();
?>
