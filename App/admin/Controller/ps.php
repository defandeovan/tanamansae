<?php
$password = "admin"; // Ganti dengan password yang ingin Anda hash
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

echo "Password: $password<br>";
echo "Hash: $hashedPassword<br>";
?>
