<?php
session_start();
include '../includes/config.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['nik'])) {
    header("Location: ../login.php");
    exit;
}

// Ambil ID catatan terakhir
$query = "SELECT MAX(id_catatan) AS last_id FROM tb_catatan";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$next_id = $row['last_id'] + 1; // ID catatan baru

$current_page = basename($_SERVER['PHP_SELF']);
$tanggal_hari_ini = date('Y-m-d');
$nik = $_SESSION['nik'];

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Isi Catatan Perjalanan - Aplikasi Peduli Diri</title>
    <link href="../css/bootstrap.css" rel="stylesheet">
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script>
        const locations = <?php echo json_encode($locations); ?>; // Make PHP array available in JavaScript

        function updateCityDropdown() {
            const provinceSelect = document.getElementById('provinsi');
            const citySelect = document.getElementById('kota');
            const selectedProvince = provinceSelect.value;

            // Clear existing city options
            citySelect.innerHTML = '<option value="">Pilih Kota</option>'; // Reset to default option

            // Check if a province is selected
            if (selectedProvince && locations[selectedProvince]) {
                // Populate cities for the selected province
                locations[selectedProvince].forEach(city => {
                    const option = document.createElement('option');
                    option.value = city;
                    option.textContent = city;
                    citySelect.appendChild(option);
                });
            }
        }
    </script>

</head>

<body>
    <?php include '../includes/header.php'; ?>
    <form action="proses_perjalanan.php" method="POST">
        <div class="mb-4">
            <?php
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success" role="alert" id="alert">' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger" role="alert" id="alert">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            ?>
        </div>
        <div class="mb-3">
            <label for="nik" class="form-label">NIK</label>
            <input type="number" class="form-control" id="nik" name="nik" value="<?php echo isset($_SESSION['nik']) ? $_SESSION['nik'] : ''; ?>" required readonly>
        </div>
        <div class="mb-3">
            <label for="tgl_catatan" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tgl_catatan" name="tgl_catatan" max="<?php echo $tanggal_hari_ini; ?>" required>
        </div>
        <div class="mb-3">
            <label for="jam_catatan" class="form-label">Jam</label>
            <input type="time" class="form-control" id="jam_catatan" name="jam_catatan" required>
        </div>
        <div class="mb-3">
            <label for="provinsi" class="form-label">Provinsi</label>
            <select name="provinsi" id="provinsi" class="form-control" onchange="updateCityDropdown()" required>
                <option value="">Pilih Provinsi</option>
                <?php foreach ($locations as $key => $value): ?>
                    <option value="<?php echo $key; ?>" <?php echo (isset($provinsi) && $provinsi == $key) ? 'selected' : ''; ?>><?php echo $key; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="kota" class="form-label">Kota</label>
            <select name="kota" id="kota" class="form-control" required>
                <option value="">Pilih Kota</option>
                <?php if (isset($provinsi) && $provinsi && isset($locations[$provinsi])): ?>
                    <?php foreach ($locations[$provinsi] as $city): ?>
                        <option value="<?php echo $city; ?>" <?php echo (isset($kota) && $kota == $city) ? 'selected' : ''; ?>><?php echo $city; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="lokasi_catatan" class="form-label">Lokasi yang dikunjungi</label>
            <input type="text" class="form-control" id="lokasi_catatan" name="lokasi_catatan" placeholder="Masukkan lokasi spesifik" required>
        </div>
        <div class="mb-3">
            <label for="suhu_tubuh" class="form-label">Suhu Tubuh</label>
            <div class="input-group">
                <input type="text" class="form-control" id="suhu_tubuh" name="suhu_tubuh" placeholder="Contoh: 36,5" required>
                <div class="col-auto">
                    <select class="form-select" name="satuan_suhu" id="satuan_suhu">
                        <option value="C">°C (Celsius)</option>
                        <option value="F">°F (Fahrenheit)</option>
                        <option value="R">°R (Reamur)</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-utama">Simpan Catatan</button>
            <a href="catatan_perjalanan.php" class="btn btn-kedua">Kembali</a>
        </div>
    </form>
    <script>
        setTimeout(function() {
            const alert = document.getElementById('alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 2500);
    </script>
</body>

</html>

<?php
$conn->close();
?>