<?php
require 'koneksi.php';

if (isset($_POST['next'])) {
    $username = $_POST['username'];

    // Prepare statement
    $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
    
    // Check if prepare() was successful
    if ($stmt === false) {
        die("Error preparing the statement: " . $koneksi->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        session_start();
        $_SESSION['username'] = $username;
        header("Location: reset_password.php");
        exit;
    } else {
        echo "<script>alert('Maaf akun anda tidak ditemukan!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Abhaya+Libre:wght@400;500;600;700;800&family=Actor&family=Asap:ital,wght@0,100..900;1,100..900&family=Bangers&family=Berkshire+Swash&family=Gloria+Hallelujah&family=IM+Fell+English:ital@0;1&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style-usr.css">
    <title>Trouble Logging in?</title>
</head>

<body>
    <div class="container">
        <div class="lock">
            <i id="lock" data-feather="lock"></i>
        </div>
        <h1>Trouble Logging in?</h1>
        <form method="POST" action="">
            <p>Enter your username and we'll send you a link to get back into your account.</p>
            <div class="input-group">
                <span class="icon"><i data-feather="user"></i></span>
                <input type="text" name="username" placeholder="Masukkan username anda" required>
            </div>
            <div class="button-container">
                <button name="next">NEXT</button>
            </div>
            <div class="or">
                <div class="g-left">
                    <hr>
                </div>
                <div>
                    <p>OR</p>
                </div>
                <div class="g-right">
                    <hr>
                </div>
            </div>
            <div class="create">
                <a href="register.php"><h3>Create Account</h3></a>
            </div>
        </form>
    </div>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
</body>
</html>
