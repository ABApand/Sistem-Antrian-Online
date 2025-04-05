<?php
session_start();

// Set session variable indicating user came from index.php
$_SESSION['from_index'] = true;

include 'config/database.php';

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
            height: 300px;
            width: 100%;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .container {
            max-width: 900px;
            margin-top: 50px;
        }
        .btn-custom {
            background-color: #f39c12;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #e67e22;
            cursor: pointer;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            font-family: 'Poppins', sans-serif;
        }
        .alert-custom {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ecf0f1;
        }
        .modal-content {
            border-radius: 8px;
        }
        .modal-header, .modal-footer {
            background-color: #2980b9;
            color: white;
        }
        .modal-body {
            background-color: #ecf0f1;
        }

        /* CSS for the loading spinner */
        .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.25em;
        }

        .spinner-container {
            display: none; /* Initially hidden */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 class="text-primary">Anda harus berada Di Toko</h2>
            <p class="text-muted">Temukan lokasi terdekat dan ambil nomor antrian dengan mudah!</p>
            <p class="text-warning">Izinkan Permintaan Akses Lokasi!</p>
        </div>

        <div id="map"></div> <!-- Peta akan muncul di sini -->

        <div class="text-center">
            <button class="btn btn-custom" onclick="getUserLocation()">Cek Lokasi Saya</button>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div class="spinner-container" id="spinnerContainer">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
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
        }

        function getUserLocation() {
    // Show loading spinner
    document.getElementById("spinnerContainer").style.display = "block";

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            userLat = position.coords.latitude;
            userLon = position.coords.longitude;

            // Hide loading spinner
            document.getElementById("spinnerContainer").style.display = "none";

            // Marker untuk lokasi pengguna
            L.marker([userLat, userLon]).addTo(map).bindPopup("Lokasi Anda").openPopup();

            var distanceToKarangSatria = map.distance([userLat, userLon], [karangSatria.lat, karangSatria.lon]);
            var distanceToBojongsari = map.distance([userLat, userLon], [bojongsari.lat, bojongsari.lon]);

            // If the user is within the radius, zoom and center on the user
            if (distanceToKarangSatria <= karangSatria.radius) {
                map.setView([userLat, userLon], 15); // Center and zoom in on the user's location
                showModal('karangsatria');
                // Store the valid location in the session
                sessionStorage.setItem('validLocation', 'karangsatria');
            } else if (distanceToBojongsari <= bojongsari.radius) {
                map.setView([userLat, userLon], 15); // Center and zoom in on the user's location
                showModal('bojongsari');
                // Store the valid location in the session
                sessionStorage.setItem('validLocation', 'bojongsari');
            } else {
                alert("Anda tidak berada dalam radius lokasi yang tersedia.");
                sessionStorage.removeItem('validLocation');
            }
        }, function(error) {
            // Hide loading spinner on error
            document.getElementById("spinnerContainer").style.display = "none";
            alert("Tidak dapat mengakses lokasi. Pastikan izin lokasi sudah diberikan.");
        });
    } else {
        // Hide loading spinner if geolocation is not supported
        document.getElementById("spinnerContainer").style.display = "none";
        alert("Geolocation tidak didukung oleh browser ini.");
    }
}


// Function to show the confirmation modal with updated text
function showModal(location) {
    let locationName = "";
    if (location === 'karangsatria') {
        locationName = "Karang Satria";
    } else if (location === 'bojongsari') {
        locationName = "Bojongsari";
    }

    // Update the modal content dynamically based on the location
    $('#locationModal .modal-body p').text(`Anda saat ini berada di lokasi ${locationName}. Apakah Anda ingin melanjutkan ke lokasi ini?`);

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
// Function to set the location in PHP session
function setLocationInSession(location) {
            // Make an AJAX request to set the location in session
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "set_location.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("location=" + location);
        }


        window.onload = initMap;
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
