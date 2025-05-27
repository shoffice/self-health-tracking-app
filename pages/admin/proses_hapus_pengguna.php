<?php
session_start();
include '../../includes/config.php';

// Cek jika user adalah admin
if (!isset($_SESSION['nik']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../../login.php");
    exit();
}

// Validasi input untuk keamanan tambahan
if (isset($_GET['nik']) && !empty($_GET['nik'])) {
    $nik = $_GET['nik'];

    // Persiapan query untuk menghapus data
    $stmt = $conn->prepare("DELETE FROM tb_pengguna WHERE nik = ?");
    $stmt->bind_param("s", $nik);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Pengguna berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Terjadi kesalahan saat menghapus pengguna.";
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "NIK tidak valid.";
}

// Redirect ke halaman manajemen pengguna
header("Location: manajemen_pengguna.php");
exit();
