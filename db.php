<?php
$host = "localhost";
$user = "root"; // update if needed
$password = ""; // update if you use a password
$database = "serevibe"; // <--- use existing DB

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
