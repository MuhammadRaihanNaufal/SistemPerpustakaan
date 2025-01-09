<?php
session_start();

if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password_user = htmlspecialchars($_POST['password_user']);

    require 'Database.php';

    $database = new Database();

    $query = <<<QUERY
            SELECT * FROM user WHERE username = '$username' AND password_user = '$password_user' LIMIT 1
        QUERY;

    $user = $database->koneksi->query($query)->fetch();

    if (!$user) {
        echo '<script>
            alert("Nama atau Password salah!");
            window.location.href = "login.php";
        </script>';
    }

    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    echo '<script>
          alert("Berhasil login!");
          window.location.href = "index.php";
        </script>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>LOGIN</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/128/9043/9043296.png" type="image/x-icon">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body class="d-flex justify-content-center align-items-center vh-100">
    <form action="login.php" method="post" class="text-center p-4 bg-white rounded shadow-lg" style="width: 100%; max-width: 400px;">
        <h3><strong>PERPUSTAKAAN</strong></h3>
        <h5 class="mb-4"><strong>LOGIN</strong></h5>

        <!-- Input Fields -->
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Masukkan Username" required />
        </div>
        <div class="mb-3">
            <input type="password" name="password_user" class="form-control" placeholder="Masukkan Password" required />
        </div>

        <!-- Login Button -->
        <button type="submit" class="btn btn-primary w-100">Login</button>

        <!-- Register Link -->
        <div class="mt-3 text-end">
            <p class="d-inline">Belum punya akun?</p>
            <a href="register.php" class="text-warning fw-bold">Register!</a>
        </div>
    </form>
</body>

</html>