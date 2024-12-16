<?php
// Database connection
include("../db.php");

// Debug: Periksa koneksi database
if (!$conn) {
    die(json_encode(["status" => "error", "message" => "Database connection failed."]));
}

// Ambil data dari JSON
$data = json_decode(file_get_contents("php://input"), true);

// Cek apakah data diterima
if ($data) {
    // Validasi data untuk `products`, `article`, dan `bhanner`
    if (
        isset($data['product_id'], $data['product_name'], $data['product_description'], $data['product_price'], $data['product_image_path'],
              $data['article_id'], $data['article_name_plant'], $data['article_description_plant'], $data['article_image'],
              $data['bhanner_id'], $data['slider1'], $data['slider2'], $data['slider3'], $data['right_top_bhanner'], $data['right_bot_bhanner']) &&
        !empty($data['product_id']) && !empty($data['product_name']) && !empty($data['product_description']) && 
        is_numeric($data['product_price']) && !empty($data['product_image_path']) &&
        !empty($data['article_id']) && !empty($data['article_name_plant']) && !empty($data['article_description_plant']) && !empty($data['article_image']) &&
        !empty($data['bhanner_id']) && !empty($data['slider1']) && !empty($data['slider2']) && !empty($data['slider3']) &&
        !empty($data['right_top_bhanner']) && !empty($data['right_bot_bhanner'])
    ) {
        // Data untuk tabel `products`
        $product_id = $data['product_id'];
        $product_name = mysqli_real_escape_string($conn, $data['product_name']);
        $product_description = mysqli_real_escape_string($conn, $data['product_description']);
        $product_price = $data['product_price'];
        $product_image_path = mysqli_real_escape_string($conn, $data['product_image_path']);
        
        // Data untuk tabel `article`
        $article_id = $data['article_id'];
        $article_name_plant = mysqli_real_escape_string($conn, $data['article_name_plant']);
        $article_description_plant = mysqli_real_escape_string($conn, $data['article_description_plant']);
        $article_image = mysqli_real_escape_string($conn, $data['article_image']);

        // Data untuk tabel `bhanner`
        $bhanner_id = $data['bhanner_id'];
        $slider1 = mysqli_real_escape_string($conn, $data['slider1']);
        $slider2 = mysqli_real_escape_string($conn, $data['slider2']);
        $slider3 = mysqli_real_escape_string($conn, $data['slider3']);
        $right_top_bhanner = mysqli_real_escape_string($conn, $data['right_top_bhanner']);
        $right_bot_bhanner = mysqli_real_escape_string($conn, $data['right_bot_bhanner']);

        // Array response
        $response = [];

        // SQL query untuk update produk berdasarkan ID
        $sqlProduct = "UPDATE products SET name = ?, description = ?, price = ?, image_path = ?, created_at = NOW() WHERE id = ?";
        
        if ($stmtProduct = $conn->prepare($sqlProduct)) {
            $stmtProduct->bind_param("ssdsi", $product_name, $product_description, $product_price, $product_image_path, $product_id);
            if ($stmtProduct->execute()) {
                $response['products'] = ["status" => "success", "message" => "Product updated successfully."];
            } else {
                $response['products'] = ["status" => "error", "message" => "Failed to update product. MySQL error: " . $stmtProduct->error];
            }
            $stmtProduct->close();
        } else {
            $response['products'] = ["status" => "error", "message" => "Failed to prepare product update query. MySQL error: " . $conn->error];
        }

        // SQL query untuk update artikel berdasarkan ID
        $sqlArticle = "UPDATE article SET name_plant = ?, description_plant = ?, image = ? WHERE id = ?";
        
        if ($stmtArticle = $conn->prepare($sqlArticle)) {
            $stmtArticle->bind_param("sssi", $article_name_plant, $article_description_plant, $article_image, $article_id);
            if ($stmtArticle->execute()) {
                $response['articles'] = ["status" => "success", "message" => "Article updated successfully."];
            } else {
                $response['articles'] = ["status" => "error", "message" => "Failed to update article. MySQL error: " . $stmtArticle->error];
            }
            $stmtArticle->close();
        } else {
            $response['articles'] = ["status" => "error", "message" => "Failed to prepare article update query. MySQL error: " . $conn->error];
        }

        // SQL query untuk update bhanner berdasarkan ID
        $sqlBhanner = "UPDATE bhanner SET slider1 = ?, slider2 = ?, slider3 = ?, right_top_bhanner = ?, right_bot_bhanner = ? WHERE id = ?";
        
        if ($stmtBhanner = $conn->prepare($sqlBhanner)) {
            $stmtBhanner->bind_param("sssssi", $slider1, $slider2, $slider3, $right_top_bhanner, $right_bot_bhanner, $bhanner_id);
            if ($stmtBhanner->execute()) {
                $response['bhanners'] = ["status" => "success", "message" => "Bhanner updated successfully."];
            } else {
                $response['bhanners'] = ["status" => "error", "message" => "Failed to update bhanner. MySQL error: " . $stmtBhanner->error];
            }
            $stmtBhanner->close();
        } else {
            $response['bhanners'] = ["status" => "error", "message" => "Failed to prepare bhanner update query. MySQL error: " . $conn->error];
        }

        // Kirim respons JSON
        echo json_encode($response, JSON_PRETTY_PRINT);
    } else {
        // Jika data tidak valid
        echo json_encode(["status" => "error", "message" => "Invalid input data. Please provide all required fields."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No JSON data received."]);
}

// Tutup koneksi database
$conn->close();

?>
