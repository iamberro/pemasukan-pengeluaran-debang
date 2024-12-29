<?php
// Koneksi ke database
include('../data/db.php');

// Mengambil ID dari URL
$id = $_GET['id'];

// Query untuk mengambil data berdasarkan ID
$sql = "SELECT * FROM pemasukan WHERE id = '$id'";
$result = mysqli_query($conn, $sql);

// Jika data ditemukan
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "Data tidak ditemukan!";
    exit;
}

// Jika tombol submit ditekan, lakukan update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $kategori = $_POST['kategori'];
    $jenis = $_POST['jenis'];
    $lokasi = $_POST['lokasi'];
    $nominal = $_POST['nominal'];
    $qty = $_POST['qty'];
    $total = $_POST['total'];

    // Query untuk update data
    $update_sql = "UPDATE pemasukan SET tanggal = '$tanggal', kategori = '$kategori', jenis = '$jenis', lokasi = '$lokasi', nominal = '$nominal', qty = '$qty', total = '$total' WHERE id = '$id'";

    if (mysqli_query($conn, $update_sql)) {
        echo "Data berhasil diupdate!";
        header('Location: history.php'); // Redirect ke halaman history setelah berhasil update
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pemasukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3>Edit Data Pemasukan</h3>
        <form method="POST">
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo $row['tanggal']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <input type="text" class="form-control" id="kategori" name="kategori" value="<?php echo $row['kategori']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="jenis" class="form-label">Jenis</label>
                <input type="text" class="form-control" id="jenis" name="jenis" value="<?php echo $row['jenis']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="lokasi" class="form-label">Lokasi</label>
                <input type="text" class="form-control" id="lokasi" name="lokasi" value="<?php echo $row['lokasi']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="nominal" class="form-label">Nominal</label>
                <input type="number" class="form-control" id="nominal" name="nominal" value="<?php echo $row['nominal']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="qty" class="form-label">Qty</label>
                <input type="number" class="form-control" id="qty" name="qty" value="<?php echo $row['qty']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="total" class="form-label">Total</label>
                <input type="number" class="form-control" id="total" name="total" value="<?php echo $row['total']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>
