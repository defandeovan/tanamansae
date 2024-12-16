<?php
// Database connection
include("../db.php");

// Debug: Periksa koneksi database
if (!$conn) {
    die(json_encode(["status" => "error", "message" => "Database connection failed."]));
}

// Ambil data dari JSON
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    // Validasi data untuk `products`, `article`, dan `bhanner`
    if (
        isset($data['name'], $data['description'], $data['price'], $data['image_path'], $data['name_plant'], $data['description_plant'], $data['image'],
              $data['slider1'], $data['slider2'], $data['slider3'], $data['right_top_bhanner'], $data['right_bot_bhanner']) &&
        !empty($data['name']) && !empty($data['description']) &&
        is_numeric($data['price']) && !empty($data['image_path']) &&
        !empty($data['name_plant']) && !empty($data['image']) && !empty($data['description_plant']) &&
        !empty($data['slider1']) && !empty($data['slider2']) && !empty($data['slider3']) &&
        !empty($data['right_top_bhanner']) && !empty($data['right_bot_bhanner'])
    ) {
        // Data untuk tabel `products`
        $name = $data['name'];
        $description = $data['description'];
        $price = $data['price'];
        $image_path = $data['image_path'];

        // Data untuk tabel `article`
        $name_plant = $data['name_plant'];
        $description_plant = $data['description_plant'];
        $image = $data['image'];

        // Data untuk tabel `bhanner`
        $slider1 = $data['slider1'];
        $slider2 = $data['slider2'];
        $slider3 = $data['slider3'];
        $right_top_bhanner = $data['right_top_bhanner'];
        $right_bot_bhanner = $data['right_bot_bhanner'];

        $response = [];

        // SQL query untuk `products`
        $sqlProduct = "INSERT INTO products (name, description, price, image_path, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmtProduct = $conn->prepare($sqlProduct);

        // Periksa apakah prepare() berhasil
        if ($stmtProduct === false) {
            die(json_encode(["status" => "error", "message" => "Failed to prepare SQL query for products. MySQL error: " . $conn->error]));
        }

        // Bind parameter
        $stmtProduct->bind_param("ssds", $name, $description, $price, $image_path);

        // Eksekusi query untuk `products`
        if ($stmtProduct->execute()) {
            $response['products'] = ["status" => "success", "message" => "Product added successfully."];
        } else {
            $response['products'] = ["status" => "error", "message" => "Failed to add product. MySQL error: " . $stmtProduct->error];
        }
        $stmtProduct->close();

        // SQL query untuk `article`
        $sqlArticle = "INSERT INTO article (name_plant, description_plant, image) VALUES (?, ?, ?)";
        $stmtArticle = $conn->prepare($sqlArticle);

        // Periksa apakah prepare() berhasil
        if ($stmtArticle === false) {
            die(json_encode(["status" => "error", "message" => "Failed to prepare SQL query for articles. MySQL error: " . $conn->error]));
        }

        // Bind parameter
        $stmtArticle->bind_param("sss", $name_plant, $description_plant, $image);

        // Eksekusi query untuk `article`
        if ($stmtArticle->execute()) {
            $response['articles'] = ["status" => "success", "message" => "Article added successfully."];
        } else {
            $response['articles'] = ["status" => "error", "message" => "Failed to add article. MySQL error: " . $stmtArticle->error];
        }
        $stmtArticle->close();

        // SQL query untuk `bhanner`
        $sqlBhanner = "INSERT INTO bhanner (slider1, slider2, slider3, right_top_bhanner, right_bot_bhanner) VALUES (?, ?, ?, ?, ?)";
        $stmtBhanner = $conn->prepare($sqlBhanner);

        // Periksa apakah prepare() berhasil
        if ($stmtBhanner === false) {
            die(json_encode(["status" => "error", "message" => "Failed to prepare SQL query for bhanners. MySQL error: " . $conn->error]));
        }

        // Bind parameter untuk tabel `bhanner`
        $stmtBhanner->bind_param("sssss", $slider1, $slider2, $slider3, $right_top_bhanner, $right_bot_bhanner);

        // Eksekusi query untuk `bhanner`
        if ($stmtBhanner->execute()) {
            $response['bhanners'] = ["status" => "success", "message" => "Bhanner added successfully."];
        } else {
            $response['bhanners'] = ["status" => "error", "message" => "Failed to add bhanner. MySQL error: " . $stmtBhanner->error];
        }
        $stmtBhanner->close();

        // Kirim respons JSON
        echo json_encode($response, JSON_PRETTY_PRINT);

    } else {
        // Jika data tidak valid
        echo json_encode(["status" => "error", "message" => "Invalid input data. Please provide all required fields."]);
    }
} else {
    // Jika JSON tidak diterima
    echo json_encode(["status" => "error", "message" => "No JSON data received."]);
}

// Tutup koneksi database
$conn->close();
?>
