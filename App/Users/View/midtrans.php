<?php
require_once realpath(__DIR__ . '/midtrans-php-master/Midtrans.php');

// Atur konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-1WjTs89Fi03b8hIiiZZMCYAf';
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Parameter transaksi
$params = array(
    'transaction_details' => array(
        'order_id' => rand(),
        'gross_amount' => 10000,
    ),
    'customer_details' => array(
        'first_name' => 'Budi',
        'last_name' => 'Pratama',
        'email' => 'budi.pra@example.com',
        'phone' => '08111222333',
    ),
);

// Mendapatkan Snap Token
try {
    $snapToken = \Midtrans\Snap::getSnapToken($params);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Ganti dengan Client Key Anda -->
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="SB-Mid-client-YOUR_CLIENT_KEY"></script>
</head>

<body>
    <button id="pay-button">Pay!</button>

    <div id="snap-container"></div>

    <script type="text/javascript">
        // Token Snap dari PHP
        var snapToken = '<?php echo $snapToken; ?>';

        // Trigger embed Snap ketika tombol ditekan
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            window.snap.embed(snapToken, {
                embedId: 'snap-container'
            });
        });
    </script>
</body>

</html>
