<?php
session_start();
include '../../includes/config.php'; // Include database connection

// Check if user is admin
if (!isset($_SESSION['nik']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit();
}


// Ambil ID catatan dari URL
$id_catatan = isset($_GET['id']) ? $_GET['id'] : '';
if (!$id_catatan) {
    echo "ID catatan tidak ditemukan.";
    exit();
}

// Ambil data catatan untuk diedit
$query = "SELECT * FROM tb_catatan WHERE id_catatan = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_catatan);
$stmt->execute();
$result = $stmt->get_result();
$catatan = $result->fetch_assoc();

// Ambil provinsi dan kota yang sudah disimpan
$provinsi = $catatan['provinsi'];
$kota = $catatan['kota'];

$locations = [
    "Aceh" => [
        "Banda Aceh",
        "Langsa",
        "Lhokseumawe",
        "Sabang",
        "Subulussalam",
        "Aceh Barat",
        "Aceh Barat Daya",
        "Aceh Besar",
        "Aceh Jaya",
        "Aceh Selatan",
        "Aceh Singkil",
        "Aceh Tamiang",
        "Aceh Tengah",
        "Aceh Tenggara",
        "Aceh Timur",
        "Aceh Utara",
        "Bener Meriah",
        "Bireuen",
        "Gayo Lues",
        "Nagan Raya",
        "Pidie",
        "Pidie Jaya",
        "Simeulue"
    ],
    "Bali" => [
        "Denpasar",
        "Badung",
        "Bangli",
        "Buleleng",
        "Gianyar",
        "Jembrana",
        "Karangasem",
        "Klungkung",
        "Tabanan"
    ],
    "Banten" => [
        "Serang",
        "Cilegon",
        "Tangerang",
        "Tangerang Selatan",
        "Lebak",
        "Pandeglang"
    ],
    "Bengkulu" => [
        "Bengkulu",
        "Bengkulu Selatan",
        "Bengkulu Tengah",
        "Bengkulu Utara",
        "Kaur",
        "Kepahiang",
        "Lebong",
        "Mukomuko",
        "Rejang Lebong",
        "Seluma"
    ],
    "DKI Jakarta" => [
        "Jakarta Selatan",
        "Jakarta Utara",
        "Jakarta Barat",
        "Jakarta Pusat",
        "Jakarta Timur",
        "Kepulauan Seribu"
    ],
    "D.I Yogyakarta" => ["Yogyakarta", "Bantul", "Gunungkidul", "Kulon Progo", "Sleman"],
    "Gorontalo" => [
        "Gorontalo",
        "Boalemo",
        "Bone Bolango",
        "Gorontalo Utara",
        "Pohuwato"
    ],
    "Jambi" => [
        "Jambi",
        "Sungai Penuh",
        "Batanghari",
        "Bungo",
        "Kerinci",
        "Merangin",
        "Muaro Jambi",
        "Sarolangun",
        "Tanjung Jabung Barat",
        "Tanjung Jabung Timur",
        "Tebo"
    ],
    "Jawa Barat" => [
        "Bandung",
        "Bekasi",
        "Bogor",
        "Cimahi",
        "Cirebon",
        "Depok",
        "Sukabumi",
        "Tasikmalaya",
        "Banjar",
        "Bandung Barat",
        "Ciamis",
        "Cianjur",
        "Garut",
        "Indramayu",
        "Karawang",
        "Kuningan",
        "Majalengka",
        "Pangandaran",
        "Purwakarta",
        "Subang",
        "Sumedang"
    ],
    "Jawa Tengah" => [
        "Semarang",
        "Surakarta",
        "Magelang",
        "Pekalongan",
        "Salatiga",
        "Tegal",
        "Banjarnegara",
        "Banyumas",
        "Batang",
        "Blora",
        "Boyolali",
        "Brebes",
        "Cilacap",
        "Demak",
        "Grobogan",
        "Jepara",
        "Karanganyar",
        "Kebumen",
        "Kendal",
        "Klaten",
        "Kudus",
        "Pati",
        "Pemalang",
        "Purbalingga",
        "Purworejo",
        "Rembang",
        "Sragen",
        "Sukoharjo",
        "Temanggung",
        "Wonogiri",
        "Wonosobo"
    ],
    "Jawa Timur" => [
        "Surabaya",
        "Malang",
        "Batu",
        "Blitar",
        "Kediri",
        "Madiun",
        "Mojokerto",
        "Pasuruan",
        "Probolinggo",
        "Bangkalan",
        "Banyuwangi",
        "Bojonegoro",
        "Bondowoso",
        "Gresik",
        "Jember",
        "Jombang",
        "Lamongan",
        "Lumajang",
        "Magetan",
        "Nganjuk",
        "Ngawi",
        "Pacitan",
        "Pamekasan",
        "Ponorogo",
        "Sampang",
        "Sidoarjo",
        "Situbondo",
        "Sumenep",
        "Trenggalek",
        "Tuban",
        "Tulungagung"
    ],
    "Kalimantan Barat" => [
        "Pontianak",
        "Singkawang",
        "Bengkayang",
        "Kapuas Hulu",
        "Kayong Utara",
        "Ketapang",
        "Kubu Raya",
        "Landak",
        "Melawi",
        "Sambas",
        "Sanggau",
        "Sekadau",
        "Sintang"
    ],
    "Kalimantan Selatan" => [
        "Banjarmasin",
        "Banjarbaru",
        "Balangan",
        "Banjar",
        "Barito Kuala",
        "Hulu Sungai Selatan",
        "Hulu Sungai Tengah",
        "Hulu Sungai Utara",
        "Kotabaru",
        "Tabalong",
        "Tanah Bumbu",
        "Tanah Laut",
        "Tapin"
    ],
    "Kalimantan Tengah" => [
        "Palangka Raya",
        "Barito Selatan",
        "Barito Timur",
        "Barito Utara",
        "Gunung Mas",
        "Kapuas",
        "Katingan",
        "Kotawaringin Barat",
        "Kotawaringin Timur",
        "Lamandau",
        "Murung Raya",
        "Pulang Pisau",
        "Sukamara",
        "Seruyan"
    ],
    "Kalimantan Timur" => [
        "Samarinda",
        "Balikpapan",
        "Bontang",
        "Berau",
        "Kutai Barat",
        "Kutai Kartanegara",
        "Kutai Timur",
        "Paser",
        "Penajam Paser Utara",
        "Mahakam Ulu"
    ],
    "Kalimantan Utara" => ["Tarakan", "Bulungan", "Malinau", "Nunukan", "Tana Tidung"],
    "Kepulauan Bangka Belitung" => [
        "Pangkal Pinang",
        "Bangka",
        "Bangka Barat",
        "Bangka Selatan",
        "Bangka Tengah",
        "Belitung",
        "Belitung Timur"
    ],
    "Kepulauan Riau" => [
        "Batam",
        "Tanjung Pinang",
        "Bintan",
        "Karimun",
        "Kepulauan Anambas",
        "Lingga",
        "Natuna"
    ],
    "Lampung" => [
        "Bandar Lampung",
        "Metro",
        "Kotabumi",
        "Liwa",
        "Lampung Barat",
        "Lampung Selatan",
        "Lampung Tengah",
        "Lampung Timur",
        "Lampung Utara",
        "Mesuji",
        "Pesawaran",
        "Pringsewu",
        "Tanggamus",
        "Tulang Bawang",
        "Tulang Bawang Barat",
        "Way Kanan",
        "Pesisir Barat"
    ],
    "Maluku" => [
        "Ambon",
        "Tual",
        "Buru",
        "Buru Selatan",
        "Kepulauan Aru",
        "Maluku Barat Daya",
        "Maluku Tengah",
        "Maluku Tenggara",
        "Maluku Tenggara Barat",
        "Seram Bagian Barat",
        "Seram Bagian Timur"
    ],
    "Maluku Utara" => [
        "Ternate",
        "Tidore Kepulauan",
        "Halmahera Barat",
        "Halmahera Tengah",
        "Halmahera Utara",
        "Halmahera Selatan",
        "Halmahera Timur",
        "Kepulauan Sula",
        "Pulau Morotai",
        "Pulau Taliabu"
    ],
    "Nusa Tenggara Barat" => [
        "Mataram",
        "Bima",
        "Dompu",
        "Lombok Barat",
        "Lombok Tengah",
        "Lombok Timur",
        "Lombok Utara",
        "Sumbawa",
        "Sumbawa Barat"
    ],
    "Nusa Tenggara Timur" => [
        "Kupang",
        "Alor",
        "Belu",
        "Ende",
        "Flores Timur",
        "Lembata",
        "Manggarai",
        "Manggarai Barat",
        "Manggarai Timur",
        "Ngada",
        "Nagekeo",
        "Rote Ndao",
        "Sabu Raijua",
        "Sikka",
        "Sumba Barat",
        "Sumba Barat Daya",
        "Sumba Tengah",
        "Sumba Timur",
        "Timor Tengah Selatan",
        "Timor Tengah Utara"
    ],
    "Papua" => [
        "Jayapura",
        "Asmat",
        "Biak Numfor",
        "Boven Digoel",
        "Deiyai",
        "Dogiyai",
        "Intan Jaya",
        "Jayawijaya",
        "Keerom",
        "Kepulauan Yapen",
        "Lanny Jaya",
        "Mamberamo Raya",
        "Mamberamo Tengah",
        "Mappi",
        "Merauke",
        "Mimika",
        "Nabire",
        "Nduga",
        "Paniai",
        "Pegunungan Bintang",
        "Puncak",
        "Puncak Jaya",
        "Sarmi",
        "Supiori",
        "Tolikara",
        "Waropen",
        "Yahukimo",
        "Yalimo"
    ],
    "Papua Barat" => [
        "Sorong",
        "Fakfak",
        "Kaimana",
        "Manokwari",
        "Manokwari Selatan",
        "Maybrat",
        "Pegunungan Arfak",
        "Raja Ampat",
        "Sorong Selatan",
        "Tambrauw",
        "Teluk Bintuni",
        "Teluk Wondama"
    ],
    "Riau" => [
        "Pekanbaru",
        "Dumai",
        "Bengkalis",
        "Indragiri Hilir",
        "Indragiri Hulu",
        "Kampar",
        "Kuantan Singingi",
        "Pelalawan",
        "Rokan Hilir",
        "Rokan Hulu",
        "Siak",
        "Kepulauan Meranti"
    ],
    "Sulawesi Barat" => [
        "Mamuju",
        "Majene",
        "Mamasa",
        "Mamuju Utara",
        "Polewali Mandar",
        "Mamuju Tengah"
    ],
    "Sulawesi Selatan" => [
        "Makassar",
        "Palopo",
        "Parepare",
        "Bantaeng",
        "Barru",
        "Bone",
        "Bulukumba",
        "Enrekang",
        "Gowa",
        "Jeneponto",
        "Kepulauan Selayar",
        "Luwu",
        "Luwu Timur",
        "Luwu Utara",
        "Maros",
        "Pangkajene dan Kepulauan",
        "Pinrang",
        "Sidenreng Rappang",
        "Sinjai",
        "Soppeng",
        "Takalar",
        "Tana Toraja",
        "Toraja Utara",
        "Wajo"
    ],
    "Sulawesi Tengah" => [
        "Palu",
        "Banggai",
        "Banggai Kepulauan",
        "Banggai Laut",
        "Buol",
        "Donggala",
        "Morowali",
        "Parigi Moutong",
        "Poso",
        "Sigi",
        "Tojo Una-Una",
        "Tolitoli"
    ],
    "Sulawesi Tenggara" => [
        "Kendari",
        "Bau-Bau",
        "Bombana",
        "Buton",
        "Buton Utara",
        "Kolaka",
        "Kolaka Timur",
        "Kolaka Utara",
        "Konawe",
        "Konawe Selatan",
        "Konawe Utara",
        "Konawe Kepulauan",
        "Muna",
        "Wakatobi"
    ],
    "Sulawesi Utara" => [
        "Manado",
        "Bitung",
        "Kotamobagu",
        "Tomohon",
        "Bolaang Mongondow",
        "Bolaang Mongondow Selatan",
        "Bolaang Mongondow Timur",
        "Bolaang Mongondow Utara",
        "Kepulauan Sangihe",
        "Kepulauan Siau Tagulandang Biaro",
        "Kepulauan Talaud",
        "Minahasa",
        "Minahasa Selatan",
        "Minahasa Tenggara",
        "Minahasa Utara"
    ],
    "Sumatera Barat" => [
        "Padang",
        "Bukittinggi",
        "Padangpanjang",
        "Pariaman",
        "Payakumbuh",
        "Sawahlunto",
        "Solok",
        "Agam",
        "Dharmasraya",
        "Kepulauan Mentawai",
        "Lima Puluh Kota",
        "Padang Pariaman",
        "Pasaman",
        "Pasaman Barat",
        "Pesisir Selatan",
        "Sijunjung",
        "Solok Selatan",
        "Tanah Datar"
    ],
    "Sumatera Selatan" => [
        "Palembang",
        "Lubuklinggau",
        "Pagar Alam",
        "Prabumulih",
        "Banyuasin",
        "Empat Lawang",
        "Lahat",
        "Muara Enim",
        "Musi Banyuasin",
        "Musi Rawas",
        "Ogan Ilir",
        "Ogan Komering Ilir",
        "Ogan Komering Ulu",
        "Ogan Komering Ulu Selatan",
        "Penukal Abab Lematang Ilir",
        "Ogan Komering Ulu Timur"
    ],
    "Sumatera Utara" => [
        "Medan",
        "Binjai",
        "Gunungsitoli",
        "Padangsidempuan",
        "Pematangsiantar",
        "Sibolga",
        "Tanjungbalai",
        "Tebing Tinggi",
    ]
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $tgl_catatan = $_POST['tgl_catatan'];
    $jam_catatan = $_POST['jam_catatan'];
    $provinsi = $_POST['provinsi'];
    $kota = $_POST['kota'];
    $lokasi_catatan = $_POST['lokasi_catatan'];
    $suhu_tubuh = $_POST['suhu_tubuh'];
    $satuan_suhu = $_POST['satuan_suhu'];

    // Update data catatan
    $update_query = "UPDATE tb_catatan SET tgl_catatan = ?, jam_catatan = ?, provinsi = ?, kota = ?, lokasi_catatan = ?, suhu_tubuh = ?, satuan_suhu = ? WHERE id_catatan = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssssdsi", $tgl_catatan, $jam_catatan, $provinsi, $kota, $lokasi_catatan, $suhu_tubuh, $satuan_suhu, $id_catatan);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Catatan berhasil diperbarui.";
        header("Location: catatan_perjalanan.php?nik=" . urlencode($catatan['nik']));
        exit();
    } else {
        echo "Terjadi kesalahan saat memperbarui data.";
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Catatan</title>
    <link href="../../css/bootstrap.css" rel="stylesheet">
    <link href="../../css/custom.css" rel="stylesheet">
    <script src="../../js/bootstrap.bundle.min.js"></script>
    <script>
        const locations = <?php echo json_encode($locations); ?>; // Mengonversi PHP array ke JavaScript
        function updateCityDropdown() {
            const provinceSelect = document.getElementById("provinsi");
            const citySelect = document.getElementById("kota");
            const selectedProvince = provinceSelect.value;

            // Kosongkan dropdown kota
            citySelect.innerHTML = '<option value="">Pilih Kota</option>';

            // Jika provinsi dipilih, tambahkan kota yang sesuai
            if (selectedProvince) {
                const cities = locations[selectedProvince];
                cities.forEach(city => {
                    const option = document.createElement("option");
                    option.value = city;
                    option.textContent = city;
                    citySelect.appendChild(option);
                });

                // Pilih kota yang sudah ada jika ada
                if (<?php echo json_encode($kota); ?>) {
                    citySelect.value = <?php echo json_encode($kota); ?>;
                }
            }
        }
    </script>
</head>


<body class="bg-light">
    <div class="container" style="max-width: 1000px;">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-3">
                    <h3 class="fw-bold">PEDULI DIRI</h3>
                    <h3 class="fw-bold mb-4 no-print"> Admin Panel</h3>
                    <img src="../../images/logo2.png" alt="Descriptive Alt Text" class="img-fluid" style="width: 80px; height: 80px;">
                </div>
                <h4 class="my-4">Edit Catatan Perjalanan</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label for="tgl_catatan" class="form-label">Tanggal</label>
                        <input type="date" name="tgl_catatan" id="tgl_catatan" class="form-control" value="<?php echo htmlspecialchars($catatan['tgl_catatan']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="jam_catatan" class="form-label">Jam</label>
                        <input type="time" name="jam_catatan" id="jam_catatan" class="form-control" value="<?php echo htmlspecialchars($catatan['jam_catatan']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="provinsi" class="form-label">Provinsi</label>
                        <select name="provinsi" id="provinsi" class="form-control" onchange="updateCityDropdown()" required>
                            <option value="">Pilih Provinsi</option>
                            <?php foreach ($locations as $key => $value): ?>
                                <option value="<?php echo $key; ?>" <?php echo ($provinsi == $key) ? 'selected' : ''; ?>><?php echo $key; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kota" class="form-label">Kota</label>
                        <select name="kota" id="kota" class="form-control" required>
                            <option value="">Pilih Kota</option>
                            <?php if ($provinsi && isset($locations[$provinsi])): ?>
                                <?php foreach ($locations[$provinsi] as $city): ?>
                                    <option value="<?php echo $city; ?>" <?php echo ($kota == $city) ? 'selected' : ''; ?>><?php echo $city; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="lokasi_catatan" class="form-label">Lokasi</label>
                        <input type="text" name="lokasi_catatan" id="lokasi_catatan" class="form-control" value="<?php echo htmlspecialchars($catatan['lokasi_catatan']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="suhu_tubuh" class="form-label">Suhu Tubuh</label>
                        <input type="number" step="0.01" name="suhu_tubuh" id="suhu_tubuh" class="form-control" value="<?php echo htmlspecialchars($catatan['suhu_tubuh']); ?>" required>
                    </div>
                    <div class="mb-3 col-auto">
                        <label for="satuan_suhu" class="form-label">Satuan Suhu</label>
                        <select class="form-select" name="satuan_suhu" id="satuan_suhu">
                            <option value="C" <?php if (htmlspecialchars($catatan['satuan_suhu']) == 'C') echo 'selected'; ?>>°C (Celsius)</option>
                            <option value="F" <?php if (htmlspecialchars($catatan['satuan_suhu']) == 'F') echo 'selected'; ?>>°F (Fahrenheit)</option>
                            <option value="R" <?php if (htmlspecialchars($catatan['satuan_suhu']) == 'R') echo 'selected'; ?>>°R (Reamur)</option>
                        </select>
                    </div>
                    <div class="mb-3 col-auto">
                        <button type="submit" class="btn btn-utama  ">Simpan Perubahan</button>
                        <a href="catatan_perjalanan.php?nik=<?php echo urlencode($catatan['nik']); ?>" class="btn btn-kedua">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>