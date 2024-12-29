<?php
include '../data/db.php'; // Menghubungkan ke database

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $kategori = $_POST['kategori'];
    $jenis = $_POST['jenis'];
    $lokasi = $_POST['lokasi'];

    // Masukkan data ke dalam tabel settings
    $sql = "INSERT INTO settings (kategori, jenis, lokasi) VALUES ('$kategori', '$jenis', '$lokasi')";
    
    if (mysqli_query($conn, $sql)) {
        // Redirect ke halaman pengaturan setelah berhasil
        header("Location: setting_details.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Tutup koneksi
mysqli_close($conn);
?>
