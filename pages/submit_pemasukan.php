<?php
session_start(); // Memulai session untuk mengambil username

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    // Jika tidak login, arahkan ke halaman login
    header("Location: login.php");
    exit;
}

include('../data/db.php');  // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $tanggal = $_POST['tanggal'];
    $kategori = $_POST['kategori'];
    $jenis = $_POST['jenis'];
    $lokasi = $_POST['lokasi'];
    $nominal = $_POST['nominal'];
    $qty = $_POST['qty'];

    // Hitung total
    $total = $nominal * $qty;

    // Ambil username dari session
    $created_by = $_SESSION['username'];

    // Query untuk menyimpan data ke database dengan menambahkan created_by
    $sql = "INSERT INTO pemasukan (tanggal, kategori, jenis, lokasi, nominal, qty, total, created_by) 
            VALUES ('$tanggal', '$kategori', '$jenis', '$lokasi', '$nominal', '$qty', '$total', '$created_by')";

    // Mengeksekusi query
    if (mysqli_query($conn, $sql)) {
        // Jika berhasil, arahkan kembali ke halaman input
        header("Location: pemasukan.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Menutup koneksi
    mysqli_close($conn);
}

?>
