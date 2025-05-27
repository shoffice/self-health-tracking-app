<?php
session_start();
include '../../includes/config.php';

if (!isset($_SESSION['nik']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik = $_POST['nik'];
    $namaLengkap = $_POST['nama_pasien'];
    $alamat = $_POST['alamat'];
    $password = $_POST['password'];

    try {
        // Update with password if provided
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE tb_pengguna SET nama_pasien = ?, alamat = ?, password = ? WHERE nik = ?");
            $stmt->bind_param("ssss", $namaLengkap, $alamat, $hashedPassword, $nik);
        } else {
            // Update without password
            $stmt = $conn->prepare("UPDATE tb_pengguna SET nama_pasien = ?, alamat = ? WHERE nik = ?");
            $stmt->bind_param("sss", $namaLengkap, $alamat, $nik);
        }

        // Execute the statement
        if ($stmt->execute()) {
            // Update session with new name
            $result = $conn->query("SELECT nama_pasien FROM tb_pengguna WHERE nik = '$nik'");
            if ($result) {
                $user = $result->fetch_assoc();
                $_SESSION['nama_pasien'] = $user['nama_pasien'];
            }
            $_SESSION['success'] = "Data berhasil diperbarui.";
        } else {
            $_SESSION['error'] = "Terjadi kesalahan: " . $stmt->error;
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
    }

    // Redirect to the management page
    header("Location: manajemen_pengguna.php");
    exit();
}

$nik = $_GET['nik'];
$stmt = $conn->prepare("SELECT * FROM tb_pengguna WHERE nik = ?");
$stmt->bind_param("s", $nik);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna</title>
    <link href="../../css/bootstrap.css" rel="stylesheet">
</head>

<body>
    <?php include '../admin/header.php'; ?>
    <div class="container mt-4">
        <h3 class="my-4">Edit Pengguna</h3>
        <form method="POST" action="">
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
            <button type="submit" class="btn btn-utama">Simpan Perubahan</button>
            <a href="manajemen_pengguna.php" class="btn btn-kedua">Batal</a>
        </form>
    </div>
</body>

</html>

<?php
$conn->close();
?>