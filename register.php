<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Abhaya+Libre:wght@400;500;600;700;800&family=Actor&family=Asap:ital,wght@0,100..900;1,100..900&family=Bangers&family=Berkshire+Swash&family=Gloria+Hallelujah&family=IM+Fell+English:ital@0;1&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style-register.css">
    <title>Register</title>
</head>
<body>
   <div class="container">
    <h1>Create Account</h1>
    <form id="register-form" action="" method="POST">
        <div class="input-group">
            <span class="icon"><i data-feather="user"></i></span>
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-group">
            <span class="icon"><i data-feather="mail"></i></span>
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-group">
            <span class="icon"><i data-feather="phone"></i></span>
            <input type="tel" id="phone" name="phone" placeholder="Phone Number" pattern="[0-9]{10,15}" required>
        </div>
        <div class="input-group">
            <span class="icon"><i data-feather="lock"></i></span>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="input-group">
            <span class="icon"><i data-feather="lock"></i></span>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        </div>
        <div class="tc">
            <input type="checkbox" required>
            <label for="">I agree to <a href="">the Terms and Conditions</a></label>
        </div>
        <div class="button-container">
            <button type="submit" name="register">Register</button>
        </div>
        <div class="already">
            <p>Already have an account? <a href="login.php">Log in</a></p>
        </div>
    </form>
   </div>

    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
    <script>
        document.getElementById('register-form').addEventListener('submit', function(event) {
            const phoneInput = document.getElementById('phone');
            const phonePattern = /^[0-9]{10,15}$/;

            if (!phonePattern.test(phoneInput.value)) {
                event.preventDefault();
                alert("Phone number is not valid!");
            }
        });
    </script>
</body>
</html>

<?php
include "koneksi.php";

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password == $confirm_password) {
        // Cek apakah username sudah ada
        $stmt = $koneksi->prepare("SELECT * FROM Users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Username sudah digunakan, silakan gunakan yang lain')</script>";
        } else {
            // Cek apakah email sudah ada
            $stmt = $koneksi->prepare("SELECT * FROM Users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<script>alert('Email sudah digunakan, silakan gunakan yang lain')</script>";
            } else {
                // Cek apakah phone sudah ada
                $stmt = $koneksi->prepare("SELECT * FROM UserDetails WHERE phone = ?");
                $stmt->bind_param("s", $phone);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo "<script>alert('Nomor sudah digunakan, silakan gunakan yang lain')</script>";
                } else {
                    // Jika semua validasi berhasil, lakukan pendaftaran
                    $pass = password_hash($password, PASSWORD_DEFAULT);

                    // Mulai transaksi
                    $koneksi->begin_transaction();
                    try {
                        // Masukkan data ke tabel Users
                        $stmt = $koneksi->prepare("INSERT INTO Users (username, email, password) VALUES (?, ?, ?)");
                        $stmt->bind_param("sss", $username, $email, $pass);
                        $stmt->execute();

                        // Dapatkan ID user yang baru saja dimasukkan
                        $user_id = $stmt->insert_id;

                        // Masukkan data ke tabel UserDetails
                        $stmt = $koneksi->prepare("INSERT INTO UserDetails (user_id, phone) VALUES (?, ?)");
                        $stmt->bind_param("is", $user_id, $phone);
                        $stmt->execute();

                        // Commit transaksi
                        $koneksi->commit();

                        header("Location: login.php");
                    } catch (Exception $e) {
                        // Rollback transaksi jika terjadi kesalahan
                        $koneksi->rollback();
                        echo "Registrasi gagal: " . $e->getMessage();
                    }
                }
            }
        }
        $stmt->close();
    } else {
        echo "<script>alert('Password dan Konfirmasi Password tidak sesuai!')</script>";
    }
}
?>
