<?php
session_start();
include '../includes/config.php'; // Masukkan file konfigurasi koneksi database

// Pastikan pengguna sudah login
if (!isset($_SESSION['nik'])) {
    header('Location: ../login.php'); // Arahkan ke halaman login jika belum login
    exit();
}

// Ambil NIK pengguna dari sesi
$nik = $_SESSION['nik'];

$current_page = basename($_SERVER['PHP_SELF']);

// Fungsi untuk mendapatkan semua catatan perjalanan dengan opsi pengurutan
function getCatatanPerjalanan($conn, $nik, $sort_by = null)
{
    // Set default query
    $query = "SELECT * FROM tb_catatan WHERE nik = ?";

    // Add sorting based on the selected option
    if ($sort_by == 'tanggal') {
        $query .= " ORDER BY tgl_catatan ASC"; // Urutkan berdasarkan tanggal
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nik);
    $stmt->execute();
    $result = $stmt->get_result();

    $catatanArray = []; // Array untuk menyimpan catatan perjalanan

    while ($row = $result->fetch_assoc()) {
        $catatanArray[] = $row; // Menambahkan setiap catatan ke dalam array
    }

    $stmt->close();
    return $catatanArray; // Mengembalikan array catatan perjalanan
}

// Fungsi untuk menghitung jumlah catatan pengguna
function countCatatan($conn, $nik)
{
    $query = "SELECT COUNT(*) as total FROM tb_catatan WHERE nik = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nik);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc();
    $stmt->close();
    return $count['total']; // Mengembalikan jumlah catatan
}

// Check if sorting option is set and call the function
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : null;
$catatanList = getCatatanPerjalanan($conn, $nik, $sort_by); // Memanggil fungsi untuk mendapatkan catatan perjalanan
$totalCatatan = countCatatan($conn, $nik); // Menghitung jumlah catatan

// Fungsi untuk memformat tanggal
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
    <title>Daftar Catatan Perjalanan</title>
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
    <script src="../js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <div class="container my-4">
        <div class="mb-3 no-print">
            <form method="GET" action="">
                <div class="d-flex align-items-center my-3">
                    <label for="sort_by" class="form-label fw-bold me-2">Urutkan Berdasarkan:</label>
                    <select name="sort_by" id="sort_by" class="form-select me-2">
                        <option value="">Pilih</option>
                        <option value="tanggal" <?php echo isset($_GET['sort_by']) && $_GET['sort_by'] === 'tanggal' ? 'selected' : ''; ?>>Tanggal</option>
                    </select>
                    <button type="submit" class="btn btn-utama">Urutkan</button>
                    <button onclick="window.print()" class="btn btn-print">Print</button>
                </div>
            </form>
        </div>
        <?php
        // Tampilkan pesan sukses atau error jika ada
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success" role="alert" id="alert">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger" role="alert" id="alert">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-bordered text-center">
                <thead>
                    <tr class="text-center align-middle">
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Tanggal Catatan</th>
                        <th>Jam Catatan</th>
                        <th>Lokasi Catatan</th>
                        <th>Suhu Tubuh</th>
                        <th class="no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($catatanList)): ?>
                        <?php foreach ($catatanList as $index => $catatan): ?>
                            <tr>
                                <td><?php echo $totalCatatan - (count($catatanList) - ($index + 1)); ?></td> <!-- Nomor catatan yang benar -->
                                <td><?php echo htmlspecialchars($catatan['nik']); ?></td>
                                <td><?php echo formatTanggal($catatan['tgl_catatan']); ?></td>
                                <td><?php echo htmlspecialchars($catatan['jam_catatan']); ?></td>
                                <td><?php echo htmlspecialchars($catatan['lokasi_catatan']); ?>, <?php echo htmlspecialchars($catatan['kota']); ?>, <?php echo htmlspecialchars($catatan['provinsi']); ?></td>
                                <td><?php echo htmlspecialchars($catatan['suhu_tubuh']); ?> °<?php echo htmlspecialchars($catatan['satuan_suhu']); ?></td>
                                <td>
                                    <a href="proses_hapus_catatan.php?id_catatan=<?php echo urlencode($catatan['id_catatan']); ?>" class="btn btn-danger btn-sm no-print" onclick="return confirm('Apakah Anda yakin ingin menghapus catatan ini?');">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada catatan perjalanan ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php include '../includes/footer.php'; ?>
    </div>
    <script>
        // Menghilangkan alert setelah 3 detik
        setTimeout(function() {
            const alert = document.getElementById('alert');
            if (alert) {
                alert.style.display = 'none'; // Sembunyikan alert
            }
        }, 3000); // Waktu dalam milidetik (3000ms = 3 detik)
    </script>
</body>

</html>

<?php
$conn->close();
?>