<?php
session_start();

// Handle the checkout logic when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout'])) {
    // Assuming you need to process the checkout here
    // Retrieve the necessary data from the form or session
    // Perform any necessary processing
    // Redirect to the payment page
    header("Location: payment.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Saya</title>
    <link rel="stylesheet" href="style-basket.css">
    <link href="https://fonts.googleapis.com/css2?family=Abhaya+Libre:wght@400;500;600;700;800&family=Actor&family=Asap:ital,wght@0,100..900;1,100..900&family=Bangers&family=Berkshire+Swash&family=Gloria+Hallelujah&family=IM+Fell+English:ital@0;1&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet"> 
</head>
<body>
    <div class="container">
        <header>
            <a href="shop.php"><button class="back-button">&larr;</button></a>
            <h1>Keranjang Saya</h1>
            <button class="edit-button">Ubah</button>
        </header>
        
        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <div class="cart-item">
                    <div class="item-header">
                        <input type="checkbox">
                        <span class="store-name">AD_Flore</span>
                        <!-- Wrap the button in a form element -->
                        <form method="post">
                            <button type="submit" name="checkout" class="checkout-button">Checkout</button>
                        </form>
                    </div>
                    <div class="item-body">
                        <div class="item-image">
                            <img src="img/tanaman/<?php echo $item['gambar']; ?>" alt="<?php echo $item['nama']; ?>">
                        </div>
                        <div class="item-details">
                            <p><?php echo $item['nama']; ?></p>
                            <div class="item-price">
                                <span class="discounted-price">Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></span>
                            </div>
                            <div class="item-quantity">
                                <button onclick="updateQuantity(<?php echo $item['id']; ?>, 'decrease')">-</button>
                                <input type="number" value="<?php echo $item['quantity']; ?>" readonly>
                                <button onclick="updateQuantity(<?php echo $item['id']; ?>, 'increase')">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Keranjang Anda kosong.</p>
        <?php endif; ?>

        <div class="footer">
            <input type="checkbox"> Semua
            <div class="total" id="total-price">
                Total: Rp <?php echo calculateTotalPrice(); ?>
            </div>
        </div>
    </div>

    <script>
        function updateQuantity(id, action) {
            // Implement AJAX to update the quantity of the item in the session
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_cart.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    location.reload();
                }
            };
            xhr.send('id=' + id + '&action=' + action);
        }

        // Function to calculate the total price dynamically
        function calculateTotalPrice() {
            var totalPrice = 0;
            <?php foreach ($_SESSION['cart'] as $item): ?>
                totalPrice += <?php echo $item['harga'] * $item['quantity']; ?>;
            <?php endforeach; ?>
            return totalPrice.toLocaleString('id-ID');
        }
    </script>
</body>
</html>
<?php

// Function to calculate the total price of items in the cart
function calculateTotalPrice() {
    $totalPrice = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $totalPrice += $item['harga'] * $item['quantity'];
        }
    }
    return number_format($totalPrice, 0, ',', '.');
}
?>
