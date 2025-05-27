<?php
// Mulai session
session_start();

// Arahkan pengguna ke halaman login.php
header("Location: login.php");
exit();
