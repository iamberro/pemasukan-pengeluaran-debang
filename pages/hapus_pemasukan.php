<?php
include('../data/db.php');

// Cek jika id ada dalam URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus data berdasarkan ID
    $sql = "DELETE FROM pemasukan WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        // Redirect ke halaman history setelah data berhasil dihapus
        header('Location: history.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Menutup koneksi database
    mysqli_close($conn);
} else {
    echo "ID tidak ditemukan!";
}
?>
