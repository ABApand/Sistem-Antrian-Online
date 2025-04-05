<?php
session_start();

// Check if the location is valid and matches 'bojongsari'
if ($_SESSION['validLocation'] !== 'bojongsari') {
    // Redirect to the main page or show an error
    header("Location: index.php");
    exit();
}


require 'config/database.php'; // Include the PDO database connection

// Fetch jam_reset from the database using PDO
try {
    $sql_get_reset_time = "SELECT value FROM settings WHERE key_name = 'jam_reset'";
    $stmt = $pdo->prepare($sql_get_reset_time);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $jam_reset = $row['value']; // Assign the value to $jam_reset
    } else {
        // Default fallback if not found
        $jam_reset = '08:00:00';
    }
} catch (PDOException $e) {
    die("Error fetching reset time: " . $e->getMessage());
}

// Set timezone ke Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk menghasilkan device ID jika belum ada
function getDeviceID() {
    if (!isset($_COOKIE['device_id'])) {
        $device_id = bin2hex(random_bytes(16)); // Membuat ID unik menggunakan random_bytes
        setcookie('device_id', $device_id, time() + (86400 * 1), "/"); // Menyimpan cookie dengan ID unik yang berlaku 1 hari
        return $device_id;
    }
    return $_COOKIE['device_id'];
}

$device_id = getDeviceID();
$ip_address = $_SERVER['REMOTE_ADDR'];
$tanggal_hari_ini = date('Y-m-d');

// Mengubah waktu sekarang dan waktu reset ke dalam format timestamp
$waktu_sekarang = strtotime(date('H:i:s'));
$waktu_reset = strtotime($jam_reset);
$waktu_reset_end = $waktu_reset + (2 * 60 * 60); // Waktu reset berakhir 2 jam setelah waktu reset

// Apakah form aktif (waktu sekarang antara jam reset dan jam reset + 2 jam)
$form_aktif = $waktu_sekarang >= $waktu_reset && $waktu_sekarang <= $waktu_reset_end;

// Hapus data pada tabel antrian_bojongsari 1 menit setelah waktu reset berakhir
if ($waktu_sekarang >= ($waktu_reset_end + 60) && $waktu_sekarang < ($waktu_reset_end + 120)) {
    try {
        // Pindahkan data ke tabel log_antrian_bojongsari
        $sql_insert_log = "INSERT INTO log_antrian_bojongsari (device_id, ip_address, random_number, nama, telepon, tanggal)
                           SELECT device_id, ip_address, random_number, nama, telepon, tanggal FROM antrian_bojongsari";
        $pdo->exec($sql_insert_log);
        
        // Hapus data dari tabel antrian_bojongsari
        $sql_delete = "DELETE FROM antrian_bojongsari";
        $pdo->exec($sql_delete);
        
        $message = "Data antrian_bojongsari telah dipindahkan ke log dan dihapus.";
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Inisialisasi variabel
$total_antrian_bojongsari = 0;
$antrian_bojongsari_list = null;
$message = "";

// Periksa jika perangkat atau IP sudah menghasilkan nomor antrian_bojongsari hari ini
try {
    $sql_check_device = "SELECT * FROM antrian_bojongsari WHERE (device_id = :device_id OR ip_address = :ip_address) AND tanggal = :tanggal";
    $stmt_check_device = $pdo->prepare($sql_check_device);
    $stmt_check_device->bindParam(':device_id', $device_id);
    $stmt_check_device->bindParam(':ip_address', $ip_address);
    $stmt_check_device->bindParam(':tanggal', $tanggal_hari_ini);
    $stmt_check_device->execute();
    $antrian_bojongsari_hari_ini = $stmt_check_device->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error checking device: " . $e->getMessage());
}

// Jika perangkat sudah mendapatkan nomor antrian_bojongsari
if ($antrian_bojongsari_hari_ini) {
    $message = "Anda Sudah Mengambil Nomor Antrian";
}

// Hitung total antrian_bojongsari
try {
    $sql_total = "SELECT COUNT(*) AS total_antrian_bojongsari FROM antrian_bojongsari WHERE tanggal = :tanggal";
    $stmt_total = $pdo->prepare($sql_total);
    $stmt_total->bindParam(':tanggal', $tanggal_hari_ini);
    $stmt_total->execute();
    $total_data = $stmt_total->fetch(PDO::FETCH_ASSOC);
    $total_antrian_bojongsari = $total_data['total_antrian_bojongsari'] ?? 0;
} catch (PDOException $e) {
    die("Error menghitung total: " . $e->getMessage());
}

// Ambil daftar antrian_bojongsari hari ini
try {
    $sql_antrian_bojongsari_list = "SELECT random_number, nama FROM antrian_bojongsari WHERE tanggal = :tanggal ORDER BY random_number ASC";
    $stmt_antrian_bojongsari_list = $pdo->prepare($sql_antrian_bojongsari_list);
    $stmt_antrian_bojongsari_list->bindParam(':tanggal', $tanggal_hari_ini);
    $stmt_antrian_bojongsari_list->execute();
    $antrian_bojongsari_list = $stmt_antrian_bojongsari_list->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching antrian_bojongsari list: " . $e->getMessage());
}

// Proses form untuk mendapatkan nomor antrian_bojongsari baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$antrian_bojongsari_hari_ini && $form_aktif) {
    $nama = $_POST['nama'];
    $telepon = $_POST['telepon'];

    if (empty($nama) || empty($telepon)) {
        echo "Nama dan Nomor Telepon wajib diisi!";
        exit;
    }

    // Tentukan grup berdasarkan total antrian_bojongsari
    $group_number = floor($total_antrian_bojongsari / 10) + 1;
    $min = ($group_number - 1) * 10 + 1;
    $max = $group_number * 10;

    do {
        $random_number = rand($min, $max);
        try {
            $sql_check = "SELECT COUNT(*) AS count FROM antrian_bojongsari WHERE random_number = :random_number AND tanggal = :tanggal";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->bindParam(':random_number', $random_number);
            $stmt_check->bindParam(':tanggal', $tanggal_hari_ini);
            $stmt_check->execute();
            $check_result = $stmt_check->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error memeriksa nomor acak: " . $e->getMessage());
        }
    } while ($check_result['count'] > 0);

    // Masukkan data antrian_bojongsari baru ke dalam tabel antrian_bojongsari
    try {
        $sql_insert = "INSERT INTO antrian_bojongsari (device_id, ip_address, random_number, nama, telepon, tanggal) 
                       VALUES (:device_id, :ip_address, :random_number, :nama, :telepon, :tanggal)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->bindParam(':device_id', $device_id);
        $stmt_insert->bindParam(':ip_address', $ip_address);
        $stmt_insert->bindParam(':random_number', $random_number);
        $stmt_insert->bindParam(':nama', $nama);
        $stmt_insert->bindParam(':telepon', $telepon);
        $stmt_insert->bindParam(':tanggal', $tanggal_hari_ini);
        $stmt_insert->execute();
        
        $antrian_bojongsari_hari_ini = ['random_number' => $random_number, 'nama' => $nama];
    } catch (PDOException $e) {
        die("Error memasukkan antrian_bojongsari baru: " . $e->getMessage());
    }
}

$pdo = null; // Close the PDO connection
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrian Online - Lala Bundle Bojongsari</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* General Reset */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            overflow-x: hidden; /* Prevent horizontal scrolling */
        }

        .container {
            max-width: 500px;
            margin: 20px auto;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box; /* Prevent box from overflowing */
        }

        .logo img {
            max-width: 120px;
            display: block;
            margin: 10px auto;
        }

        .card {
            text-align: center;
        }

        h1, h2 {
            margin: 15px 0;
            color: #333;
        }

        p {
            color: #555;
            margin: 5px 0;
        }

        .generate-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin: 20px 0;
        }

        label {
            font-weight: bold;
            text-align: left;
            color: #333;
        }

        input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            width: 100%;
            box-sizing: border-box;
        }

        button {
            padding: 10px 15px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        .antrian_bojongsari-box {
            background-color: #eaf7f5;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .antrian_bojongsari-number {
            font-size: 2em;
            font-weight: bold;
            color: #27ae60;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #3498db;
            color: white;
        }

        @media (max-width: 600px) {
            .container {
                margin: 10px;
                padding: 10px;
            }

            .generate-form label, .generate-form input, .generate-form button {
                font-size: 0.9em;
            }

            .antrian_bojongsari-number {
                font-size: 1.5em;
            }

            table th, table td {
                font-size: 0.9em;
                padding: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="asset/lala_bundle.png" alt="Logo">
        </div>
        <div class="card">
            <h1>Antrian Bojongsari</h1>
            <p>Antrian hari ini: <strong><?= $total_antrian_bojongsari ?></strong></p>

            <?php if (!$form_aktif): ?>
                <p style="color: red; font-weight: bold;">Antrian Belum Dibuka</p>
            <?php elseif ($antrian_bojongsari_hari_ini): ?>
                <div class="antrian_bojongsari-box">
                    <h2>Nomor Antrian Anda:</h2>
                    <p class="antrian_bojongsari-number"><?= $antrian_bojongsari_hari_ini['random_number'] ?></p>
                    <p>Nama: <strong><?= $antrian_bojongsari_hari_ini['nama'] ?></strong></p>
                    <p style="color: red;"><?= $message ?></p>
                </div>
            <?php else: ?>
                <form method="POST" class="generate-form">
                    <label for="nama">Nama:</label>
                    <input type="text" id="nama" name="nama" required placeholder="Nama Anda">

                    <label for="telepon">Nomor Telepon:</label>
                    <input type="text" id="telepon" name="telepon" required placeholder="Nomor Telepon Anda">

                    <button type="submit">Ambil Nomor Antrian</button>
                </form>
            <?php endif; ?>

            <h2>Daftar Antrian:</h2>
            <?php if ($total_antrian_bojongsari > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Nama</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($antrian_bojongsari_list as $row): ?>
                            <tr>
                                <td><?= $row['random_number'] ?></td>
                                <td><?= $row['nama'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Tidak ada Antrian hari ini.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        if (!<?= json_encode($form_aktif) ?>) {
            document.querySelector(".generate-form").style.display = "none";
        }
    </script>
</body>
</html>

