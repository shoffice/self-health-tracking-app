<?php
session_start();
include '../includes/config.php'; // Masukkan file konfigurasi koneksi database

// Fungsi untuk mengatur pesan sesi
function set_session_message($type, $message)
{
    $_SESSION[$type] = $message;
}

// Pastikan pengguna sudah login
if (!isset($_SESSION['nik'])) {
    header('Location: login.php'); // Arahkan ke halaman login jika belum login
    exit();
}

// Cek apakah ada permintaan penghapusan
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_catatan'])) {
    $id_catatan = $_GET['id_catatan'];

    // Validasi ID catatan
    if (!empty($id_catatan)) {
        // Siapkan pernyataan untuk menghapus catatan dari tabel catatan
        $stmt = $conn->prepare("DELETE FROM tb_catatan WHERE id_catatan = ?");

        if ($stmt) {
            $stmt->bind_param("s", $id_catatan);

            if ($stmt->execute()) {
                // Set session message
                set_session_message('success', "Catatan berhasil dihapus!");
            } else {
                // Set session message for error
                set_session_message('error', "Terjadi kesalahan: " . $stmt->error);
            }

            $stmt->close();
        } else {
            set_session_message('error', "Gagal mempersiapkan pernyataan: " . $conn->error);
        }
    } else {
        set_session_message('error', "ID catatan tidak ditemukan.");
    }
} else {
    set_session_message('error', "Metode permintaan tidak valid.");
}

$conn->close();

// Redirect to the home page
header('Location: catatan_perjalanan.php'); // Ganti dengan URL yang sesuai untuk mengarahkan kembali
exit();
