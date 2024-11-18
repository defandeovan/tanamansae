
<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Mengambil data user
$stmt = $koneksi->prepare("
    SELECT u.username, u.email, ud.nama, ud.phone, ud.gender, ud.profil, ud.provinsi, ud.kota, ud.jalan, ud.kode_pos, ud.rekening, s.store_name 
    FROM users u 
    LEFT JOIN userdetails ud ON u.user_id = ud.user_id 
    LEFT JOIN stores s ON u.user_id = s.user_id 
    WHERE u.username = ?
");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Handle form submission
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $store_name = $_POST['store-name'];
    $gender = $_POST['gender'];
    $provinsi = $_POST['provinsi'];
    $kota = $_POST['kota'];
    $jalan = $_POST['jalan'];
    $kode_pos = $_POST['kode_pos'];
    $rekening = $_POST['rekening'];

    if ($_FILES['pprofil']['error'] == 0) {
        $profil = $_FILES['pprofil']['name'];
        $dir = "img/profil/";
        $tmp_file = $_FILES['pprofil']['tmp_name'];

        if (move_uploaded_file($tmp_file, $dir . $profil)) {
            $stmt = $koneksi->prepare("UPDATE userdetails SET nama = ?, gender = ?, profil = ?, provinsi = ?, kota = ?, jalan = ?, kode_pos = ?, rekening = ? WHERE user_id = (SELECT user_id FROM users WHERE username = ?)");
            $stmt->bind_param("sssssssss", $nama, $gender, $profil, $provinsi, $kota, $jalan, $kode_pos, $rekening, $username);
        } else {
            echo "Terjadi kesalahan saat mengunggah file.";
            exit;
        }
    } else {
        $stmt = $koneksi->prepare("UPDATE userdetails SET nama = ?, gender = ?, provinsi = ?, kota = ?, jalan = ?, kode_pos = ?, rekening = ? WHERE user_id = (SELECT user_id FROM users WHERE username = ?)");
        $stmt->bind_param("ssssssss", $nama, $gender, $provinsi, $kota, $jalan, $kode_pos, $rekening, $username);
    }

    if ($stmt->execute()) {
        // Check if a store entry already exists for the user
        $stmt = $koneksi->prepare("SELECT store_id FROM stores WHERE user_id = (SELECT user_id FROM users WHERE username = ?)");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update the existing store entry
            $stmt = $koneksi->prepare("UPDATE stores SET store_name = ? WHERE user_id = (SELECT user_id FROM users WHERE username = ?)");
        } else {
            // Insert a new store entry
            $stmt = $koneksi->prepare("INSERT INTO stores (store_name, user_id) VALUES (?, (SELECT user_id FROM users WHERE username = ?))");
        }
        $stmt->bind_param("ss", $store_name, $username);
        $stmt->execute();

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Terjadi kesalahan saat memperbarui profil.";
    }

    $stmt->close();
    $koneksi->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flora Trade Indonesia</title>
    <link rel="stylesheet" href="styles22.css">
    <style>
    
    </style>
</head>
<body>
<header>
    <div class="logo">
        <a href="index.html"><img src="img/logo.svg" alt="Logo"></a>
        <h1>FLORA TRADE INDONESIA</h1>
    </div>
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search...">
        <button onclick="performSearch()">Search</button>
    </div>
    <div class="menu">
        <div class="cart-icon"><a href="basket.php" class="icon-shop"><i data-feather="shopping-cart"></i></a></div>
        <div class="notification-icon">
            <span class="icon-notif" onclick="toggleDropdown('dropdown-m', event)"><i data-feather="bell"></i></span>
        </div>
        <div class="menu-icon">
            <span class="icon-menu" onclick="toggleDropdown('dropdown', event)"><i data-feather="align-right"></i></span>
        </div>
    </div>
    <div class="container">
        <div class="top-bar-right">
            <div class="profile-menu" style="display: inline-block;">
                <div id="dropdown" class="dropdown-content">
                    <a href="profil.php">Akun Saya</a>
                    <a href="input_tanaman.php">Tambah Product Saya</a>
                    <a href="logout.php">Log Out</a>
                </div>
            </div>
            <div class="notif-menu" style="display: inline-block;">
                <div id="dropdown-m" class="dropdown-content-m">
                    <a href="#">Notifikasi 1</a>
                    <a href="#">Notifikasi 2</a>
                    <a href="#">Notifikasi 3</a>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="profile-container">
    <img class="pp" src="img/profil/<?php echo !empty($row['profil']) ? htmlspecialchars($row['profil']) : 'default.jpg'; ?>" alt="">
    <input class="from-control" type="file" name="pprofil" id="pprofil">
    <p>Ukuran gambar: maks. 1 MB <br> Format gambar: .JPEG, .PNG</p>
</div>
<div class="container-main">
    <form method="post" action="" enctype="multipart/form-data">
        <main>
            <div class="profile-form">
                <h3>Profil saya</h3>
                <p>Kelola informasi profil Anda untuk mengontrol, <br> melindungi dan mengamankan akun</p>
                <div class="input">
                    <div>
                        <label for="username">Username</label>
                    </div>
                    <div>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" readonly>
                    </div>
                </div>
                <div class="input">
                    <div>
                        <label for="nama">Nama</label>
                    </div>
                    <div>
                        <input type="text" id="nama" name="nama" value="<?php echo isset($row['nama']) ? htmlspecialchars($row['nama']) : ''; ?>">
                    </div>
                </div>
                <div class="input">
                    <div>
                        <label for="email">Email</label>
                    </div>
                    <div>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" readonly>
                    </div>
                </div>
                <div class="input">
                    <div>
                        <label for="phone">Nomor Telepon</label>
                    </div>
                    <div>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" readonly>
                    </div>
                </div>
                <div class="input">
                    <div>
                        <label for="store-name">Nama Toko</label>
                    </div>
                    <div>
                        <input type="text" id="store-name" name="store-name" value="<?php echo isset($row['nama_toko']) ? htmlspecialchars($row['nama_toko']) : ''; ?>">
                    </div>
                </div>
                <div>
                    <label>Jenis Kelamin</label>
                </div>
                <div class="gender-input">
                    <label><input type="radio" name="gender" value="male" <?php echo isset($row['jenis_kelamin']) && $row['jenis_kelamin'] == 'male' ? 'checked' : ''; ?>> Laki-laki</label>
                    <label><input type="radio" name="gender" value="female" <?php echo isset($row['jenis_kelamin']) && $row['jenis_kelamin'] == 'female' ? 'checked' : ''; ?>> Perempuan</label>
                    <label><input type="radio" name="gender" value="other" <?php echo isset($row['jenis_kelamin']) && $row['jenis_kelamin'] == 'other' ? 'checked' : ''; ?>> Lainnya</label>
                </div>
                <div class="input">
                    <div>
                        <label class="alamat" for="alamat">Alamat</label>
                    </div>
                    <div>
                        <input type="text" id="provinsi" name="provinsi" value="<?php echo isset($row['provinsi']) ? htmlspecialchars($row['provinsi']) : ''; ?>" placeholder="Provinsi">
                    </div>
                    <div>
                        <input type="text" id="kota" name="kota" value="<?php echo isset($row['kota']) ? htmlspecialchars($row['kota']) : ''; ?>" placeholder="Kota">
                    </div>
                    <div>
                        <input type="text" id="jalan" name="jalan" value="<?php echo isset($row['jalan']) ? htmlspecialchars($row['jalan']) : ''; ?>" placeholder="Jalan">
                    </div>
                    <div>
                        <input type="text" id="kode_pos" name="kode_pos" value="<?php echo isset($row['kode_pos']) ? htmlspecialchars($row['kode_pos']) : ''; ?>" placeholder="Kode Pos">
                    </div>
                </div>
                <button class="simpan" type="submit" name="simpan">SIMPAN</button>
            </div>
        </main>
    </form>
</div>

<script src="https://unpkg.com/feather-icons"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        feather.replace();
    });

    function toggleDropdown(id, event) {
        event.stopPropagation();
        var dropdown = document.getElementById(id);
        if (dropdown.style.display === "block") {
            dropdown.style.display = "none";
        } else {
            closeAllDropdowns();
            dropdown.style.display = "block";
        }
    }

    function closeAllDropdowns() {
        var dropdowns = document.querySelectorAll('.dropdown-content, .dropdown-content-m');
        dropdowns.forEach(function(dropdown) {
            dropdown.style.display = "none";
        });
    }

    window.onclick = function(event) {
        if (!event.target.closest('.profile-menu') && !event.target.closest('.menu-icon') && !event.target.closest('.notification-icon')) {
            closeAllDropdowns();
        }
    }

    function performSearch() {
        var searchInput = document.getElementById('searchInput');
        var searchQuery = searchInput.value.trim();
        if (searchQuery !== '') {
            window.location.href = 'shop.php?search=' + encodeURIComponent(searchQuery);
        }
    }
</script>
</body>
</html>
