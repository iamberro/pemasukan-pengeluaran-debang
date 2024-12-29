<?php
session_start();

// Cek apakah user sudah login dan memiliki role yang tepat
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'superadmin')) {
  // Tampilkan alert jika role tidak sesuai
  echo "<script>alert('Maaf, Anda tidak memiliki akses ke halaman ini.'); window.location.href='index.php';</script>";
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Input Pemasukan</title>
  <link rel="stylesheet" href="../css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script> <!-- Tambahkan Plugin Data Labels -->
</head>
<body>
  <!-- Include Header -->
  <?php include('../includes/header.php'); ?>

  <!-- Main Content -->
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-13 mb-4">
        <div class="box p-3 shadow">
          <h3>Input Pemasukan</h3>
          <form action="submit_pemasukan.php" method="POST">
            <div class="row mb-3">
              <div class="col-md-2">
                <input type="date" class="form-control" name="tanggal" required>
              </div>
              <div class="col-md-2">
              <div class="position-relative">
                <select class="form-control" name="kategori" required>
                  <option value="">Pilih Kategori</option>
                  <?php
                  include('../data/db.php');
                  $sql = "SELECT DISTINCT kategori FROM settings ORDER BY kategori";
                  $result = mysqli_query($conn, $sql);
                  while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['kategori'] . "'>" . $row['kategori'] . "</option>";
                  }
                  ?>
                </select>
                <i class="fas fa-chevron-down position-absolute" style="top: 50%; right: 10px; transform: translateY(-50%);"></i>
              </div>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" placeholder="Jenis" name="jenis" required>
              </div>
              <div class="col-md-2">
                <input type="text" class="form-control" placeholder="Lokasi" name="lokasi" required>
              </div>
              <div class="col-md-2">
                <input type="number" class="form-control" placeholder="Nominal" name="nominal" required>
              </div>
              <div class="col-md-2">
                <input type="number" class="form-control" placeholder="Qty" name="qty" required>
              </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Simpan</button>
          </form>
        </div>
      </div>
    </div>

    <!-- History Pemasukan -->
<div class="row mb-4">
  <div class="col-md-12" id="history-pengeluaran">
    <div class="box p-3 shadow">
      <h3>History Pemasukan</h3>
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
            </tr>
          </thead>
          <tbody>
            <?php
            include('../data/db.php');  // Koneksi ke database

            // Tentukan jumlah data per halaman
            $rowsPerPage = 10;

            // Hitung jumlah total data untuk pagination
            $sqlCount = "SELECT COUNT(*) AS total FROM pemasukan";
            $resultCount = mysqli_query($conn, $sqlCount);
            $totalRows = mysqli_fetch_assoc($resultCount)['total'];

            // Tentukan jumlah halaman
            $totalPages = ceil($totalRows / $rowsPerPage);
            $currentPage = isset($_GET['page_history']) ? (int)$_GET['page_history'] : 1;
            if ($currentPage < 1) $currentPage = 1;
            if ($currentPage > $totalPages) $currentPage = $totalPages;
            $offset = ($currentPage - 1) * $rowsPerPage;

            // Pastikan $offset adalah angka positif
            if ($offset < 0) {
              $offset = 0;
            }

            // Query dengan limit dan offset untuk pagination
            $sql = "SELECT * FROM pemasukan ORDER BY tanggal DESC LIMIT ?, ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $offset, $rowsPerPage);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
              $no = $offset + 1;
              while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>" . $no++ . "</td>
                        <td>" . $row['tanggal'] . "</td>
                        <td>" . $row['kategori'] . "</td>
                        <td>" . $row['jenis'] . "</td>
                        <td>" . $row['lokasi'] . "</td>
                        <td>" . number_format($row['nominal'], 0, ',', '.') . "</td>
                        <td>" . $row['qty'] . "</td>
                        <td>" . number_format($row['total'], 0, ',', '.') . "</td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='8' class='text-center'>Tidak ada data pemasukan.</td></tr>";
            }

            mysqli_close($conn);
            ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination History Pengeluaran -->
<?php if ($totalPages > 1): ?>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($currentPage > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page_history=1#history-pemasukan">First</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="?page_history=<?php echo $currentPage - 1; ?>#history-pemasukan">Previous</a>
                </li>
            <?php endif; ?>

            <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                <li class="page-item <?php echo ($page == $currentPage) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page_history=<?php echo $page; ?>#history-pemasukan"><?php echo $page; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page_history=<?php echo $currentPage + 1; ?>#history-pemasukan">Next</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="?page_history=<?php echo $totalPages; ?>#history-pemasukan">Last</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
        </div>
    </div>
</div>

   <!-- Report Pemasukan Bulanan -->
<div class="row mb-4">
  <div class="col-md-12" id="pemasukan-bulanan">
    <div class="box p-3 shadow">
      <h3>Report Pemasukan Bulanan</h3>
      
      <!-- Filter Tahun dan Bulan -->
      <form method="GET" action="pemasukan.php#pemasukan-bulanan">
        <div class="row mb-3">
          <div class="col-md-4">
            <select class="form-control" name="tahun" required>
              <option value="">Pilih Tahun</option>
              <?php
              // Ambil tahun dari database untuk filter
              include('../data/db.php');
              $sql = "SELECT DISTINCT YEAR(tanggal) AS tahun FROM pemasukan ORDER BY tahun DESC";
              $result = mysqli_query($conn, $sql);
              while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['tahun'] . "'>" . $row['tahun'] . "</option>";
              }
              ?>
            </select>
          </div>
          <div class="col-md-4">
            <select class="form-control" name="bulan" required>
              <option value="">Pilih Bulan</option>
              <option value="01">Januari</option>
              <option value="02">Februari</option>
              <option value="03">Maret</option>
              <option value="04">April</option>
              <option value="05">Mei</option>
              <option value="06">Juni</option>
              <option value="07">Juli</option>
              <option value="08">Agustus</option>
              <option value="09">September</option>
              <option value="10">Oktober</option>
              <option value="11">November</option>
              <option value="12">Desember</option>
            </select>
          </div>
          <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
          </div>
        </div>
      </form>

      <!-- Tabel Report Pemasukan Bulanan -->
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
            </tr>
          </thead>
          <tbody>
            <?php
            // Menangkap nilai tahun dan bulan dari form filter
            if (isset($_GET['tahun']) && isset($_GET['bulan'])) {
              $tahun = $_GET['tahun'];
              $bulan = $_GET['bulan'];
              $rowsPerPage = 10;

              // Hitung jumlah total data untuk pagination
              $sqlCount = "SELECT COUNT(*) AS total FROM pemasukan WHERE YEAR(tanggal) = '$tahun' AND MONTH(tanggal) = '$bulan'";
              $resultCount = mysqli_query($conn, $sqlCount);
              $totalRows = mysqli_fetch_assoc($resultCount)['total'];

              // Tentukan jumlah halaman
              $totalPages = ceil($totalRows / $rowsPerPage);
              $currentPage = isset($_GET['page_pemasukan']) ? (int)$_GET['page_pemasukan'] : 1;
              if ($currentPage < 1) $currentPage = 1;
              if ($currentPage > $totalPages) $currentPage = $totalPages;
              $offset = ($currentPage - 1) * $rowsPerPage;

              // Pastikan $offset adalah angka positif
              if ($offset < 0) {
                $offset = 0;
              }

              // Query dengan limit dan offset untuk pagination
              $sql = "SELECT * FROM pemasukan WHERE YEAR(tanggal) = '$tahun' AND MONTH(tanggal) = '$bulan' ORDER BY tanggal DESC LIMIT ?, ?";
              $stmt = mysqli_prepare($conn, $sql);
              mysqli_stmt_bind_param($stmt, "ii", $offset, $rowsPerPage);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);

              if ($result && mysqli_num_rows($result) > 0) {
                $no = $offset + 1;
                while ($row = mysqli_fetch_assoc($result)) {
                  echo "<tr>
                          <td>" . $no++ . "</td>
                          <td>" . $row['tanggal'] . "</td>
                          <td>" . $row['kategori'] . "</td>
                          <td>" . $row['jenis'] . "</td>
                          <td>" . $row['lokasi'] . "</td>
                          <td>" . number_format($row['nominal'], 0, ',', '.') . "</td>
                          <td>" . $row['qty'] . "</td>
                          <td>" . number_format($row['total'], 0, ',', '.') . "</td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='8' class='text-center'>Tidak ada data untuk bulan dan tahun yang dipilih.</td></tr>";
              }
            }
            ?>
          </tbody>
        </table>
      </div>

      <?php
// Ambil nilai parameter dari URL, jika ada
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$currentPage = isset($_GET['page_pemasukan']) ? $_GET['page_pemasukan'] : 1;
?>

<!-- Pagination Pengeluaran Bulanan -->
<?php if ($totalPages > 1): ?>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($currentPage > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?tahun=<?php echo $tahun; ?>&bulan=<?php echo $bulan; ?>&page_pemasukan=1#pemasukan-bulanan">First</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="?tahun=<?php echo $tahun; ?>&bulan=<?php echo $bulan; ?>&page_pemasukan=<?php echo $currentPage - 1; ?>#pemasukan-bulanan">Previous</a>
                </li>
            <?php endif; ?>

            <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                <li class="page-item <?php echo ($page == $currentPage) ? 'active' : ''; ?>">
                    <a class="page-link" href="?tahun=<?php echo $tahun; ?>&bulan=<?php echo $bulan; ?>&page_pemasukan=<?php echo $page; ?>#pemasukan-bulanan"><?php echo $page; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?tahun=<?php echo $tahun; ?>&bulan=<?php echo $bulan; ?>&page_pemasukan=<?php echo $currentPage + 1; ?>#pemasukan-bulanan">Next</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="?tahun=<?php echo $tahun; ?>&bulan=<?php echo $bulan; ?>&page_pemasukan=<?php echo $totalPages; ?>#pemasukan-bulanan">Last</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
    </div>
  </div>
</div>


    <!-- Grafik Pemasukan -->
    <div class="row mt-4">
      <div class="col-md-6">
        <div class="box p-3 shadow">
          <h4>Grafik Pemasukan Tahun <?php echo isset($tahun) ? $tahun : 'Pilih Tahun'; ?> (Line)</h4>
          <canvas id="pemasukanChartLine"></canvas>
        </div>
      </div>
      
      <!-- Grafik Batang Pemasukan -->
      <div class="col-md-6">
        <div class="box p-3 shadow">
          <h4>Grafik Pemasukan Tahun <?php echo isset($tahun) ? $tahun : 'Pilih Tahun'; ?> (Bar)</h4>
          <canvas id="pemasukanChartBar"></canvas>
        </div>
      </div>
    </div>

  </div>

  <!-- Include Footer -->
  <?php include('../includes/footer.php'); ?>

  <script>
    // Ambil data pemasukan berdasarkan tahun yang dipilih
    <?php
    if (isset($tahun)) {
      $sql = "SELECT MONTH(tanggal) AS bulan, SUM(total) AS total_pemasukan FROM pemasukan WHERE YEAR(tanggal) = '$tahun' GROUP BY MONTH(tanggal)";
      $result = mysqli_query($conn, $sql);
      $bulanData = [];
      $totalPemasukanData = [];
      
      // Array untuk nama bulan
      $bulanNama = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
      ];

      while ($row = mysqli_fetch_assoc($result)) {
        $bulan = $row['bulan'];
        $bulanData[] = $bulanNama[$bulan]; // Menambahkan nama bulan
        $totalPemasukanData[] = (int) $row['total_pemasukan'];
      }
      mysqli_close($conn);
    }
    ?>
    
    var ctxLine = document.getElementById('pemasukanChartLine').getContext('2d');
    var pemasukanChartLine = new Chart(ctxLine, {
      type: 'line',
      data: {
        labels: <?php echo json_encode($bulanData); ?>,
        datasets: [{
          label: 'Total Pemasukan',
          data: <?php echo json_encode($totalPemasukanData); ?>,
          borderColor: 'rgb(75, 192, 192)',
          tension: 0.1
        }]
      },
      options: {
        plugins: {
          datalabels: {
            color: 'black',
            font: {
              weight: 'bold'
            },
            align: 'top'
          }
        }
      }
    });

    var ctxBar = document.getElementById('pemasukanChartBar').getContext('2d');
    var pemasukanChartBar = new Chart(ctxBar, {
      type: 'bar',
      data: {
        labels: <?php echo json_encode($bulanData); ?>,
        datasets: [{
          label: 'Total Pemasukan',
          data: <?php echo json_encode($totalPemasukanData); ?>,
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgb(75, 192, 192)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          datalabels: {
            color: 'black',
            align: 'top',
            font: {
              weight: 'bold'
            },
            formatter: function(value) {
              return 'Rp ' + value.toLocaleString();
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return 'Rp ' + value.toLocaleString();
              }
            }
          }
        }
      },
      plugins: [ChartDataLabels] // Enable plugin
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
