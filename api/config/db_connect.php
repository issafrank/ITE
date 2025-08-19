<?php
// db_connect.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nordlich";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Remove the JSON header since this is an include file
