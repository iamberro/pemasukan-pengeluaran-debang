<?php
session_start();

// Cek apakah user sudah login dan memiliki role yang tepat
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'superadmin')) {
  // Tampilkan alert jika role tidak sesuai
  echo "<script>alert('Maaf, Anda tidak memiliki akses ke halaman ini.'); window.location.href='index.php';</script>";
  exit();
}

include('../data/db.php');

// Menambahkan kategori baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_kategori') {
  $kategori = $_POST['kategori'];
  $sql = "INSERT INTO settings (kategori, jenis, lokasi) VALUES ('$kategori', NULL, NULL)";
  mysqli_query($conn, $sql);
}

// Menambahkan jenis baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_jenis') {
  $jenis = $_POST['jenis'];
  $sql = "INSERT INTO settings (kategori, jenis, lokasi) VALUES (NULL, '$jenis', NULL)";
  mysqli_query($conn, $sql);
}

// Menambahkan lokasi baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_lokasi') {
  $lokasi = $_POST['lokasi'];
  $sql = "INSERT INTO settings (kategori, jenis, lokasi) VALUES (NULL, NULL, '$lokasi')";
  mysqli_query($conn, $sql);
}


// Hapus kategori
if (isset($_GET['delete_kategori'])) {
    $id = $_GET['delete_kategori'];
    $sql = "DELETE FROM settings WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: setting_details.php");
}

// Hapus jenis
if (isset($_GET['delete_jenis'])) {
    $id = $_GET['delete_jenis'];
    $sql = "DELETE FROM settings WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: setting_details.php");
}

// Hapus lokasi
if (isset($_GET['delete_lokasi'])) {
    $id = $_GET['delete_lokasi'];
    $sql = "DELETE FROM settings WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: setting_details.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setting Details</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Include Header -->
    <?php include('../includes/header_setting.php'); ?>

    <div class="container mt-5">
        <h3>Setting Details</h3>

        <!-- Bagian Kategori -->
        <h4>Manage Kategori</h4>
        <form action="setting_details.php" method="POST">
            <input type="hidden" name="action" value="add_kategori">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Kategori" name="kategori" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Tambah Kategori</button>
        </form>
        
        <h5 class="mt-4">Daftar Kategori</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM settings WHERE kategori IS NOT NULL";
                $result = mysqli_query($conn, $sql);
                $no = 1;

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$no}</td>
                            <td>{$row['kategori']}</td>
                            <td>
                                <a href='setting_details.php?delete_kategori={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                            </td>
                          </tr>";
                    $no++;
                }
                ?>
            </tbody>
        </table>

        <!-- hr class="mt-5">

        <Bagian Jenis -->
        <!--h4>Manage Jenis</h4>
        <form action="setting_details.php" method="POST">
            <input type="hidden" name="action" value="add_jenis">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Jenis" name="jenis" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Tambah Jenis</button>
        </form>
        
        <h5 class="mt-4">Daftar Jenis</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody -->
                <?php
                $sql = "SELECT * FROM settings WHERE jenis IS NOT NULL";
                $result = mysqli_query($conn, $sql);
                $no = 1;

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$no}</td>
                            <td>{$row['jenis']}</td>
                            <td>
                                <a href='setting_details.php?delete_jenis={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                            </td>
                          </tr>";
                    $no++;
                }
                ?>
            </tbody>
        </table>

        <!-- hr class="mt-5">

        < Bagian Lokasi -->
        <!--h4>Manage Lokasi</h4>
        <form action="setting_details.php" method="POST">
            <input type="hidden" name="action" value="add_lokasi">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Lokasi" name="lokasi" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Tambah Lokasi</button>
        </form>

        <h5 class="mt-4">Daftar Lokasi</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Lokasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody -->
                <?php
                $sql = "SELECT * FROM settings WHERE lokasi IS NOT NULL";
                $result = mysqli_query($conn, $sql);
                $no = 1;

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$no}</td>
                            <td>{$row['lokasi']}</td>
                            <td>
                                <a href='setting_details.php?delete_lokasi={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                            </td>
                          </tr>";
                    $no++;
                }
                ?>
            </tbody>
        </table>

    </div>

    <!-- Include Footer -->
    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
