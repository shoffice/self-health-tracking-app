<?php
$servername = "localhost";
$username = "root"; // Ganti dengan username database Anda
$password = "123456789"; // Ganti dengan password database Anda
$database = "db_catatan_6371036704050001"; // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
