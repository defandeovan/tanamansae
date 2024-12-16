<?php
include 'db.php';

// Ambil data dari form login
$username = $_POST['username'];
$password = $_POST['password'];

// Cari username di database
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        // Login berhasil
        session_start();
        $_SESSION['username'] = $user['username'];
        header("Location: ../View/dashboard.php");
    } else {
        // Password salah
        echo "Password salah!";
    }
} else {
    // Username tidak ditemukan
    echo "Username tidak ditemukan!";
}
$conn->close();
?>
