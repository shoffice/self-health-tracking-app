<?php
session_start();
if (!isset($_SESSION['nik'])) {
    header("Location: ../login.php"); // Arahkan ke halaman login jika belum login
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Aplikasi Peduli Diri</title>
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
    <script src="../js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <div class="rectangle">
        <p>Selamat Datang <b><?php echo $_SESSION['nama_pasien']; ?></b> di aplikasi <b>PEDULI DIRI</b></p>
    </div>
    <div class="d-flex justify-content-end mt-3">
        <a href="perjalanan.php" class="btn btn-utama">Isi Catatan Perjalanan</a> <!-- Tombol di sisi kanan -->
    </div>
    <?php include '../includes/footer.php'; ?> <!-- Pastikan footer ada -->
    </div>
    </div>
    </div>
    </div>
</body>

</html>