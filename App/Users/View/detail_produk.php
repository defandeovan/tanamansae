<?php
// Include database connection
include '../../admin/Controller/db.php';

// Check for `id` parameter in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID.");
}

$product_id = intval($_GET['id']);

// Fetch product details from the database using a prepared statement
$sql = "SELECT id, name, description, price, image_path, image_path2 FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();
$stmt->close();

// Include Midtrans library and get Snap Token
require_once realpath(__DIR__ . '/midtrans-php-master/Midtrans.php');

// Set up Midtrans configuration
\Midtrans\Config::$serverKey = 'SB-Mid-server-1WjTs89Fi03b8hIiiZZMCYAf';
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Prepare transaction parameters
$params = array(
    'transaction_details' => array(
        'order_id' => 'order_' . uniqid(),
        'gross_amount' => $product['price'],
    ),
    'customer_details' => array(
        'first_name' => 'Budi',
        'last_name' => 'Pratama',
        'email' => 'budi.pra@example.com',
        'phone' => '08111222333',
    ),
);

// Get Snap Token
try {
    $snapToken = \Midtrans\Snap::getSnapToken($params);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
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

    <!-- Load Snap.js library -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-YOUR_CLIENT_KEY"></script>
</head>

<body>
    <button class="back" onclick="goBack()">&larr; Back</button>
    <div class="container">
        <div class="product-images">
            <img id="main-image" src="../../../Assets/img/products/<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <div class="thumbnail-container">
                <img src="../../../Assets/img/products/<?php echo htmlspecialchars($product['image_path']); ?>" alt="Thumbnail 1" onclick="changeImage(this)">
                <img src="../../../Assets/img/products/<?php echo htmlspecialchars($product['image_path2']); ?>" alt="Thumbnail 2" onclick="changeImage(this)">
                <!-- <img src="../../../Assets/img/products/67457ce602c42-Tanaman (32).jpg" alt="Thumbnail 3" onclick="changeImage(this)">
                <img src="../../../Assets/img/products/67457d7e18bf0-Tanaman (25).jpg" alt="Thumbnail 4" onclick="changeImage(this)"> -->
            </div>
        </div>
        <div class="product-details">
            <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
            <div class="container-box">
                <div class="order-box">
                    <div class="product-price">Rp. <?php echo number_format($product['price'], 2); ?></div>
                    <h2>Description</h2>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <div class="address-input">
                        <label for="address-line-1">Alamat Line 1</label>
                        <input type="text" id="address-line-1" placeholder="Enter your address">
                        <div class="address-i">
                            <input type="text" id="address-line-2" placeholder="Detail your Address">
                            <input type="text" id="address-line-3" placeholder="Code pos 3">
                        </div>
                    </div>
                </div>
                <div class="order-box-2">
                    <h3>Catatan</h3>
                    <textarea name="note" id="note" cols="30" rows="10"></textarea>
                    <h3>Jumlah</h3>
                    <div class="quantity-container">
                        <button class="quantity-btn" id="decrease">−</button>
                        <input type="number" class="quantity-input" id="quantity" value="1" min="1" />
                        <button class="quantity-btn" id="increase">+</button>
                    </div>
                    <p class="subtotal">Subtotal: <strong id="subtotal">Rp <?php echo number_format($product['price'], 2); ?></strong></p>
                </div>
            </div>
            <button class="add-to-cart-btn" id="pay-button">Pay Now</button>
        </div>
    </div>
    <footer>
        <div class="container-f">
            <div id="footer" class="footer-content">
                <p><span id="sp">&copy; TanamanSae</span> All right reserved</p>
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
       const price = <?php echo $product['price']; ?>;
        const subtotalElement = document.getElementById('subtotal');
        const quantityInput = document.getElementById('quantity');
        const decreaseBtn = document.getElementById('decrease');
        const increaseBtn = document.getElementById('increase');

        function updateSubtotal() {
            const quantity = parseInt(quantityInput.value);
            const subtotal = price * quantity;
            subtotalElement.textContent = `Rp ${subtotal.toLocaleString()}`;
            return subtotal;
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

        updateSubtotal();

        function goBack() {
            window.history.back();
        }

        function changeImage(thumbnail) {
            const mainImage = document.getElementById('main-image');
            mainImage.src = thumbnail.src;
            mainImage.alt = thumbnail.alt;
        }

        document.getElementById('pay-button').addEventListener('click', function () {
            const subtotal = updateSubtotal();

            fetch('process_transaction.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ subtotal: subtotal })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(error => {
                        throw new Error(error.error || 'Unknown server error');
                    });
                }
                return response.json();
            })
            .then(data => {
                window.snap.pay(data.snapToken, {
                    onSuccess: function (result) { alert('Pembayaran berhasil!'); console.log(result); },
                    onPending: function (result) { alert('Pembayaran sedang diproses.'); console.log(result); },
                    onError: function (result) { alert('Pembayaran gagal.'); console.log(result); }
                });
            })
            .catch(error => {
                alert('Error: ' + error.message);
                console.error(error);
            });
        });

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
