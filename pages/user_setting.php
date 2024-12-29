<?php
session_start();
include('../data/db.php');

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Jika belum login, arahkan ke login
    exit();
}

// Cek apakah user sudah login dan memiliki role yang tepat
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'superadmin')) {
    // Jika tidak memiliki role admin atau superadmin, redirect ke halaman index
    header("Location: index.php");
    exit;
}

// Ambil data pengguna dari database
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Jika data pengguna tidak ditemukan
if (!$user) {
    echo "Data pengguna tidak ditemukan.";
    exit();
}

mysqli_close($conn);

include '../data/db.php';

// Tambah pengguna baru
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $name = $_POST['name'];
    $role = $_POST['role'];

    // Cek apakah yang mengedit adalah superadmin
    if ($_SESSION['role'] != 'superadmin') {
        echo "<script>alert('Maaf, hanya superadmin yang bisa menambah pengguna.'); window.location.href='user_setting.php';</script>";
        exit();
    }

    $sql = "INSERT INTO users (username, password, email, name, role) VALUES ('$username', '$password', '$email', '$name', '$role')";
    mysqli_query($conn, $sql);
    header("Location: user_setting.php");
    exit();
}

// Hapus pengguna (Superadmin hanya bisa menghapus admin atau user)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "SELECT role FROM users WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $userToDelete = mysqli_fetch_assoc($result);

    // Pastikan superadmin tidak menghapus dirinya sendiri atau pengguna lain dengan role superadmin
    if ($_SESSION['role'] == 'superadmin' && $userToDelete['role'] != 'superadmin') {
        $sql = "DELETE FROM users WHERE id = $id";
        mysqli_query($conn, $sql);
        header("Location: user_setting.php");
        exit();
    } elseif ($_SESSION['role'] != 'superadmin') {
        echo "<script>alert('Maaf, Anda bukan superadmin. Aksi ini tidak diizinkan.'); window.location.href='user_setting.php';</script>";
        exit();
    }
}

// Edit pengguna
if (isset($_POST['edit_user'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Cek apakah yang mengedit adalah superadmin
    if ($_SESSION['role'] != 'superadmin') {
        echo "<script>alert('Maaf, hanya superadmin yang bisa mengedit pengguna.'); window.location.href='user_setting.php';</script>";
        exit();
    }

    // Update password jika diubah
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET username = '$username', email = '$email', name = '$name', role = '$role', password = '$password' WHERE id = $id";
    } else {
        $sql = "UPDATE users SET username = '$username', email = '$email', name = '$name', role = '$role' WHERE id = $id";
    }
    mysqli_query($conn, $sql);
    header("Location: user_setting.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Include Header -->
    <?php include('../includes/header_setting.php'); ?>

<div class="container mt-5 mb-5">

    <!-- Form Tambah Pengguna -->
    <form method="POST" class="mb-4">
        <h2>Tambah Pengguna Baru</h2>
        <div class="row">
            <div class="col-12 col-md-2">
                <div class="mb-3">
                    <label for="username" class="form-label fw-bold">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Username" required>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Nama</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Nama" required>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <div class="mb-3">
                    <label for="role" class="form-label fw-bold">Role</label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                        <option value="superadmin">Super Admin</option>
                    </select>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" name="add_user" class="btn btn-primary w-100">Tambah</button>
            </div>
        </div>
    </form>

    <h2>Pengaturan Pengguna</h2>
    <!-- Tabel Daftar Pengguna -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Nama</th>
                    <th>Role</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM users";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['username']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['role']}</td>
                        <td>
                            <div class='d-flex justify-content-center'>
                                <a href='user_setting.php?delete={$row['id']}' class='btn btn-danger btn-sm me-2'>Hapus</a>
                                <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editUserModal{$row['id']}'>Edit</button>
                            </div>
                        </td>
                    </tr>";

                    // Modal Edit
                    echo "
                    <div class='modal fade' id='editUserModal{$row['id']}' tabindex='-1'>
                        <div class='modal-dialog'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title'>Edit Pengguna</h5>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                </div>
                                <form method='POST'>
                                    <div class='modal-body'>
                                        <input type='hidden' name='id' value='{$row['id']}'>
                                        <div class='mb-3'>
                                            <label>Username</label>
                                            <input type='text' name='username' class='form-control' value='{$row['username']}' required>
                                        </div>
                                        <div class='mb-3'>
                                            <label>Email</label>
                                            <input type='email' name='email' class='form-control' value='{$row['email']}' required>
                                        </div>
                                        <div class='mb-3'>
                                            <label>Nama</label>
                                            <input type='text' name='name' class='form-control' value='{$row['name']}' required>
                                        </div>
                                        <div class='mb-3'>
                                            <label>Role</label>
                                            <select name='role' class='form-select' required>
                                                <option value='user' " . ($row['role'] == 'user' ? 'selected' : '') . ">User</option>
                                                <option value='admin' " . ($row['role'] == 'admin' ? 'selected' : '') . ">Admin</option>
                                                <option value='superadmin' " . ($row['role'] == 'superadmin' ? 'selected' : '') . ">Super Admin</option>
                                            </select>
                                        </div>
                                        <div class='mb-3'>
                                            <label>Password Baru</label>
                                            <input type='password' name='password' class='form-control' placeholder='Kosongkan jika tidak ingin mengubah password'>
                                        </div>
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='submit' name='edit_user' class='btn btn-primary'>Simpan</button>
                                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    ";
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<!-- Tambahkan script untuk responsivitas -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
