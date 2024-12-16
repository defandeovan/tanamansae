<?php
include("db.php");

$sql = "SELECT id, name FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode(["status" => "success", "products" => $products]);
} else {
    echo json_encode(["status" => "error", "message" => "No products found."]);
}

$conn->close();
?>
