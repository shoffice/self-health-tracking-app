<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Aplikasi Peduli Diri</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="container" style="max-width: 460px;">
            <div class="card">
                <div class="text-center my-2">
                    <h3 class="card-title fw-bold my-4">Selamat Datang<br></h3>
                    <img src="images/logo2.png" alt="Logo" class="img-fluid logo-img my-3">
                </div>
                <div class="card-body mx-2">
                    <form action="proses_login.php" method="POST" class="mb-4">
                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" class="form-control" id="nik" name="nik" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="text-center my-4">
                            <?php
                            session_start();
                            if (isset($_SESSION['success'])) {
                                echo '<div class="alert alert-success" role="alert" id="alert">' . $_SESSION['success'] . '</div>';
                                unset($_SESSION['success']);
                            }

                            if (isset($_SESSION['error'])) {
                                echo '<div class="alert alert-danger" role="alert" id="alert">' . $_SESSION['error'] . '</div>';
                                unset($_SESSION['error']);
                            }
                            ?>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-utama">Login</button>
                            <a href="pages/registrasi.php" class="btn btn-kedua">Daftar</a>
                        </div>
                    </form>
                    <!-- Footer moved below the buttons -->
                    <?php include 'includes/footer.php'; ?>
                </div>
            </div>
        </div>
        <script>
            setTimeout(function() {
                const alert = document.getElementById('alert');
                if (alert) {
                    alert.style.display = 'none';
                }
            }, 2500);
        </script>
    </div>
</body>

</html>