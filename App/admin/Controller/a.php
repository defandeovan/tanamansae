<?php
include 'db.php';

// Contoh username dan hash
$username = "admin";
$hashedPassword = '$2y$10$XYK9gKsnLKtSQaC2GKo9Tu48tnO7fu2ZkRyv4egBaooYcSTttrOXm'; // Ganti dengan hash Anda

$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $hashedPassword);

if ($stmt->execute()) {
    echo "Hash berhasil disimpan!";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
