<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Aplikasi Peduli Diri</title>
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
    <script src="../js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light">
    <div class="container" style="max-width: 1000px;">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-3">
                    <h3 class="fw-bold">PEDULI DIRI</h3>
                    <img src="../images/logo2.png" alt="Descriptive Alt Text" class="img-fluid" style="width: 80px; height: 80px;">
                </div>
                <nav class="navbar navbar-expand-lg navbar-light mb-4">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'home.php' ? 'active' : ''; ?>" href="home.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'data_pengguna.php' ? 'active' : ''; ?>" href="data_pengguna.php">Data Pengguna</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'catatan_perjalanan.php' ? 'active' : ''; ?>" href="catatan_perjalanan.php">Catatan Perjalanan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'perjalanan.php' ? 'active' : ''; ?>" href="perjalanan.php">Isi Data</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../logout.php">Logout</a>
                            </li>
                        </ul>
                    </div>
                </nav>