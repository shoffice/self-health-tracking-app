<?php
session_start();
include '../includes/config.php'; // Masukkan file konfigurasi koneksi database

// Pastikan pengguna sudah login
if (!isset($_SESSION['nik'])) {
    header('Location: ../login.php'); // Arahkan ke halaman login jika belum login
    exit();
}
$current_page = basename($_SERVER['PHP_SELF']);
// Ambil NIK pengguna dari sesi
$nik = $_SESSION['nik'];

// Ambil data pengguna dari database berdasarkan NIK
$query = $conn->prepare("SELECT * FROM tb_pengguna WHERE nik = ?");
$query->bind_param("s", $nik);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Simpan data pengguna ke dalam array $user
} else {
    echo "Data pengguna tidak ditemukan.";
    exit();
}

$query->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Pengguna</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/custom.css">
    <script src="../js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <form action="proses_edit_data_pengguna.php" method="POST">
        <!-- NIK -->
        <div class="mb-3">
            <label for="nik" class="form-label">NIK</label>
            <input type="text" class="form-control" id="nik" name="nik" value="<?php echo htmlspecialchars($user['nik']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="mb-3">
            <label for="nama_pasien" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" value="<?php echo htmlspecialchars($user['nama_pasien']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo htmlspecialchars($user['alamat']); ?></textarea>
        </div>
        <div class="my-2">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success" role="alert" id="alert"><?php echo $_SESSION['success']; ?></div>
                <?php unset($_SESSION['success']);
                ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert" id="alert"><?php echo $_SESSION['error']; ?></div>
                <?php unset($_SESSION['error']);
                ?>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-utama">Simpan Perubahan</button>
    </form>
    <script>
        // Hide alert after 3 seconds
        setTimeout(function() {
            const alert = document.getElementById('alert');
            if (alert) {
                alert.style.display = 'none'; // Hide alert
            }
        }, 3000); // Time in milliseconds (3000ms = 3 seconds)
    </script>
</body>

</html>