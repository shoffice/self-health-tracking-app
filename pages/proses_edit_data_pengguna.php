<?php
session_start();
include '../includes/config.php';

// Function to set session messages
function set_session_message($type, $message)
{
    $_SESSION[$type] = $message;
}

// Ensure user is logged in
if (!isset($_SESSION['nik'])) {
    header('Location: ../login.php');
    exit();
}

// Process form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik = $_POST['nik'];
    $namaLengkap = $_POST['nama_pasien'];
    $alamat = $_POST['alamat'];
    $password = $_POST['password'];

    // Prepare the SQL statement
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE tb_pengguna SET nama_pasien = ?, alamat = ?, password = ? WHERE nik = ?");
        $stmt->bind_param("ssss", $namaLengkap, $alamat, $hashedPassword, $nik);
    } else {
        $stmt = $conn->prepare("UPDATE tb_pengguna SET nama_pasien = ?, alamat = ? WHERE nik = ?");
        $stmt->bind_param("sss", $namaLengkap, $alamat, $nik);
    }

    // Execute and check for errors
    if ($stmt->execute()) {
        // Ambil nama_pasien yang baru dari database
        $result = $conn->query("SELECT nama_pasien FROM tb_pengguna WHERE nik = '$nik'");
        if ($result) {
            $user = $result->fetch_assoc();
            $_SESSION['nama_pasien'] = $user['nama_pasien']; // Update session with new name
        }
        set_session_message('success', "Data berhasil diperbarui."); // Store success message
    } else {
        set_session_message('error', "Terjadi kesalahan: " . $stmt->error); // Store error message
    }

    // Clean up
    $stmt->close();
    $conn->close();

    // Redirect to the same page to display messages
    header('Location: data_pengguna.php');
    exit();
}
