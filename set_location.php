<?php
session_start();

// Check if the location is set via POST
if (isset($_POST['location'])) {
    $_SESSION['validLocation'] = $_POST['location'];
    echo "Location set to: " . $_POST['location'];
} else {
    echo "No location provided!";
}
?>
