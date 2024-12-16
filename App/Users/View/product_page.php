<?php
// Include database connection
include '../../admin/Controller/db.php';

// Check for `id` parameter in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID.");
}

$product_id = intval($_GET['id']);

// Fetch product details from the database using a prepared statement
$sql = "SELECT id, name, description, price, image_path FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <script src="https://unpkg.com/feather-icons"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Style/style-detail.css">
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-YOUR_CLIENT_KEY"></script>
</head>

<body>
    <button class="back" onclick="goBack()">&larr; Back</button>
    <div class="container">
        <div class="product-images">
            <img id="main-image" src="../../../Assets/img/products/<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <div class="thumbnail-container">
                <img src="../../../Assets/img/products/<?php echo htmlspecialchars($product['image_path']); ?>" alt="Thumbnail 1" onclick="changeImage(this)">
            </div>
        </div>
        <div class="product-details">
            <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
            <div class="container-box">
                <div class="order-box">
                    <div class="product-price">Rp. <?php echo number_format($product['price'], 2); ?></div>
                    <p class="subtotal">Subtotal: <strong id="subtotal">Rp <?php echo number_format($product['price'], 2); ?></strong></p>
                </div>
                <div class="quantity-container">
                    <button class="quantity-btn" id="decrease">−</button>
                    <input type="number" class="quantity-input" id="quantity" value="1" min="1" />
                    <button class="quantity-btn" id="increase">+</button>
                </div>
                <button class="add-to-cart-btn" id="pay-button">Pay Now</button>
            </div>
        </div>
    </div>
    <script>
        const price = <?php echo $product['price']; ?>;
        const subtotalElement = document.getElementById('subtotal');
        const quantityInput = document.getElementById('quantity');
        const decreaseBtn = document.getElementById('decrease');
        const increaseBtn = document.getElementById('increase');

        function updateSubtotal() {
            const quantity = parseInt(quantityInput.value);
            const subtotal = price * quantity;
            subtotalElement.textContent = `Rp ${subtotal.toLocaleString()}`;
        }

        decreaseBtn.addEventListener('click', () => {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                updateSubtotal();
            }
        });

        increaseBtn.addEventListener('click', () => {
            let currentValue = parseInt(quantityInput.value);
            quantityInput.value = currentValue + 1;
            updateSubtotal();
        });

        quantityInput.addEventListener('input', () => {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue < 1 || isNaN(currentValue)) {
                quantityInput.value = 1;
            }
            updateSubtotal();
        });

        document.getElementById('pay-button').addEventListener('click', function () {
            const quantity = parseInt(quantityInput.value);
            const subtotal = price * quantity;

            fetch('process_payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ subtotal: subtotal }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.snapToken) {
                    window.snap.pay(data.snapToken, {
                        onSuccess: function (result) {
                            alert("Pembayaran berhasil! Terima kasih.");
                        },
                        onPending: function (result) {
                            alert("Pembayaran sedang diproses.");
                        },
                        onError: function (result) {
                            alert("Pembayaran gagal.");
                        }
                    });
                }
            });
        });

        updateSubtotal();

        function goBack() {
            window.history.back();
        }

        function changeImage(thumbnail) {
            const mainImage = document.getElementById('main-image');
            mainImage.src = thumbnail.src;
            mainImage.alt = thumbnail.alt;
        }
    </script>
</body>
</html>
