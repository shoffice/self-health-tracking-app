<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin</title>
    <link href="../../css/bootstrap.css" rel="stylesheet">
    <link href="../../css/custom.css" rel="stylesheet">
    <script src="../../js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Menambahkan Chart.js -->
</head>

<body class="bg-light">
    <div class="container" style="max-width: 1000px;">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-3">
                    <h3 class="fw-bold mt-4">PEDULI DIRI </h3>
                    <h3 class="fw-bold mb-4 no-print"> Admin Panel</h3>
                    <img src="../../images/logo2.png" alt="Descriptive Alt Text" class="img-fluid" style="width: 80px; height: 80px;">
                </div>
                <nav class="navbar navbar-expand-lg navbar-light mb-4">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'admin.php' ? 'active' : ''; ?>" href="admin.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'manajemen_pengguna.php' ? 'active' : ''; ?>" href="manajemen_pengguna.php">Data Pengguna</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../logout.php">Logout</a>
                            </li>
                        </ul>
                    </div>
                </nav>