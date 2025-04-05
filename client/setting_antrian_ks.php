<?php
session_start();

// Include the PDO database connection file
require '../config/database.php'; // Koneksi database menggunakan PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hapus semua data antrian untuk hari ini
    $tanggal_hari_ini = date('Y-m-d');
    $sql = "DELETE FROM antrian_karangsatria WHERE tanggal = :tanggal";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':tanggal', $tanggal_hari_ini, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $message = "Antrian untuk hari ini telah berhasil di-reset.";
    } else {
        $message = "Terjadi kesalahan saat mereset antrian.";
    }
}

// Set timezone ke Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

// Aktifkan error reporting untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ambil data dari tabel log_antrian_karangsatria
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM log_antrian_karangsatria";
if (!empty($search)) {
    $sql .= " WHERE nama LIKE :search";
}

$stmt = $pdo->prepare($sql);
if (!empty($search)) {
    $search_param = "%" . $search . "%";
    $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
}
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch results as array

// Query untuk menghitung jumlah kemunculan setiap nama
$sql_ranking_nama = "
    SELECT 
        nama,
        MAX(telepon) AS telepon, 
        COUNT(nama) AS jumlah_muncul 
    FROM 
        log_antrian_karangsatria 
    GROUP BY 
        nama 
    ORDER BY 
        jumlah_muncul DESC
    LIMIT 10";
$result_ranking_nama = $pdo->query($sql_ranking_nama);

// Fetch all rows from the query
$result_ranking_nama = $result_ranking_nama->fetchAll(PDO::FETCH_ASSOC);

// Check if there was an error with the query
if ($result_ranking_nama === false) {
    die("Error mendapatkan data: " . $pdo->errorInfo()[2]);
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    // Hapus data berdasarkan ID
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM log_antrian_karangsatria WHERE id = :id";
    $delete_stmt = $pdo->prepare($delete_sql);
    $delete_stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
    if ($delete_stmt->execute()) {
        header("Location: {$_SERVER['PHP_SELF']}"); // Refresh halaman setelah penghapusan
        exit;
    } else {
        echo "Error menghapus data: " . $delete_stmt->errorInfo()[2];
    }
}

// Handle edit request
if (isset($_POST['edit_id'])) {
    // Edit data
    $edit_id = $_POST['edit_id'];
    $edit_nama = $_POST['edit_nama'];
    $edit_telepon = $_POST['edit_telepon'];

    $update_sql = "UPDATE log_antrian_karangsatria SET nama = :nama, telepon = :telepon WHERE id = :id";
    $update_stmt = $pdo->prepare($update_sql);
    $update_stmt->bindParam(':nama', $edit_nama, PDO::PARAM_STR);
    $update_stmt->bindParam(':telepon', $edit_telepon, PDO::PARAM_STR);
    $update_stmt->bindParam(':id', $edit_id, PDO::PARAM_INT);
    if ($update_stmt->execute()) {
        header("Location: {$_SERVER['PHP_SELF']}"); // Refresh halaman setelah edit
        exit;
    } else {
        echo "Error memperbarui data: " . $update_stmt->errorInfo()[2];
    }
}

// Check if form is submitted to update jam_reset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jam_reset'])) {
    $new_jam_reset = $_POST['jam_reset'];

    // Validate the input
    if (strtotime($new_jam_reset)) {
        // Update the jam_reset value in the database
        $sql_update = "UPDATE settings SET value = :value WHERE key_name = 'jam_reset'";

        $stmt = $pdo->prepare($sql_update);
        $stmt->bindParam(':value', $new_jam_reset, PDO::PARAM_STR);
        if ($stmt->execute()) {
            echo "Jam reset telah diperbarui!";
        } else {
            echo "Error memperbarui jam reset: " . $stmt->errorInfo()[2];
        }
    } else {
        echo "Format jam tidak valid!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Data Antrian</title>
    <link rel="stylesheet" href="/antrian/asset/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f8ff;
            overflow-y: auto;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow-x: auto;
        }
        .container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 0.95em;
        }
        table thead tr {
            background-color: #007bff;
            color: white;
            text-align: left;
        }
        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
            word-wrap: break-word;
        }
        table tbody tr:nth-child(even) {
            background-color: #e9f5ff;
        }
        table tbody tr:hover {
            background-color: #d6eaff;
        }
        .footer {
            text-align: center;
            color: #555;
            font-size: 0.85em;
            margin-top: 20px;
        }
        @media (max-width: 768px) {
            table th, table td {
                padding: 8px;
                font-size: 0.85em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="POST" action="setting_antrian_ks.php">
            <label for="jam_reset">Jam Reset (format HH:MM:SS):</label>
            <input type="time" id="jam_reset" name="jam_reset" required>
            <button type="submit">Update Jam Reset</button>
        </form>
        <h1>Ranking Nama Paling Sering Muncul</h1>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Peringkat</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Jumlah Muncul</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($result_ranking_nama) > 0): ?>
                        <?php 
                        $rank = 1;
                        foreach ($result_ranking_nama as $row): 
                        ?>
                            <tr>
                                <td><?= $rank++ ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['telepon']) ?></td>
                                <td><?= htmlspecialchars($row['jumlah_muncul']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Tidak ada data yang ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h1>Data Log Antrian</h1>
        <div style="overflow-x: auto; max-height: 400px; overflow-y: scroll;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nomor Antrian</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($result) > 0): ?>
                        <?php foreach ($result as $row): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['random_number'] ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['telepon']) ?></td>
                                <td><?= $row['tanggal'] ?></td>
                                <td>
                                    <a href="?edit_id=<?= $row['id'] ?>">Edit</a> | 
                                    <a href="?delete_id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">Tidak ada data ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
