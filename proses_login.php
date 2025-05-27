<?php
session_start();
include 'includes/config.php'; // Include database connection

// Fungsi untuk memverifikasi pengguna
function verify_user($nik, $password, $conn)
{
    $stmt = $conn->prepare("SELECT * FROM tb_pengguna WHERE nik = ?");
    $stmt->bind_param("s", $nik);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            return $user; // Kembalikan data pengguna jika verifikasi berhasil
        }
    }
    return null;
}

// Fungsi untuk menyimpan log
function log_user($nik, $conn)
{
    $log_stmt = $conn->prepare("INSERT INTO tb_log (nik, wkt_login) VALUES (?, NOW())");
    $log_stmt->bind_param("s", $nik);
    return $log_stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik = $_POST['nik'];
    $password = $_POST['password'];

    $user = verify_user($nik, $password, $conn);

    if ($user) {
        $_SESSION['nik'] = $user['nik'];
        $_SESSION['nama_pasien'] = $user['nama_pasien'];
        $_SESSION['is_admin'] = $user['is_admin']; // Menyimpan status admin ke dalam session

        if (!log_user($user['nik'], $conn)) {
            error_log("Error saving log: " . $conn->error);
        }

        // Arahkan pengguna berdasarkan status admin
        if ($_SESSION['is_admin'] == 1) {
            header("Location: pages/admin/admin.php"); // Arahkan ke halaman admin
        } else {
            header("Location: pages/home.php"); // Arahkan ke halaman home untuk pengguna biasa
        }
        exit();
    } else {
        $_SESSION['error'] = "NIK atau password salah.";
        header("Location: login.php");
        exit();
    }
}

// Menutup koneksi database
$conn->close();
