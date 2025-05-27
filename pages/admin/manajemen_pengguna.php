<?php
session_start();
include '../../includes/config.php'; // Include database connection

$current_page = basename($_SERVER['PHP_SELF']);

// Check if user is logged in as admin
if (!isset($_SESSION['nik']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Fetch user data from the database
function get_user_data($conn)
{
    $query = "SELECT * FROM tb_pengguna WHERE is_admin = 0"; // Fetch non-admin users
    return $conn->query($query);
}

// Fetch user data and statistics
$user_data = get_user_data($conn);
$total_users = ($user_data) ? $user_data->num_rows : 0; // Total users
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna</title>
    <link href="../../css/bootstrap.css" rel="stylesheet">
    <link href="../../css/custom.css" rel="stylesheet">
    <script src="../js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php include '../admin/header.php'; ?>
    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>Manajemen Pengguna</h4>
            <div class=" no-print">
                <button onclick="window.print()" class="btn btn-print">Print</button>
            </div>
        </div>
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
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-bordered text-center">
                <thead>
                    <tr class="text-center align-middle">
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th class="no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total_users > 0): ?>
                        <?php
                        $no = 1; // Initialize serial number
                        while ($user = $user_data->fetch_assoc()): ?>
                            <tr class="text-center align-middle">
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($user['nik']); ?></td>
                                <td><?php echo htmlspecialchars($user['nama_pasien']); ?></td>
                                <td class="no-print">
                                    <a href="proses_edit_pengguna.php?nik=<?php echo urlencode($user['nik']); ?>" class="btn btn-utama">Edit</a>
                                    <a href="proses_hapus_pengguna.php?nik=<?php echo urlencode($user['nik']); ?>" class="btn btn-kedua" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">Hapus</a>
                                    <br><a href="catatan_perjalanan.php?nik=<?php echo urlencode($user['nik']); ?>" class="btn btn-informasi">Catatan Perjalanan</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data pengguna yang ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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
        }, 3000); // Time in milliseconds (3000ms = 3 seconds)
    </script>
    <div class="no-print">
        <?php include '../../includes/footer.php'; ?>
    </div>
</body>

</html>

<?php
$conn->close();
?>