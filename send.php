<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $to = "defandeovan@gmail.com"; 
    $subject = "Pesan Baru dari Formulir Kontak";
    $headers = "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $emailContent = "Nama: " . $name . "\n";
    $emailContent .= "Email: " . $email . "\n\n";
    $emailContent .= "Pesan:\n" . $message;

    if (mail($to, $subject, $emailContent, $headers)) {
        echo "Pesan berhasil terkirim!";
    } else {
        echo "Terjadi kesalahan, pesan tidak berhasil terkirim.";
    }
} else {
    echo "Akses tidak sah.";
}
?>
