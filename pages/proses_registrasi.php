<?php
include '../includes/config.php'; // Menghubungkan ke database

session_start(); // Mulai session

// Cek apakah request adalah GET untuk memeriksa NIK
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['nik'])) {
    $nik = $_GET['nik'];

    // Fungsi untuk memeriksa keberadaan NIK
    function check_nik_exists($nik, $conn)
    {
        $query = "SELECT * FROM tb_pengguna WHERE nik = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $nik);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0; // Kembalikan true jika NIK ada
    }

    // Cek apakah NIK ada di database
    $exists = check_nik_exists($nik, $conn);

    // Mengembalikan hasil dalam format JSON
    echo json_encode(['exists' => $exists]);
    exit(); // Keluar agar tidak melanjutkan ke proses registrasi
}

// Fungsi untuk memvalidasi input
function validate_input($nik, $nama_pasien)
{
    if (!preg_match("/^\d{16}$/", $nik)) {
        return "NIK harus terdiri dari 16 digit angka.";
    }

    if (!preg_match("/^[A-Za-z ]+$/", $nama_pasien)) {
        return "Nama pasien hanya boleh terdiri dari huruf dan spasi.";
    }

    return null; // Tidak ada error
}


// Fungsi untuk mendaftar pengguna
function register_user($nik, $password, $nama_pasien, $alamat, $conn)
{
    $query = "INSERT INTO tb_pengguna (nik, password, nama_pasien, alamat) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $nik, password_hash($password, PASSWORD_DEFAULT), $nama_pasien, $alamat);
    return $stmt->execute(); // Kembalikan hasil eksekusi
}

// Fungsi untuk mengelola pesan sesi
function set_session_message($type, $message)
{
    $_SESSION[$type] = $message;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nik = $_POST['nik'];
    $password = $_POST['password'];
    $nama_pasien = $_POST['nama_pasien'];
    $alamat = $_POST['alamat'];

    // Validasi input
    $error_message = validate_input($nik, $nama_pasien);
    if ($error_message) {
        set_session_message('error', $error_message);
        header("Location: registrasi.php");
        exit();
    }


    // Mendaftar pengguna
    if (register_user($nik, $password, $nama_pasien, $alamat, $conn)) {
        set_session_message('success', "Pendaftaran berhasil!"); // Menyimpan pesan sukses
        header("Location: ../login.php"); // Arahkan ke halaman login setelah registrasi
        exit();
    } else {
        set_session_message('error', "Terjadi kesalahan saat mendaftar. Silakan coba lagi.");
        header("Location: registrasi.php");
        exit();
    }
}

// Menutup koneksi database
$conn->close();
