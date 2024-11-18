<?php
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_produk = $_POST['id_produk'];
    $quantity = $_POST['quantity'];

    // Prepare and bind
    $stmt = $koneksi->prepare("INSERT INTO purchases (id_produk, quantity) VALUES (?, ?)");
    $stmt->bind_param("ii", $id_produk, $quantity);

    if ($stmt->execute()) {
        // Ambil detail produk, termasuk nomor rekening
        $result = $koneksi->query("SELECT harga, rekening FROM plants WHERE id = $id_produk");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $total = $row['harga'] * $quantity;
            $rekening = $row['rekening'];

            // Redirect ke halaman pembayaran dengan total harga dan nomor rekening
            header("Location: payment.php?total=$total&rekening=$rekening");
            exit();
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $koneksi->close();
}
?>
