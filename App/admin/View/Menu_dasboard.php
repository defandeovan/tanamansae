<?php
// Koneksi ke database
include("../Controller/db.php");

// Ambil data hero_section dari database
$query = "SELECT * FROM hero_section WHERE id = 1"; // Anda dapat mengubah ID sesuai kebutuhan
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $hero = $result->fetch_assoc();
} else {
    echo "Data tidak ditemukan.";
    exit;
}

// Update data ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $tagline = $_POST['tagline'];

    // Query update
    $updateQuery = "UPDATE hero_section SET title = ?, description = ?, tagline = ? WHERE id = 1";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sss", $title, $description, $tagline);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='edit_hero_section.php';</script>";
    } else {
        echo "Gagal memperbarui data: " . $conn->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hero Section</title>
</head>
<body>
    <h1>Edit Hero Section</h1>
    <form method="POST" action="">
        <label for="title">Judul:</label><br>
        <input type="text" id="title" name="title" value="<?php echo $hero['title']; ?>" required><br><br>

        <label for="description">Deskripsi:</label><br>
        <textarea id="description" name="description" rows="10" cols="50" required><?php echo $hero['description']; ?></textarea><br><br>

        <label for="tagline">Tagline:</label><br>
        <input type="text" id="tagline" name="tagline" value="<?php echo $hero['tagline']; ?>" required><br><br>

        <button type="submit">Simpan Perubahan</button>
    </form>
</body>
</html>
