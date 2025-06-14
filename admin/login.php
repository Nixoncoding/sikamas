<?php
session_start();

// Kalau sudah login, langsung arahkan ke dashboard
if (isset($_SESSION['sikamas'])) {
    echo "<script>window.location.href = 'index.php?page=dashboard';</script>";
    exit;
}

include_once('../function.php'); // file koneksi ke DB

// Jika tombol login ditekan
if (isset($_POST['submit'])) {
    $uss = trim(htmlspecialchars($_POST['username']));
    $pas = $_POST["password"];

    $cekuser = $conn->query("SELECT * FROM user WHERE username = '$uss'");

    if (mysqli_num_rows($cekuser) === 1) {
        $row = mysqli_fetch_assoc($cekuser);

        // Verifikasi password
        if (password_verify($pas, $row['password'])) {
            // Set session setelah berhasil login
            $_SESSION['username'] = $row['username'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['role_id'] = $row['role_id'];
            $_SESSION['sikamas'] = true;
            $_SESSION['pesan'] = 'Selamat datang ' . $_SESSION['fullname'];

            // Redirect ke halaman sebelumnya jika ada
            if (isset($_GET['rdr'])) {
                $rdr = $_GET['rdr'];
                echo "<script>window.location.href = '" . urldecode($rdr) . "';</script>";
                exit;
            } else {
                echo "<script>window.location.href = 'index.php?page=dashboard';</script>";
                exit;
            }
        }
    }

    // Jika gagal login
    $_SESSION['pesan'] = 'Username / Password salah';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="icon" href="<?= base_url('assets/img/SIMKARTA_logo.png'); ?>" type="image/x-icon">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #1d3c6d, #2f5585); /* Gradient from blue to deeper blue */
            position: relative;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            flex-direction: column; /* Align items vertically */
            overflow: hidden;
        }

        /* Decorative shapes at the corners and sides */
        .shape-top-left {
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 0;
            border-left: 200px solid transparent;
            border-bottom: 200px solid rgba(255, 255, 255, 0.1);
            z-index: 0;
        }

        .shape-top-right {
            position: absolute;
            top: 0;
            right: 0;
            width: 0;
            height: 0;
            border-right: 200px solid transparent;
            border-bottom: 200px solid rgba(255, 255, 255, 0.1);
            z-index: 0;
        }

        .shape-bottom-left {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 0;
            border-left: 200px solid transparent;
            border-top: 200px solid rgba(255, 255, 255, 0.1);
            z-index: 0;
        }

        .shape-bottom-right {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 0;
            height: 0;
            border-right: 200px solid transparent;
            border-top: 200px solid rgba(255, 255, 255, 0.1);
            z-index: 0;
        }

        .body-form {
            background-color: rgba(255, 255, 255, 0.85); /* Slightly more opaque background for readability */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
            box-sizing: border-box;
            z-index: 1;
            margin-bottom: 20px; /* Add space between form and chart */
        }

        h3 {
            font-size: 24px;
            color: #333;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            color: #555;
        }

        .form-control {
            border-radius: 5px;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .form-check-label {
            font-size: 14px;
            color: #777;
        }

        .btn {
            background-color: #007bff;
            color: white;
            font-size: 16px;
            padding: 10px;
            border-radius: 5px;
            border: none;
            width: 100%;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Optional particle effect in the background (empty for now) */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        /* Chart container */
        .chart-container {
            position: relative;
            width: 90%;
            max-width: 600px;
            height: 400px;
            z-index: 2;
        }
    </style>
</head>

<body>
    <div class="particles">
        <!-- Placeholder for any particle animation in the background -->
    </div>

    <!-- Decorative shapes -->
    <div class="shape-top-left"></div>
    <div class="shape-top-right"></div>
    <div class="shape-bottom-left"></div>
    <div class="shape-bottom-right"></div>

    <form class="body-form" method="post">
        <h3 class="text-center mb-4">SIMKARTA Login</h3>

        <?php if (!empty($_SESSION['pesan'])): ?>
            <div id="pesan" class="alert alert-warning"><?= $_SESSION['pesan']; ?></div>
        <?php $_SESSION['pesan'] = ''; endif; ?>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control" required autofocus>
        </div>
        <div class="form-group mb-2">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" required id="password">
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="showPass">
            <label class="form-check-label" for="showPass">Tampilkan Password</label>
        </div>
        <button type="submit" name="submit" class="btn btn-primary btn-block">Login</button>
    </form>
    

    <div class="wave"></div>

    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
    <script>
        $(document).ready(function () {
            setTimeout(() => $("#pesan").fadeOut(), 5000);
            $('#showPass').on('change', function () {
                const pass = $('#password');
                pass.attr('type', pass.attr('type') === 'password' ? 'text' : 'password');
            });
        });
    </script>
</body>
</html>
