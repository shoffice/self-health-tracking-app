<?php
session_start();
include '../../includes/config.php';

// Pastikan pengguna sudah login dan adalah admin
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

// Ambil ID catatan dari URL
$id_catatan = isset($_GET['id']) ? intval($_GET['id']) : 0; // Pastikan id_catatan adalah integer
if ($id_catatan <= 0) {
    echo "ID catatan tidak valid.";
    exit();
}

// Query untuk menghapus catatan
$query = "DELETE FROM tb_catatan WHERE id_catatan = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id_catatan);

if ($stmt->execute()) {
    $_SESSION['success'] = "Catatan perjalanan berhasil dihapus.";
} else {
    $_SESSION['error'] = "Terjadi kesalahan saat menghapus catatan perjalanan.";
}

$stmt->close();
$conn->close();

// Redirect kembali ke halaman catatan perjalanan
header("Location: catatan_perjalanan.php?nik=" . urlencode($nik));
exit();
