<?php
// Database connection
include("../Controller/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi data input
    if (
        isset($_POST['name'], $_POST['description'], $_POST['price'], $_FILES['image'], $_FILES['image2']) &&
        !empty(trim($_POST['name'])) &&
        !empty(trim($_POST['description'])) &&
        is_numeric($_POST['price']) &&
        $_POST['price'] > 0 // Validasi harga
    ) {
        // Ambil data dari input
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = floatval($_POST['price']);

        // File upload handling
        $images = [
            'image' => $_FILES['image'],
            'image2' => $_FILES['image2'],
            
        ];

        $uploadDir = __DIR__ . "../../../../Assets/img/products/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Buat direktori jika belum ada
        }

        $filePaths = [];
        $isUploadSuccessful = true;

        foreach ($images as $key => $image) {
            if (!empty($image['tmp_name'])) {
                $fileName = uniqid('img_', true) . '-' . basename($image['name']);
                $filePath = $uploadDir . $fileName;

                // Validasi tipe file
                $fileType = mime_content_type($image['tmp_name']);
                if (strpos($fileType, 'image') !== false) {
                    if (move_uploaded_file($image['tmp_name'], $filePath)) {
                        $filePaths[$key] = $fileName; // Simpan nama file
                    } else {
                        $isUploadSuccessful = false;
                        echo json_encode(["status" => "error", "message" => "Failed to upload file: $fileName"]);
                        break;
                    }
                } else {
                    $isUploadSuccessful = false;
                    echo json_encode(["status" => "error", "message" => "Invalid file type for $fileName"]);
                    break;
                }
            } else {
                $filePaths[$key] = null; // Jika file tidak ada
            }
        }

        if ($isUploadSuccessful) {
            // SQL query untuk menyisipkan data
            $sql = "INSERT INTO products (name, description, price, image_path, image_path2, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $conn->prepare($sql);

            // Bind parameter
            $stmt->bind_param(
                "ssdss",
                $name,
                $description,
                $price,
                $filePaths['image'],
                $filePaths['image2']
             
            );

            // Eksekusi query
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Product added successfully."]);
                header("Location: ../View/products.php"); 
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to add product to database: " . $stmt->error]);
            }

            $stmt->close();
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid input data. Please check the fields and try again."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

// Tutup koneksi database
$conn->close();
?>
