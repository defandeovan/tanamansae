<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <link href="https://fonts.googleapis.com/css2?family=Abhaya+Libre:wght@400;500;600;700;800&family=Actor&family=Asap:ital,wght@0,100..900;1,100..900&family=Bangers&family=Berkshire+Swash&family=Gloria+Hallelujah&family=IM+Fell+English:ital@0;1&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style-pay.css">
</head>
<body>
    <div class="container">
        
    </div>
    <nav class="navbar">
        <div class="navbar-brand">
            <a href="shop.php"><img src="img/logo.svg" alt="Logo"></a>
            <a href="shop.php"><h1 class="logo">Flora Trade Indonesia</h1></a>
        </div>
    </nav>
    <div class="Pembayaran">
        <div class="back-arrow">
            <a href="shop.php"><i data-feather="arrow-left"></i></a>
        </div>
        <h3>Pembayaran</h3>
    </div>
    <div class="container-main">
        <main>
            <div class="profile-form"></div>
            <div class="navbar-payment">
                <div class="total-payment">
                    <h3>Total</h3>
                    <?php if(isset($_GET['total'])) { $total = $_GET['total']; ?>
                    <input class="price" type="text" value="<?php echo number_format($total, 0, ',', '.'); ?>" readonly>
                    <?php } ?>
                </div>
                <div class="dana">
                    <img src="img/dana.svg" alt="DANA">
                    <?php if(isset($_GET['rekening'])) { $rekening = $_GET['rekening']; ?>
                    <input type="text" id="rekening" name="rekening" value="<?php echo htmlspecialchars($rekening); ?>" readonly>
                    <?php } ?>
                </div>
            </div>
        </main>
    </div>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
</body>
</html>
