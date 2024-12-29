<?php
session_start();
include('../data/db.php');

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Jika belum login, arahkan ke login
    exit();
}

// Ambil data pengguna dari session
$username = $_SESSION['username'];

// Periksa jika form telah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $avatar = $_FILES['avatar'];

    // Cek apakah password diubah
    if (!empty($password)) {
        // Password baru yang dimasukkan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql_update_password = "UPDATE users SET password = ? WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql_update_password);
        mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $username);
        mysqli_stmt_execute($stmt);
    }

    // Proses foto profil jika ada yang diupload
    if ($avatar['name']) {
        // Cek apakah file yang diupload adalah gambar
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($avatar['type'], $allowed_types)) {
            $upload_dir = "../assets/images/avatars/";
            $avatar_name = basename($avatar['name']);
            $upload_file = $upload_dir . $avatar_name;

            // Hapus foto lama jika ada
            $sql = "SELECT avatar FROM users WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_assoc($result);
            if ($user && file_exists($upload_dir . $user['avatar'])) {
                unlink($upload_dir . $user['avatar']);
            }

            // Pindahkan file yang diupload ke folder tujuan
            if (move_uploaded_file($avatar['tmp_name'], $upload_file)) {
                $sql_update_avatar = "UPDATE users SET avatar = ? WHERE username = ?";
                $stmt = mysqli_prepare($conn, $sql_update_avatar);
                mysqli_stmt_bind_param($stmt, "ss", $avatar_name, $username);
                mysqli_stmt_execute($stmt);
            }
        }
    }

    // Update email dan nama pengguna
    $sql_update_info = "UPDATE users SET email = ?, name = ? WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql_update_info);
    mysqli_stmt_bind_param($stmt, "sss", $email, $name, $username);
    mysqli_stmt_execute($stmt);

    $_SESSION['success'] = "Pengaturan berhasil diperbarui!";
    header("Location: setting.php");
    exit();
}

mysqli_close($conn);
?>
