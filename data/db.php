<?php
// Koneksi ke database
$host = "localhost";  // Ganti dengan host database kamu (misalnya localhost)
$username = "root";   // Ganti dengan username database kamu
$password = "Pradana12!";       // Ganti dengan password database kamu
$database = "sallary"; // Ganti dengan nama database kamu

// Membuat koneksi
$conn = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
