<?php
include '../includes/config.php'; // Masukkan file konfigurasi koneksi database

session_start(); // Mulai session

// Fungsi untuk mengatur pesan sesi
function set_session_message($type, $message)
{
    $_SESSION[$type] = $message;
}

// Fungsi untuk validasi tanggal
function validate_date($date)
{
    $currentDate = date('Y-m-d'); // Ambil tanggal hari ini
    return $date <= $currentDate; // Kembalikan true jika tanggal valid
}


function save_travel_record($conn, $nik, $tgl_catatan, $jam_catatan, $lokasi_catatan, $provinsi, $kota, $suhu_tubuh, $satuan_suhu)
{
    // Gantilah koma menjadi titik agar sesuai dengan format desimal
    $suhu_tubuh = str_replace(',', '.', $suhu_tubuh);

    // Tambahkan kolom 'satuan_suhu' ke dalam query
    $stmt = $conn->prepare("INSERT INTO tb_catatan (nik, tgl_catatan, jam_catatan, lokasi_catatan, provinsi, kota, suhu_tubuh, satuan_suhu) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("ssssssss", $nik, $tgl_catatan, $jam_catatan, $lokasi_catatan, $provinsi, $kota, $suhu_tubuh, $satuan_suhu);
        return $stmt->execute() ? true : $stmt->error; // Kembalikan true atau error
    } else {
        return $conn->error; // Kembalikan error jika gagal mempersiapkan pernyataan
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil nilai dari form
    $nik = $_POST['nik'];
    $tgl_catatan = $_POST['tgl_catatan'];
    $jam_catatan = $_POST['jam_catatan'];
    $provinsi = $_POST['provinsi'];
    $kota = $_POST['kota'];
    $satuan_suhu = $_POST['satuan_suhu'];

    // Gabungkan provinsi, kota, dan lokasi spesifik
    $lokasi_catatan = $_POST['lokasi_catatan']; // Misalnya: "Jawa Tengah, Solo, UNS"

    $suhu_tubuh = $_POST['suhu_tubuh'];

    // Validasi tanggal
    if (!validate_date($tgl_catatan)) {
        set_session_message('error', "Tanggal tidak boleh lebih dari hari ini.");
        header("Location: perjalanan.php");
        exit();
    }

    // Simpan catatan perjalanan
    $saveResult = save_travel_record($conn, $nik, $tgl_catatan, $jam_catatan, $lokasi_catatan, $provinsi, $kota, $suhu_tubuh, $satuan_suhu);

    if ($saveResult === true) {
        set_session_message('success', "Catatan berhasil disimpan!"); // Simpan pesan keberhasilan
    } else {
        set_session_message('error', "Gagal menyimpan catatan: $saveResult"); // Simpan pesan error
    }

    header("Location: perjalanan.php"); // Arahkan kembali ke halaman catatan perjalanan
    exit();
}

$conn->close(); // Tutup koneksi
