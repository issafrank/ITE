<?php
$host = 'sql12.freesqldatabase.com'; // Remote host
$user = 'sql12795296';               // DB user
$pass = 'n9BvYA57Lc';                // DB password
$dbname = 'sql12795296';             // DB name
$port = 3306;                        // Port

$conn = new mysqli($host, $user, $pass, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
} 
?>
