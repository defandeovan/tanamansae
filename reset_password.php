<?php
session_start();
require 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST['new-password'];
    $confirmPassword = $_POST['confirm-password'];
    $username = $_SESSION['username'];

    // Validate the input
    if (empty($newPassword) || empty($confirmPassword)) {
        $error = "All fields are required.";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the database
        $sql = "UPDATE users SET password = ? WHERE username = ?";
        if ($stmt = $koneksi->prepare($sql)) {
            $stmt->bind_param("ss", $hashedPassword, $username);
            if ($stmt->execute()) {
                $success = "Password has been reset successfully.";
                header("Location: login.php");
            } else {
                $error = "Error updating password. Please try again.";
            }
            $stmt->close();
        } else {
            $error = "Database error. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <link rel="stylesheet" href="style-forgot.css">
    <link href="https://fonts.googleapis.com/css2?family=Abhaya+Libre:wght@400;500;600;700;800&family=Actor&family=Asap:ital,wght@0,100..900;1,100..900&family=Bangers&family=Berkshire+Swash&family=Gloria+Hallelujah&family=IM+Fell+English:ital@0;1&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"> 
</head>
<body>
    <div class="reset-password-container">
        <h1>Reset Your Password</h1>
        <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
        <?php if (isset($success)) { echo "<p style='color:green;'>$success</p>"; } ?>
        <form action="reset_password.php" method="POST">
            <div class="form-group">
                <p>Enter New Password</p>
                <input type="password" name="new-password" required>
                <i id="icon" class="Logo" data-feather="eye-off"></i>
            </div>
            <div class="form-group">
                <p>Confirm New Password</p>
                <input type="password" name="confirm-password" required>
                <i id="iconS" class="Mata" data-feather="eye-off"></i>
            </div>
            <button type="submit" class="Reset-pass">Reset Password</button>
        </form>
        <script src="https://unpkg.com/feather-icons"></script>
        <script>
            feather.replace();
            
            </script>
    </div>
</body>
</html>
