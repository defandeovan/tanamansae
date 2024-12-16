<?php
// process_transaction.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['subtotal'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Subtotal is missing or invalid']);
        exit;
    }

    $subtotal = intval($input['subtotal']);

    if ($subtotal <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Subtotal must be a positive number']);
        exit;
    }

    require_once realpath(__DIR__ . '/midtrans-php-master/Midtrans.php');

    \Midtrans\Config::$serverKey = 'SB-Mid-server-1WjTs89Fi03b8hIiiZZMCYAf';
    \Midtrans\Config::$isProduction = false;
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

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

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        echo json_encode(['snapToken' => $snapToken]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
