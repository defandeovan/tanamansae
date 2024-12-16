<?php
include("../db.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Inisialisasi variabel respons
$response = [];
$response2 = [];
$response3 = [];

if (isset($_GET['id'])) {
    // Jika 'id' ada, ambil nilai ID dari URL
    $id = intval($_GET['id']); // Pastikan ID adalah integer untuk keamanan

    // Query untuk mengambil data produk berdasarkan ID
    $sql = "SELECT * FROM products WHERE id = ?";
    $sql2 = "SELECT * FROM article WHERE id = ?";
    $sql3 = "SELECT * FROM bhanner WHERE id = ?"; // Query untuk mengambil data banner berdasarkan ID

    $stmt = $conn->prepare($sql);
    $stmt2 = $conn->prepare($sql2);
    $stmt3 = $conn->prepare($sql3); // Menyiapkan statement untuk tabel banner

    if ($stmt && $stmt2 && $stmt3) {
        $stmt->bind_param("i", $id);
        $stmt2->bind_param("i", $id);
        $stmt3->bind_param("i", $id); // Bind ID ke statement banner

        if ($stmt->execute() && $stmt2->execute() && $stmt3->execute()) {
            // Eksekusi berhasil, ambil hasilnya
            $result = $stmt->get_result();
            $result2 = $stmt2->get_result();
            $result3 = $stmt3->get_result(); // Ambil hasil query banner

            // Cek apakah ada data pada hasil query
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $response[] = $row;
                }
            }

            if ($result2 && $result2->num_rows > 0) {
                while ($row2 = $result2->fetch_assoc()) {
                    $response2[] = $row2;
                }
            }

            if ($result3 && $result3->num_rows > 0) {
                while ($row3 = $result3->fetch_assoc()) {
                    $response3[] = $row3; // Menambahkan hasil dari tabel banner
                }
            }

            // Menutup statement setelah eksekusi berhasil
            $stmt->close();
            $stmt2->close();
            $stmt3->close();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Query execution failed',
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to prepare SQL query',
        ]);
    }
} else {
    // Jika tidak ada 'id', ambil semua data produk, artikel, dan banner
    $sql = "SELECT * FROM products";
    $sql2 = "SELECT * FROM article";
    $sql3 = "SELECT * FROM bhanner"; // Query untuk mengambil semua data banner

    $result = $conn->query($sql);
    $result2 = $conn->query($sql2);
    $result3 = $conn->query($sql3); // Ambil semua data banner

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
    }

    if ($result2 && $result2->num_rows > 0) {
        while ($row2 = $result2->fetch_assoc()) {
            $response2[] = $row2;
        }
    }

    if ($result3 && $result3->num_rows > 0) {
        while ($row3 = $result3->fetch_assoc()) {
            $response3[] = $row3; // Menambahkan semua data banner
        }
    }
}

// Output data dalam format JSON
header('Content-Type: application/json');

if (!empty($response) || !empty($response2) || !empty($response3)) {
    echo json_encode([
        'products' => $response,
        'articles' => $response2,
        'banners' => $response3 // Menambahkan data banner ke dalam respons
    ], JSON_PRETTY_PRINT);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Tidak ada data ditemukan',
    ], JSON_PRETTY_PRINT);
}

// Menutup koneksi database
$conn->close();
?>
