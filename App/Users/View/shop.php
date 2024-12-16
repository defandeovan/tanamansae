<?php

include '../../admin/Controller/db.php';


$sql2 = "SELECT id, slider1, slider2, slider3, right_top_bhanner, right_bot_bhanner FROM bhanner";
$result2 = $conn->query($sql2);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TANAMANSAE</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Style/style-shop.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .product-image {
            width: 100%;
        }

        .image-container {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 100%;
            /* This creates a square aspect ratio (1:1) */
            overflow: hidden;
        }

        .image-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Ensures the image covers the container without stretching */
        }

        .quantity-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-container .icon {
            cursor: pointer;
            padding: 5px;
        }

        .quantity-container .quantity {
            margin: 0 10px;
            min-width: 20px;
            text-align: center;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            right: 0;
            /* Tetapkan dropdown di sebelah kanan ikon */
            top: 70px;
            /* Jarak dari atas ikon, disesuaikan sesuai kebutuhan */
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        @media screen and (max-width: 600px) {
            .dropdown-content {
                top: 160px;
            }
        }
    </style>
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

            <input type="text" id="searchInput" placeholder="Search...">

            <button onclick="performSearch()">Search</button>
        </div>


    </header>


    <div id="bannerContainer" class="container-bhanner">
        <?php
        if ($result2->num_rows > 0) {
            echo '<div class="slider-container">'; // Container untuk slider
            while ($row = $result2->fetch_assoc()) {
                // Bersihkan path dari karakter backslash jika ada
                $clean_slider1 = str_replace('\\', '', $row['slider1']);
                $clean_slider2 = str_replace('\\', '', $row['slider2']);
                $clean_slider3 = str_replace('\\', '', $row['slider3']);
                $clean_right_top = str_replace('\\', '', $row['right_top_bhanner']);
                $clean_right_bot = str_replace('\\', '', $row['right_bot_bhanner']);

                // Buat path lengkap
                $slider1_url = '../../../Assets/img/' . htmlspecialchars($clean_slider1);
                $slider2_url = '../../../Assets/img/' . htmlspecialchars($clean_slider2);
                $slider3_url = '../../../Assets/img/' . htmlspecialchars($clean_slider3);
                $right_top_url = '../../../Assets/img/' . htmlspecialchars($clean_right_top);
                $right_bot_url = '../../../Assets/img/' . htmlspecialchars($clean_right_bot);

                echo '
            <div  class="slider">
                <div class="slide"><img src="' . $slider1_url . '" alt="Slider 1"></div>
                <div class="slide"><img src="' . $slider2_url . '" alt="Slider 2"></div>
                <div class="slide"><img src="' . $slider3_url . '" alt="Slider 3"></div>
            </div>
             <button id="but" class="prev" onclick="moveSlide(-1)">&#10094;</button>
            <button id="but" class="next" onclick="moveSlide(1)">&#10095;</button>
            </div>
            <div class="right-bhanner">
                <div>
                    <img src="' . $right_top_url . '" alt="Right Top Banner">
                </div>
                <div>
                    <img src="' . $right_bot_url . '" alt="Right Bottom Banner">
                </div>
            </div>
        ';
            }
            // echo '</div>';
        } else {
            echo '<p>Tidak ada banner yang tersedia.</p>';
        }
        ?>
    </div>


    <?php
    $search_query = isset($_GET['search']) ? $_GET['search'] : '';

    $sql = "SELECT id, name, description, price, image_path FROM products";
    if (!empty($search_query)) {
        $sql .= " WHERE name LIKE '%" . $conn->real_escape_string($search_query) . "%' OR description LIKE '%" . $conn->real_escape_string($search_query) . "%'";
    }
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<div class="product-container">'; // Container untuk semua kartu produk
        while ($row = $result->fetch_assoc()) {
            // Hapus backslash dari path
            $clean_path = str_replace('\\', '', $row['image_path']);
            // Buat path lengkap
            $image_url = '../../../Assets/img/products/' . htmlspecialchars($clean_path);

            echo '
                    <div class="product-card" onclick="goToDetail(' . $row['id'] . ')">
                        <div class="image-container">
                            <img src="' . $image_url . '" alt="' . htmlspecialchars($row['name']) . '" class="product-image">
                        </div>
                        <p>' . htmlspecialchars($row['name']) . '</p>
                        <p class="price">Rp' . number_format($row['price'], 0, ',', '.') . '</p>
                        <p>' . htmlspecialchars($row['description']) . '</p>
                        <div class="rating">
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"> 4.5</span>
                        </div>
                    </div>
                ';
        }
        echo '</div>';
    } else {
        echo '<p>Tidak ada produk yang tersedia.</p>';
    }
    ?>

    <footer>
        <div class="container">

            <div id="footer" class="footer-content">
                <p><span id="sp">&copy; TanamanSae</span>All right reserved
                </p>
                <ul class="footer-links">
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Contact Us</a></li>
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
    <script>
        

    </script>
    <script src="../Controller/scriptbhanner.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>


</body>


</html>
<?php
$conn->close();
?>