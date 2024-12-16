<?php

include '../../admin/Controller/db.php'; // Pastikan file ini menghubungkan ke database Anda

// Query untuk mengambil semua data produk
$sql = "SELECT id, name_plant, description_plant ,image FROM article";
$sql2 = "SELECT * FROM hero_section WHERE id = 1";
$result = $conn->query($sql);
$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {
    $hero = $result2->fetch_assoc();
} else {
    echo "Data tidak ditemukan.";
    exit;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flora Trade Indonesia</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Abhaya+Libre:wght@400;500;600;700;800&family=Actor&family=Asap:ital,wght@0,100..900;1,100..900&family=Bangers&family=Berkshire+Swash&family=Gloria+Hallelujah&family=IM+Fell+English:ital@0;1&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../Style/style.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="../../../Assets/img/logo/logo.svg" alt="">
            <h1> TanamanSae</h1>
        </div>
        <nav>
            <ul>
                <li><a id="top-bar" href="index.php"
                        class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Home</a></li>
                <li><a id="top-bar" href="shop.php"
                        class="<?= basename($_SERVER['PHP_SELF']) == 'shop.php' ? 'active' : '' ?>">Shop</a></li>
                <li><a id="top-bar" href="contact.php"
                        class="<?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>">Contact</a></li>
            </ul>
        </nav>

        <div class="search-container">
            <!-- <input type="text" id="searchInput" placeholder="Search...">
            <button onclick="performSearch()">Search</button> -->
        </div>


    </header>
    <main>
        <div class="hero-section">
            <h1><?php echo $hero['title']; ?></h1>
            <p>
                <?php
                // Gunakan nl2br agar baris baru di deskripsi tetap terlihat di HTML
                echo nl2br($hero['description']);
                ?>
            </p>
            <p><strong><?php echo $hero['tagline']; ?></strong></p>
        </div>

        <div class="container">
            <?php if ($result->num_rows > 0): ?>
                <section class="products">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <article class="product">
                            <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name_plant']) ?>">
                            <h2><?= htmlspecialchars($row['name_plant']) ?></h2>
                            <p><?= htmlspecialchars($row['description_plant']) ?></p>

                        </article>
                    <?php endwhile; ?>
                </section>
            <?php else: ?>
                <p>Tidak ada produk yang tersedia saat ini. Silakan cek kembali nanti!</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="container">

            <div id="footer" class="footer-content">
                <p><span id="sp">&copy; TanamanSae</span>All right reserved
                </p>
                <ul class="footer-links">
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="../../admin/View/dashboard.php">Admin</a></li>
                </ul>
            </div>
            <div class="social-media">
                <a id="fb" href="https://www.facebook.com">
                    <img id="sm" src="../../../Assets/img/sosmed/pngegg.png" alt="Facebook">
                    @TanamanSae
                </a>
                <a id="mp" href="#">
                    <img id="sm" src="../../../Assets/img/sosmed/gmaps.png" alt="Google Maps">
                    Malang
                </a>
                <a id="tp" href="#">
                    <img id="sm" src="../../../Assets/img/sosmed/tokped.png" alt="Tokopedia">
                    Tanaman Hias Sae
                </a>
            </div>

            <div class="footer-logo">
                <img src="../../../Assets/img/logo/logo.svg" alt="">
                <h1> Tanaman<span id="sp">Sae</span></h1>
            </div>

        </div>
    </footer>

</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const topBarLinks = document.querySelectorAll("#top-bar");

        topBarLinks.forEach(link => {
            link.addEventListener("click", function (event) {
                // Hapus class "active" dari semua link
                topBarLinks.forEach(l => l.classList.remove("active"));

                // Tambahkan class "active" ke link yang diklik
                this.classList.add("active");
            });
        });
    });
</script>


</html>
<?php
$conn->close();
?>