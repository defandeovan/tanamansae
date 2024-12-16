<?php
// Include Midtrans library
require_once realpath(__DIR__ . '/midtrans-php-master/Midtrans.php');

// Set up Midtrans configuration
\Midtrans\Config::$serverKey = 'SB-Mid-server-1WjTs89Fi03b8hIiiZZMCYAf';
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Ambil data dari client
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['subtotal'])) {
    echo json_encode(['error' => 'Invalid data received.']);
    exit;
}

$subtotal = intval($data['subtotal']);

// Prepare transaction parameters
$params = array(
    'transaction_details' => array(
        'order_id' => 'order_' . uniqid(),
        'gross_amount' => $subtotal,
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
    echo json_encode(['snapToken' => $snapToken]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
