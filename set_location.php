<?php
session_start();

// Check if location is sent through POST request
if (isset($_POST['location'])) {
    $_SESSION['user_location'] = $_POST['location']; // Store user location in session
}
?>
