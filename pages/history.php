<?php
session_start();

// Cek apakah user sudah login dan memiliki role yang tepat
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'superadmin')) {
  // Tampilkan alert jika role tidak sesuai
  echo "<script>alert('Maaf, Anda tidak memiliki akses ke halaman ini.'); window.location.href='index.php';</script>";
  exit();
}
?>

<?php
// Koneksi ke database
include('../data/db.php');

// Query untuk mengambil data pemasukan
$sql_pemasukan = "SELECT * FROM pemasukan ORDER BY tanggal DESC";
$result_pemasukan = mysqli_query($conn, $sql_pemasukan);

// Query untuk mengambil data pengeluaran
$sql_pengeluaran = "SELECT * FROM pengeluaran ORDER BY tanggal DESC";
$result_pengeluaran = mysqli_query($conn, $sql_pengeluaran);

// Menutup koneksi database
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>History Pemasukan & Pengeluaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

  <!-- Include Header -->
  <?php include('../includes/header_setting.php'); ?>

  <!-- Main Content -->
  <div class="container mt-5 mb-5">
    <!-- History Pemasukan -->
    <div class="row mb-4">
      <div class="col-md-12" id="history_masuk">
        <div class="box p-3 shadow">
          <h3>History Pemasukan</h3>

          <!-- Tabel History Pemasukan -->
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Kategori</th>
                  <th>Jenis</th>
                  <th>Lokasi</th>
                  <th>Nominal</th>
                  <th>Qty</th>
                  <th>Total</th>
                  <th>Created By</th> <!-- Kolom baru untuk username -->
                  <th>Action</th> <!-- Kolom untuk menghapus data -->
                </tr>
              </thead>
              <tbody>
                <?php
                // Batasi jumlah data per halaman
                $limit = 10;
                if (isset($_GET['page_pemasukan'])) {
                  $page = $_GET['page_pemasukan'];
                } else {
                  $page = 1;
                }
                $offset = ($page - 1) * $limit;

                // Query untuk mengambil data history pemasukan dengan batasan
                include('../data/db.php');
                $sql = "SELECT * FROM pemasukan ORDER BY tanggal DESC LIMIT $limit OFFSET $offset";
                $result_pemasukan = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result_pemasukan) > 0) {
                  $no = $offset + 1;
                  while ($row = mysqli_fetch_assoc($result_pemasukan)) {
                    echo "<tr>
                            <td>" . $no++ . "</td>
                            <td>" . $row['tanggal'] . "</td>
                            <td>" . $row['kategori'] . "</td>
                            <td>" . $row['jenis'] . "</td>
                            <td>" . $row['lokasi'] . "</td>
                            <td>" . number_format($row['nominal'], 0, ',', '.') . "</td>
                            <td>" . $row['qty'] . "</td>
                            <td>" . number_format($row['total'], 0, ',', '.') . "</td>
                            <td>" . $row['created_by'] . "</td>
                            <td>
                              <a href='edit_pemasukan.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                              <a href='hapus_pemasukan.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Hapus</a>
                            </td>
                          </tr>";
                  }
                } else {
                  echo "<tr><td colspan='10' class='text-center'>Tidak ada data pemasukan.</td></tr>";
                }

                // Menghitung total halaman
                $sql_total = "SELECT COUNT(*) AS total FROM pemasukan";
                $result_total = mysqli_query($conn, $sql_total);
                $total_row = mysqli_fetch_assoc($result_total);
                $total_pages = ceil($total_row['total'] / $limit);
                ?>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
              <!-- Tombol First -->
              <?php if ($page > 1): ?>
                <li class="page-item">
                  <a class="page-link" href="?page_pemasukan=1&history=pemasukan">First</a>
                </li>
                <li class="page-item">
                  <a class="page-link" href="?page_pemasukan=<?php echo $page - 1; ?>&history=pemasukan">Previous</a>
                </li>
              <?php endif; ?>

              <!-- Nomor Halaman -->
              <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                  <a class="page-link" href="?page_pemasukan=<?php echo $i; ?>&history=pemasukan">
                    <?php echo $i; ?>
                  </a>
                </li>
              <?php endfor; ?>

              <!-- Tombol Next -->
              <?php if ($page < $total_pages): ?>
                <li class="page-item">
                  <a class="page-link" href="?page_pemasukan=<?php echo $page + 1; ?>&history=pemasukan">Next</a>
                </li>
                <li class="page-item">
                  <a class="page-link" href="?page_pemasukan=<?php echo $total_pages; ?>&history=pemasukan">Last</a>
                </li>
              <?php endif; ?>
            </ul>
          </nav>
        </div>
      </div>
    </div>

    <!-- History Pengeluaran -->
    <div class="row mb-4">
      <div class="col-md-12" id="history-pengeluaran2">
        <div class="box p-3 shadow">
          <h3>History Pengeluaran</h3>

          <!-- Tabel History Pengeluaran -->
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Kategori</th>
                  <th>Jenis</th>
                  <th>Lokasi</th>
                  <th>Nominal</th>
                  <th>Qty</th>
                  <th>Total</th>
                  <th>Created By</th> <!-- Kolom baru untuk username -->
                  <th>Action</th> <!-- Kolom untuk menghapus data -->
                </tr>
              </thead>
              <tbody>
                <?php
                // Batasi jumlah data per halaman
                $limit = 10;
                if (isset($_GET['page_pengeluaran'])) {
                  $page = $_GET['page_pengeluaran'];
                } else {
                  $page = 1;
                }
                $offset = ($page - 1) * $limit;

                // Query untuk mengambil data history pengeluaran dengan batasan
                include('../data/db.php');
                $sql = "SELECT * FROM pengeluaran ORDER BY tanggal DESC LIMIT $limit OFFSET $offset";
                $result_pengeluaran = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result_pengeluaran) > 0) {
                  $no = $offset + 1;
                  while ($row = mysqli_fetch_assoc($result_pengeluaran)) {
                    echo "<tr>
                            <td>" . $no++ . "</td>
                            <td>" . $row['tanggal'] . "</td>
                            <td>" . $row['kategori'] . "</td>
                            <td>" . $row['jenis'] . "</td>
                            <td>" . $row['lokasi'] . "</td>
                            <td>" . number_format($row['nominal'], 0, ',', '.') . "</td>
                            <td>" . $row['qty'] . "</td>
                            <td>" . number_format($row['total'], 0, ',', '.') . "</td>
                            <td>" . $row['created_by'] . "</td>
                            <td>
                              <a href='edit_pengeluaran.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                              <a href='hapus_pengeluaran.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Hapus</a>
                            </td>
                          </tr>";
                  }
                } else {
                  echo "<tr><td colspan='10' class='text-center'>Tidak ada data pengeluaran.</td></tr>";
                }

                // Menghitung total halaman
                $sql_total = "SELECT COUNT(*) AS total FROM pengeluaran";
                $result_total = mysqli_query($conn, $sql_total);
                $total_row = mysqli_fetch_assoc($result_total);
                $total_pages = ceil($total_row['total'] / $limit);
                ?>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
              <!-- Tombol First -->
              <?php if ($page > 1): ?>
                <li class="page-item">
                  <a class="page-link" href="?#history-pengeluaran2">First</a>
                </li>
              <?php endif; ?>

              <!-- Tombol Previous -->
              <?php if ($page > 1): ?>
                <li class="page-item">
                  <a class="page-link" href="?page_pengeluaran=<?php echo $page - 1; ?>#history-pengeluaran2">Previous</a>
                </li>
              <?php endif; ?>

              <!-- Nomor Halaman -->
              <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                  <a class="page-link" href="?page_pengeluaran=<?php echo $i; ?>#history-pengeluaran2">
                    <?php echo $i; ?>
                  </a>
                </li>
              <?php endfor; ?>

              <!-- Tombol Next -->
              <?php if ($page < $total_pages): ?>
                <li class="page-item">
                  <a class="page-link" href="?page_pengeluaran=<?php echo $page + 1; ?>#history-pengeluaran2">Next</a>
                </li>
              <?php endif; ?>

              <!-- Tombol Last -->
              <?php if ($page < $total_pages): ?>
                <li class="page-item">
                  <a class="page-link" href="?page_pengeluaran=<?php echo $total_pages; ?>#history-pengeluaran2">Last</a>
                </li>
              <?php endif; ?>
            </ul>
          </nav>
        </div>
      </div>
    </div>

  </div>

  <!-- Include Footer -->
  <?php include('../includes/footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
