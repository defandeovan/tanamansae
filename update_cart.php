<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['action'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];

    if (isset($_SESSION['cart'][$id])) {
        if ($action == 'increase') {
            $_SESSION['cart'][$id]['quantity']++;
        } elseif ($action == 'decrease' && $_SESSION['cart'][$id]['quantity'] > 1) {
            $_SESSION['cart'][$id]['quantity']--;
        }
    }
}
?>
