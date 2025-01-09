<?php
session_start();

if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = htmlspecialchars($_POST['username']);
    $alamat_user = htmlspecialchars($_POST['alamat_user']);
    $no_telepon = htmlspecialchars($_POST['no_telepon']);
    $password_user = htmlspecialchars($_POST['password_user']);
    $role = htmlspecialchars($_POST['role']); // Ambil nilai role

    require 'Database.php';

    $database = new Database();

    if ($database->koneksi->query("SELECT * FROM user WHERE username = '$username' LIMIT 1")->fetch()) {
        echo '<script>
          alert("Nama sudah terdaftar!");
          window.location.href = "register.php";
        </script>';
        exit;
    }

    $query = <<<QUERY
        INSERT INTO user (username, alamat_user, no_telepon, password_user, role)
        VALUES ('$username', '$alamat_user', '$no_telepon', '$password_user', '$role')
    QUERY;

    $database->koneksi->exec($query);

    $_SESSION['id_user'] = $database->koneksi->lastInsertId();
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role; // Simpan role ke session

    echo '<script>
          alert("Berhasil register!");
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
    <title>REGISTER</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/128/9043/9043296.png" type="image/x-icon">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <div class="text-center text-dark mt-5">
        <h3><strong>PERPUSTAKAAN</strong></h3>
        <h5><strong>REGISTER</strong></h5>
    </div>

    <!-- Form Register -->
    <form action="register.php" method="post" class="col-md-6 col-lg-4 mx-auto mt-5 p-4 bg-white rounded shadow-lg">
        <!-- Input Fields -->
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Nama" required />
        </div>
        <div class="mb-3">
            <input type="text" name="alamat_user" class="form-control" placeholder="Alamat" required />
        </div>
        <div class="mb-3">
            <input type="number" name="no_telepon" class="form-control" placeholder="No Telepon" required />
        </div>
        <div class="mb-3">
            <input type="password" name="password_user" class="form-control" placeholder="Password" required />
        </div>

        <!-- Role Select -->
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-control" required>
                <option value="Member">Member</option>
                <option value="Admin">Admin</option>
            </select>
        </div>

        <!-- Register Button -->
        <button type="submit" class="btn btn-primary w-100">Register</button>

        <!-- Link to Login -->
        <div class="mt-3 text-end">
            <p class="d-inline">Sudah punya akun?</p>
            <a href="login.php" class="text-warning fw-bold">Login!</a>
        </div>
    </form>

    <script src="../bootstrap/js/bootstrap.bundle.js"></script>
</body>

</html>