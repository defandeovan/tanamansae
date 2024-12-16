<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TANAMANSAE</title>
    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Abhaya+Libre:wght@400;500;600;700;800&family=Actor&family=Asap:ital,wght@0,100..900;1,100..900&family=Bangers&family=Berkshire+Swash&family=Gloria+Hallelujah&family=IM+Fell+English:ital@0;1&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../Style/style-contact.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="../../../Assets/img/logo/logo.svg" alt="">
            <h1> TanamanSae</h1>
        </div>
        <nav>
            <ul>
                <li><a class="top-bar <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>"
                        href="index.php">Home</a></li>
                <li><a class="top-bar <?= basename($_SERVER['PHP_SELF']) == 'shop.php' ? 'active' : '' ?>"
                        href="shop.php">Shop</a></li>
                <li><a class="top-bar <?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>"
                        href="contact.php">Contact</a></li>
            </ul>
        </nav>

        <div class="search-container">

        </div>
    </header>


    <div class="container">
        <div class="form-container">
            <div class="left-container">
                <div class="left-inner-container">
                    <h2>Let's Chat</h2>
                    <p>Whether you have a question, want to start a project or simply want to connect.</p>
                    <br>
                    <p>Feel free to send me a message in the contact form</p>
                </div>
            </div>
            <div class="right-container">
                <div class="right-inner-container">
                    <form action="https://formspree.io/f/xwpkqvab" method="POST">
                        <h2 class="lg-view">Contact</h2>
                        <h2 class="sm-view">Let's Chat</h2>
                        <p>* Required</p>
                        <div class="social-container">
                            <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                            <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                       
                            <input type="email" name="email" placeholder="email">
                    
                       
                        <textarea rows="4"  name="message" placeholder="Message"></textarea>
                        <button>Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <div class="container-f">

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
<!-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        const topBarLinks = document.querySelectorAll(".top-bar");

        topBarLinks.forEach(link => {
            link.addEventListener("click", function () {
                // Hapus semua class "active"
                topBarLinks.forEach(l => l.classList.remove("active"));

                // Tambahkan class "active" ke link yang diklik
                this.classList.add("active");
            });
        });
    });
</script> -->

</html>