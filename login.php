
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Abhaya+Libre:wght@400;500;600;700;800&family=Actor&family=Asap:ital,wght@0,100..900;1,100..900&family=Bangers&family=Berkshire+Swash&family=Gloria+Hallelujah&family=IM+Fell+English:ital@0;1&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style-login.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form method="POST" action="">
            <div class="alert-failed hide" id="alert-failed">
                <i class="alert" data-feather="alert-circle"></i>
                <p>Maaf akun anda tidak ditemukan!</p>
                <div class="alert-x">
                    <i class="x" data-feather="x"></i>
                </div>
            </div>
            <div class="input-group">
                <span class="icon"><i data-feather="user"></i></span>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <span class="icon"><i data-feather="lock"></i></span>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="container-me">
                <div class="Remember-me">
                    <input name="remember" type="checkbox">
                    <p>Remember Me</p>
                </div>
                <div class="Forgot">
                    <a href="username-forgot.php"><p>Forgot Password?</p></a>
                </div>
            </div>
            <div class="button-container">
                <button name="login" type="submit">Login</button>
            </div>
            <div class="Register">
                <p>Don’t have an account? <a class="Sign-in" href="register.php">Sign in</a></p>
            </div>
        </form>
    </div>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
</body>
</html>
