<?php
session_start();

// Set session variable indicating user came from index.php
$_SESSION['from_index'] = true;

include '../config/database.php';

// Mengambil data radius dari database
$query = "SELECT * FROM lokasi_radius";
$stmt = $pdo->query($query);
$lokasi_radius = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Koordinat untuk Karang Satria dan Bojongsari
$karangSatria = ['lat' => -6.2321715541993035, 'lon' => 107.02557276908512, 'radius' => $lokasi_radius[0]['radius']];  // Karang Satria
$bojongsari = ['lat' => -6.408020836166138, 'lon' => 106.7364839729399, 'radius' => $lokasi_radius[1]['radius']];  // Bojongsari
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Lokasi Karang Satria dan Bojongsari</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        /* Atur ukuran peta */
        #map {
            height: 300px; /* Ukuran tinggi peta */
            width: 90%;    /* Lebar peta agar lebih kecil */
            margin: 20px auto; /* Posisikan peta di tengah dengan margin */
        }
        .container {
            max-width: 900px;
            margin-top: 50px;
        }
        .btn-custom {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #218838;
            cursor: pointer;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .alert-custom {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Anda harus berada Di Toko</h2>
            <p>Temukan lokasi terdekat dan ambil nomor antrian dengan mudah!</p>
            <p>Izinkan Permintaan Akses Lokasi!</p>
        </div>

        <div id="map"></div> <!-- Peta akan muncul di sini -->

    </div>

    <!-- Modal Popup for Confirmation -->
    <div class="modal" tabindex="-1" role="dialog" id="locationModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Lokasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Anda berada dalam radius yang sesuai. Apakah Anda ingin melanjutkan ke lokasi berikut?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmRedirect">Ya, Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var map;
        var karangSatria = { lat: <?php echo $karangSatria['lat']; ?>, lon: <?php echo $karangSatria['lon']; ?>, radius: <?php echo $karangSatria['radius']; ?> };
        var bojongsari = { lat: <?php echo $bojongsari['lat']; ?>, lon: <?php echo $bojongsari['lon']; ?>, radius: <?php echo $bojongsari['radius']; ?> };
        var userLat = 0, userLon = 0;

        function initMap() {
            map = L.map('map').setView([karangSatria.lat, karangSatria.lon], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            // Marker untuk Karang Satria
            L.marker([karangSatria.lat, karangSatria.lon]).addTo(map).bindPopup("Karang Satria").openPopup();
            // Marker untuk Bojongsari
            L.marker([bojongsari.lat, bojongsari.lon]).addTo(map).bindPopup("Bojongsari").openPopup();

            // Menambahkan lingkaran radius untuk Karang Satria dan Bojongsari
            L.circle([karangSatria.lat, karangSatria.lon], {
                color: 'green',
                fillColor: 'green',
                fillOpacity: 0.3,
                radius: karangSatria.radius
            }).addTo(map).bindPopup("Radius Karang Satria");

            L.circle([bojongsari.lat, bojongsari.lon], {
                color: 'blue',
                fillColor: 'blue',
                fillOpacity: 0.3,
                radius: bojongsari.radius
            }).addTo(map).bindPopup("Radius Bojongsari");

            // Menangani geolocation pengguna
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    userLat = position.coords.latitude;
                    userLon = position.coords.longitude;

                    // Marker untuk lokasi pengguna
                    L.marker([userLat, userLon]).addTo(map).bindPopup("Lokasi Anda").openPopup();

                    var distanceToKarangSatria = map.distance([userLat, userLon], [karangSatria.lat, karangSatria.lon]);
                    var distanceToBojongsari = map.distance([userLat, userLon], [bojongsari.lat, bojongsari.lon]);

                    if (distanceToKarangSatria <= karangSatria.radius) {
                        // Jika dalam radius Karang Satria, tampilkan modal konfirmasi
                        showModal('karangsatria');
                    } else if (distanceToBojongsari <= bojongsari.radius) {
                        // Jika dalam radius Bojongsari, tampilkan modal konfirmasi
                        showModal('bojongsari');
                    } else {
                        alert("Anda tidak berada dalam radius lokasi yang tersedia.");
                    }
                }, function(error) {
                    alert("Tidak dapat mengakses lokasi. Pastikan izin lokasi sudah diberikan.");
                });
            } else {
                alert("Geolocation tidak didukung oleh browser ini.");
            }
        }

        // Function to show the confirmation modal
        function showModal(location) {
            // Show the modal
            $('#locationModal').modal('show');

            // Set up the redirect action based on the location
            $('#confirmRedirect').click(function() {
                if (location === 'karangsatria') {
                    window.location.href = 'antrian_ks.php';
                } else if (location === 'bojongsari') {
                    window.location.href = 'antrian_bjs.php';
                }
            });
        }

        window.onload = initMap;
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
