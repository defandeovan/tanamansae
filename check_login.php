<?php
session_start();

$response = array();
if (isset($_SESSION['username'])) {
    $response['logged_in'] = true;
} else {
    $response['logged_in'] = false;
}

echo json_encode($response);
?>
