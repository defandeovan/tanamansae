<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tanaman - Flora Trade Indonesia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abhaya+Libre:wght@400;500;600;700;800&family=Actor&family=Asap:ital,wght@0,100..900;1,100..900&family=Bangers&family=Berkshire+Swash&family=Gloria+Hallelujah&family=IM+Fell+English:ital@0;1&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style-inputan.css">
</head>
<body>
    <div class="container">
        <form action="input_tanaman.php" method="POST" enctype="multipart/form-data">
            <div class="input">
                <label for="nama">Nama Tanaman</label>
                <input type="text" id="nama" name="nama" required>
            </div>
            <div class="input">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" required></textarea>
            </div>
            <div class="input">
                <label for="harga">Harga</label>
                <input type="number" id="harga" name="harga" required>
            </div>
            <div class="input">
                <label for="rekening">Rekening</label>
                <input type="text" id="rekening" name="rekening" required>
            </div>
            <div class="input">
                <label for="gambar">Gambar</label>
                <input type="file" id="gambar" name="gambar" required>
            </div>
            <button type="submit" name="submit">Tambah Tanaman</button>
        </form>
    </div>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();

        function toggleDropdown(id, event) {
            event.stopPropagation();
            var dropdown = document.getElementById(id);
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }

        window.onclick = function(event) {
            if (!event.target.closest('.profile-menu') && !event.target.closest('.icon-menu')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === "block") {
                        openDropdown.style.display = "none";
                    }
                }
            }
            if (!event.target.closest('.notif-menu') && !event.target.closest('.icon-notif')) {
                var dropdowns = document.getElementsByClassName("dropdown-content-m");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === "block") {
                        openDropdown.style.display = "none";
                    }
                }
            }
        }
    </script>
</body>
</html>

<!-- <?php
include "koneksi.php";

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $rekening = $_POST['rekening'];

    $gambar = $_FILES['gambar']['name'];
    $tmp_file = $_FILES['gambar']['tmp_name'];
    $dir = "img/tanaman/";

    if (move_uploaded_file($tmp_file, $dir . $gambar)) {
        $stmt = $koneksi->prepare("INSERT INTO plants (nama, deskripsi, harga, gambar, rekening) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $nama, $deskripsi, $harga, $gambar, $rekening);

        if ($stmt->execute()) {
            header("Location: shop.php");
            echo "Tanaman berhasil ditambahkan.";
        } else {
            echo "Terjadi kesalahan saat menambahkan tanaman.";
        }

        $stmt->close();
    } else {
        echo "Terjadi kesalahan saat mengunggah gambar.";
    }

    $koneksi->close();
}
?> -->
