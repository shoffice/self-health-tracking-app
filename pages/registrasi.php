<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Aplikasi Peduli Diri</title>
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="container" style="max-width: 460px;">
            <div class="card">
                <div class="text-center my-2">
                    <h3 class="card-title fw-bold my-4">Buat Akun</h3>
                    <img src="../images/logo2.png" alt="Logo" class="img-fluid logo-img my-3"> <!-- Logo -->
                </div>
                <div class="card-body mx-2">
                    <form action="proses_registrasi.php" method="POST">
                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" class="form-control" id="nik" name="nik" required pattern="\d{16}" maxlength="16" title="NIK harus terdiri dari 16 digit angka.">
                            <span id="nik-error" class="text-danger" style="font-size: 0.9em;"></span>
                        </div>
                        <div class=" mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_pasien" class="form-label">Nama Pasien</label>
                            <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" required pattern="[A-Za-z ]+" title="Hanya huruf dan spasi yang diperbolehkan">
                        </div>
                        <div class=" mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" required>
                        </div>
                        <div class="mb-3">
                            <?php
                            session_start();
                            if (isset($_SESSION['error'])) {
                                echo '<div class="alert alert-danger" role="alert" id="alert">' . $_SESSION['error'] . '</div>';
                                unset($_SESSION['error']);
                            }
                            ?>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-utama">Daftar</button>
                            <a href="../login.php" class="btn btn-kedua">Kembali</a>
                        </div>
                    </form>
                    <?php include '../includes/footer.php'; ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#nik').on('input', function() {
                var nik = $(this).val();
                if (nik.length === 16) { // Pastikan NIK memiliki panjang 16
                    $.ajax({
                        url: 'proses_registrasi.php',
                        type: 'GET',
                        data: {
                            nik: nik
                        },
                        dataType: 'json',
                        success: function(response) {
                            const nikError = $('#nik-error');
                            if (response.exists) {
                                nikError.text("NIK sudah terdaftar.");
                            } else {
                                nikError.text("");
                            }
                        }
                    });
                } else {
                    $('#nik-error').text(""); // Hapus pesan jika panjang NIK kurang dari 16
                }
            });
        });
    </script>
</body>

</html>