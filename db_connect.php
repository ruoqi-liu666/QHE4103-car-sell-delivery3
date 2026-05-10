<?php


$host     = "127.0.0.1";
$port     = 3306;
$username = "root";
$password = "";
$database = "veluxe_motors";
$socket   = "/tmp/mysql.sock"; // macOS conda-installed MySQL socket

$conn = @new mysqli($host, $username, $password, $database, $port, $socket);

if ($conn->connect_error) {
    // Fallback to TCP without socket
    $conn = new mysqli($host, $username, $password, $database, $port);
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
