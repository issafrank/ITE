<?php
// db_connect.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nordlich";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    // For API calls, it's better to send a JSON error, but this works
    die("Connection failed: " . $conn->connect_error);
}
?>