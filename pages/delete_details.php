<?php
include '../data/db.php'; // Mengarahkan koneksi ke db.php

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM settings WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header("Location: setting_details.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
