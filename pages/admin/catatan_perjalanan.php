<?php
session_start();
include '../../includes/config.php'; // Include database connection

// Check if user is admin
// Periksa apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['nik']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Ambil NIK dari URL
$nik = isset($_GET['nik']) ? $_GET['nik'] : '';
if (!$nik) {
    echo "NIK tidak ditemukan.";
    exit();
}

// Ambil data catatan perjalanan berdasarkan NIK
$query = "SELECT * FROM tb_catatan WHERE nik = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $nik);
$stmt->execute();
$result = $stmt->get_result();

function formatTanggal($tgl)
{
    $date = DateTime::createFromFormat('Y-m-d', $tgl); // Mengubah format tanggal dari database (Y-m-d)
    return $date ? $date->format('d-m-Y') : ''; // Mengembalikan format dd-MM-YYYY
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catatan Perjalanan</title>
    <link href="../../css/bootstrap.css" rel="stylesheet">
    <link href="../../css/custom.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container" style="max-width: 1000px;">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-3">
                    <h3 class="fw-bold">PEDULI DIRI</h3>
                    <h3 class="fw-bold mb-4 no-print"> Admin Panel</h3>
                    <img src="../../images/logo2.png" alt="Descriptive Alt Text" class="img-fluid" style="width: 80px; height: 80px;">
                </div>
                <div class="container mt-5">
                    <div class="d-flex justify-content-between align-items-center my-2">
                        <h4>Catatan Perjalanan Pengguna <br>
                            (<?php echo htmlspecialchars($nik); ?>)</h4>
                        <div class=" no-print">
                            <button onclick="window.print()" class="btn btn-print">Print</button>
                            <a href="manajemen_pengguna.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                    <div class="my-3">
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success" role="alert" id="alert"><?php echo $_SESSION['success']; ?></div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger" role="alert" id="alert"><?php echo $_SESSION['error']; ?></div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                    </div>
                    <div class="table-responsive mb-3">
                        <table class="table table-striped table-hover table-bordered text-center">
                            <thead>
                                <tr class="text-center align-middle">
                                    <th>No.</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Lokasi</th>
                                    <th>Suhu Tubuh</th>
                                    <th class="no-print">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php $no = 1; ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr class="text-center align-middle">
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo formatTanggal($row['tgl_catatan']); ?></td>
                                            <td><?php echo htmlspecialchars($row['jam_catatan']); ?></td>
                                            <td><?php echo htmlspecialchars($row['lokasi_catatan']); ?>, <?php echo htmlspecialchars($row['kota']); ?>, <?php echo htmlspecialchars($row['provinsi']); ?></td>
                                            <td><?php echo htmlspecialchars($row['suhu_tubuh']); ?> °<?php echo htmlspecialchars($row['satuan_suhu']); ?></td>
                                            <td class="no-print">
                                                <a href="edit_catatan.php?id=<?php echo urlencode($row['id_catatan']); ?>" class="btn btn-utama">Edit</a>
                                                <a href="hapus_catatan.php?id=<?php echo urlencode($row['id_catatan']); ?>&nik=<?php echo urlencode($row['nik']); ?>" class="btn btn-kedua  " onclick="return confirm('Apakah Anda yakin ingin menghapus catatan ini?');">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada catatan perjalanan ditemukan.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Hide alert after 3 seconds
            setTimeout(function() {
                const alert = document.getElementById('alert');
                if (alert) {
                    alert.style.display = 'none'; // Hide alert
                }
            }, 3000);
        </script>
        <div class="no-print">
            <?php include '../../includes/footer.php'; ?>
        </div>
    </div>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>