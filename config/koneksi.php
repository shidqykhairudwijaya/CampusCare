<?php
// Ubah localhost menjadi 127.0.0.1
$host = "localhost"; 
$user = "root";
$pass = "";
$db   = "campuscare";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi Database Gagal: " . $conn->connect_error);
}
?>
