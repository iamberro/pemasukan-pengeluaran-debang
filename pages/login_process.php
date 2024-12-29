<?php
session_start();
include('../data/db.php'); // Pastikan kamu sudah menghubungkan ke database

// Menangkap data dari form login
$username = $_POST['username'];
$password = $_POST['password'];

// Query untuk memeriksa username dan password
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        // Set session untuk username dan role
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Menyimpan role pengguna dalam session

        // Jika peran adalah admin, arahkan ke halaman admin, jika tidak, ke halaman utama
        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        // Jika password salah
        $_SESSION['error'] = "Username atau Password salah!";
        header("Location: login.php");
        exit;
    }
} else {
    // Jika username tidak ditemukan
    $_SESSION['error'] = "Username tidak ditemukan!";
    header("Location: login.php");
    exit;
}

mysqli_close($conn); // Menutup koneksi ke database
?>
