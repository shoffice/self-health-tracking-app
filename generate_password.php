<?php
// Password yang ingin di-hash
$password = 'admin';

// Menghasilkan hash dari password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Menampilkan hasil hash
echo "Hash Password: " . $hashed_password;
