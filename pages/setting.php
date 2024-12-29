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
    // Tampilkan alert jika role tidak sesuai
    echo "<script>alert('Maaf, Anda tidak memiliki akses ke halaman ini.'); window.location.href='index.php';</script>";
    exit();
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
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <!-- Include Header -->
    <?php include('../includes/header_setting.php'); ?>

    <!-- Main Content -->
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="box p-3 shadow">
                    <h3>Pengaturan Akun</h3>
                    
                    <!-- Menampilkan pesan sukses/error -->
                    <?php
                    if (isset($_SESSION['success'])) {
                        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                        unset($_SESSION['success']);
                    } elseif (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                        unset($_SESSION['error']);
                    }
                    ?>

                    <!-- Form untuk mengubah pengaturan -->
                    <form action="update_settings.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Foto Profil -->
                                <img src="../assets/images/avatars/<?php echo $user['avatar'] ? $user['avatar'] : 'default-avatar.jpg'; ?>" alt="Avatar" class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
                                <div class="mb-3">
                                    <label for="avatar" class="form-label">Foto Profil</label>
                                    <input type="file" class="form-control" id="avatar" name="avatar">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengganti foto profil.</small>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>" required>
                                </div>
                                <div class="mb-3">
                                     <label for="password" class="form-label">Password Baru</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                     <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                                </div>

                                <button type="submit" class="btn btn-primary">Update Pengaturan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Include Footer -->
    <?php include('../includes/footer.php'); ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
