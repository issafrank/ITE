<?php
// db_connect.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nordlich";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    // In a real API, you'd send a JSON error, but for an include, dying is okay.
    die("Connection failed: " . $conn->connect_error);
}
