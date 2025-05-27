<?php
session_start();
include '../../includes/config.php'; // Include database connection

$current_page = basename($_SERVER['PHP_SELF']);

// Periksa apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['nik']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Fungsi untuk mengambil data pengguna non-admin berdasarkan jumlah login
function get_user_login_stats($conn)
{
    $query = "SELECT nik, COUNT(*) as total_logins FROM tb_log WHERE nik IN (SELECT nik FROM tb_pengguna WHERE is_admin = 0) GROUP BY nik ORDER BY total_logins DESC"; // Ambil hanya pengguna non-admin
    $result = $conn->query($query);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row; // Simpan hasil dalam array
    }
    return $data; // Kembalikan data sebagai array
}

// Fungsi untuk mengambil data statistik provinsi berdasarkan kunjungan pengguna
function get_count_by_province($conn)
{
    // Ganti 'nama_tabel' dengan nama tabel yang sesuai
    $query = "SELECT provinsi, COUNT(*) as jumlah FROM tb_catatan GROUP BY provinsi";
    $result = $conn->query($query);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}


// Ambil data statistik provinsi
$province_data = get_count_by_province($conn);


// Ambil data pengguna non-admin yang paling sering login
$user_login_data = get_user_login_stats($conn);
?>

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

<body>
    <?php include 'header.php'; ?>

    <div class="card mt-3">
        <div class="card-body text-center">
            <h4 class="mb-4">Statistik Kunjungan Pengguna</h4>
            <canvas id="myChart" style="width: 100%; max-width: 800px; height: 400px; margin: 0 auto;"></canvas>
            <script>
                const ctx = document.getElementById('myChart').getContext('2d');
                const provinces = <?php echo json_encode(array_column($province_data, 'provinsi')); ?>;
                const counts = <?php echo json_encode(array_column($province_data, 'jumlah')); ?>;

                const myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: provinces,
                        datasets: [{
                            label: 'Jumlah Pengguna',
                            data: counts,
                            backgroundColor: 'rgba(120, 144, 248, 0.6)',
                            borderColor: 'rgba(222, 99, 109, 1)',
                            borderWidth: 1,
                            borderRadius: 5,
                            barThickness: 50
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    color: '#333',
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                                titleFont: {
                                    size: 14
                                },
                                bodyFont: {
                                    size: 12
                                },
                                padding: 10,
                                cornerRadius: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah Pengguna',
                                    color: '#666',
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    }
                                },
                                ticks: {
                                    color: '#444',
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Provinsi',
                                    color: '#666',
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    }
                                },
                                ticks: {
                                    color: '#444',
                                    font: {
                                        size: 15
                                    }
                                }
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>
    <br>
    <div class="card mt-3">
        <div class="card-body text-center">
            <h4 class="mb-4">Statistik Jumlah Login Pengguna</h4>
            <div class="row justify-content-center align-items-center flex-column flex-md-row">
                <div class="col-12 col-md-6 d-flex justify-content-center mb-3 mb-md-0">
                    <canvas id="loginChart" style="max-width: 300px;"></canvas>
                </div>
                <div class="col-12 col-md-6">
                    <table class="table table-striped table-bordered text-center">
                        <thead>
                            <tr class="text-center align-middle">
                                <th>NIK</th>
                                <th>Jumlah Login</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($user_login_data as $user) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['nik']); ?></td>
                                    <td><?php echo htmlspecialchars($user['total_logins']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <script>
                const loginCtx = document.getElementById('loginChart').getContext('2d');
                const users = <?php echo json_encode(array_column($user_login_data, 'nik')); ?>;
                const loginCounts = <?php echo json_encode(array_column($user_login_data, 'total_logins')); ?>;

                const loginChart = new Chart(loginCtx, {
                    type: 'pie',
                    data: {
                        labels: users,
                        datasets: [{
                            label: 'Jumlah Login',
                            data: loginCounts,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.label + ': ' + tooltipItem.raw + ' Login';
                                    }
                                }
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>
    <?php include '../../includes/footer.php'; ?>
    <script src="../js/bootstrap.bundle.js"></script>
</body>

</html>

<?php
// Tutup koneksi database
$conn->close();
?>