<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: pages/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="../css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script> <!-- Include Data Labels Plugin -->
</head>
<body>
  <!-- Include Header -->
  <?php include('../includes/header.php'); ?>
  <?php include('../data/db.php'); ?>

  <!-- Main Content -->
  <div class="container mt-5">
    <h1>Selamat datang di Debang Tracking,<?php echo ucwords(strtolower($_SESSION['username'])) ?>!</h1>
    <p>Jangan Sampai Besar Pasak dari pada Tiang ~</p>

    <!-- Kotak-kotak untuk konten -->

<!-- Report Section -->
<div class="row">
  <div class="col-md-12">
    <a href="report.php" class="btn btn-success mb-3">Report</a>
    <div class="box p-3 mb-4" style="position: relative;">
      <h3>Report</h3>

      <!-- Tampilkan Total Pemasukan dan Pengeluaran -->
      <p><strong>Pemasukan Bulan Ini:</strong> Rp 
        <?php 
          // Ambil total pemasukan bulan ini
          $month = date('m');
          $year = date('Y');
          $sqlPemasukan = "SELECT SUM(total) AS total_pemasukan FROM pemasukan WHERE MONTH(tanggal) = '$month' AND YEAR(tanggal) = '$year'";
          $resultPemasukan = mysqli_query($conn, $sqlPemasukan);
          $rowPemasukan = mysqli_fetch_assoc($resultPemasukan);
          echo number_format($rowPemasukan['total_pemasukan'] ?: 0, 0, ',', '.');
        ?>
      </p>

      <p><strong>Pengeluaran Bulan Ini:</strong> Rp 
        <?php 
          // Ambil total pengeluaran bulan ini
          $sqlPengeluaran = "SELECT SUM(total) AS total_pengeluaran FROM pengeluaran WHERE MONTH(tanggal) = '$month' AND YEAR(tanggal) = '$year'";
          $resultPengeluaran = mysqli_query($conn, $sqlPengeluaran);
          $rowPengeluaran = mysqli_fetch_assoc($resultPengeluaran);
          echo number_format($rowPengeluaran['total_pengeluaran'] ?: 0, 0, ',', '.');
        ?>
      </p>

      <!-- Hitung Selisih Pemasukan dan Pengeluaran -->
      <p><strong>Selisih :</strong> Rp 
        <?php
          $totalPemasukan = $rowPemasukan['total_pemasukan'] ?: 0;
          $totalPengeluaran = $rowPengeluaran['total_pengeluaran'] ?: 0;
          $selisih = $totalPemasukan - $totalPengeluaran;

          // Tentukan warna berdasarkan selisih
          if ($selisih >= 0) {
            echo '<span style="color:blue;">' . number_format($selisih, 0, ',', '.') . '</span>';
          } else {
            echo '<span style="color:red;">' . "- " . number_format(abs($selisih), 0, ',', '.')  . '</span>';
          }
        ?>
      </p>

      <!-- Grafik Pie di Pojok Kanan -->
      <div class="chart-container" style="position: absolute; top: 20px; right: 20px; width: 175px; height: 175px;">
        <canvas id="pieChart"></canvas>
      </div>
    </div>
  </div>
</div>

    <div class="row">
      <div class="col-md-6">
        <!-- Tombol di atas kiri kotak 1 -->
        <a href="pemasukan.php" class="btn btn-primary mb-3">Pemasukan</a>
        <div class="box p-3 mb-4">
          <h3>Total Pemasukan</h3>
          <p><strong>Hari Ini:</strong> 
            <?php
            $today = date('Y-m-d'); // Format hari ini
            $sql = "SELECT SUM(total) AS total_pemasukan FROM pemasukan WHERE DATE(tanggal) = '$today'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $totalPemasukanHariIni = $row['total_pemasukan'];
            echo "Rp " . number_format($totalPemasukanHariIni, 0, ',', '.');
            ?>
          </p>
          <p><strong>Bulan Ini:</strong> 
            <?php
            $month = date('m'); // Bulan ini
            $year = date('Y'); // Tahun ini
            $sql = "SELECT SUM(total) AS total_pemasukan FROM pemasukan WHERE MONTH(tanggal) = '$month' AND YEAR(tanggal) = '$year'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $totalPemasukanBulanIni = $row['total_pemasukan'];
            echo "Rp " . number_format($totalPemasukanBulanIni, 0, ',', '.');
            ?>
          </p>
          <p><strong>Tahun Ini:</strong> 
            <?php
            $sql = "SELECT SUM(total) AS total_pemasukan FROM pemasukan WHERE YEAR(tanggal) = '$year'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $totalPemasukanTahunIni = $row['total_pemasukan'];
            echo "Rp " . number_format($totalPemasukanTahunIni, 0, ',', '.');
            ?>
          </p>

          <!-- Grafik Batang Pemasukan -->
          <canvas id="pemasukanChartBar"></canvas>
        </div>
      </div>

      <div class="col-md-6">
        <!-- Tombol di atas kiri kotak 2 -->
        <a href="pengeluaran.php" class="btn btn-danger mb-3">Pengeluaran</a>
        <div class="box p-3 mb-4">
          <h3>Total Pengeluaran</h3>
          <p><strong>Hari Ini:</strong> 
            <?php
            $sql = "SELECT SUM(total) AS total_pengeluaran FROM pengeluaran WHERE DATE(tanggal) = '$today'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $totalPengeluaranHariIni = $row['total_pengeluaran'];
            echo "Rp " . number_format($totalPengeluaranHariIni, 0, ',', '.');
            ?>
          </p>
          <p><strong>Bulan Ini:</strong> 
            <?php
            $sql = "SELECT SUM(total) AS total_pengeluaran FROM pengeluaran WHERE MONTH(tanggal) = '$month' AND YEAR(tanggal) = '$year'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $totalPengeluaranBulanIni = $row['total_pengeluaran'];
            echo "Rp " . number_format($totalPengeluaranBulanIni, 0, ',', '.');
            ?>
          </p>
          <p><strong>Tahun Ini:</strong> 
            <?php
            $sql = "SELECT SUM(total) AS total_pengeluaran FROM pengeluaran WHERE YEAR(tanggal) = '$year'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $totalPengeluaranTahunIni = $row['total_pengeluaran'];
            echo "Rp " . number_format($totalPengeluaranTahunIni, 0, ',', '.');
            ?>
          </p>

          <!-- Grafik Batang Pengeluaran -->
          <canvas id="pengeluaranChartBar"></canvas>
        </div>
      </div>
    </div>

    <div class="row">
  <div class="col-md-12" id="history_bulanan">
    <a href="history.php" class="btn btn-info mb-3">History</a>
    <div class="box p-3 mb-4">
      <h3>History Bulan Ini</h3>
      
      <?php
      // Query untuk menghitung total data
      $sqlCountPemasukan = "SELECT COUNT(*) AS total FROM pemasukan 
                            WHERE MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE())";
      $sqlCountPengeluaran = "SELECT COUNT(*) AS total FROM pengeluaran 
                             WHERE MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE())";
      
      $resultCountPemasukan = mysqli_query($conn, $sqlCountPemasukan);
      $resultCountPengeluaran = mysqli_query($conn, $sqlCountPengeluaran);

      $countPemasukan = mysqli_fetch_assoc($resultCountPemasukan)['total'];
      $countPengeluaran = mysqli_fetch_assoc($resultCountPengeluaran)['total'];
      
      $totalRows = $countPemasukan + $countPengeluaran; // Total jumlah data dari kedua tabel
      $rowsPerPage = 10; // Jumlah baris per halaman
      $totalPages = ceil($totalRows / $rowsPerPage); // Total halaman

      // Menentukan halaman saat ini
      $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
      if ($currentPage < 1) $currentPage = 1;
      if ($currentPage > $totalPages) $currentPage = $totalPages;

      // Menentukan offset untuk query
      $offset = ($currentPage - 1) * $rowsPerPage;
      ?>

      <p>Total Data: <?php echo $totalRows; ?></p> <!-- Menampilkan jumlah total data -->
      
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Input</th>
              <th>Kategori</th>
              <th>Jenis</th>
              <th>Jumlah</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Query untuk mengambil data dengan pagination
            $sql = "SELECT tanggal, 'Pemasukan' AS input, kategori AS keterangan, jenis, total 
                    FROM pemasukan 
                    WHERE MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE()) 
                    UNION ALL
                    SELECT tanggal, 'Pengeluaran' AS input, kategori, jenis, total 
                    FROM pengeluaran 
                    WHERE MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE()) 
                    ORDER BY tanggal DESC 
                    LIMIT $offset, $rowsPerPage";
            $result = mysqli_query($conn, $sql);

            // Cek jika ada data
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                // Format tanggal
                $formattedDate = date('d-m-Y', strtotime($row['tanggal']));

                // Tentukan warna berdasarkan input
                $colorClass = ($row['input'] == 'Pemasukan') ? 'text-primary' : 'text-danger'; // Biru untuk Pemasukan, Merah untuk Pengeluaran

                echo "<tr>
                        <td>" . $formattedDate . "</td>
                        <td class='$colorClass'>" . $row['input'] . "</td>
                        <td>" . $row['keterangan'] . "</td>
                        <td>" . $row['jenis'] . "</td>
                        <td>Rp " . number_format($row['total'], 0, ',', '.') . "</td>
                      </tr>";
              }
            } else {
              // Jika tidak ada data
              echo "<tr><td colspan='5' class='text-center'>Tidak ada data yang tersedia</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <!-- Tombol First dan Previous -->
        <?php if ($currentPage > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=1#history_bulanan">First</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>#history_bulanan">Previous</a>
            </li>
        <?php endif; ?>

        <!-- Nomor Halaman -->
        <?php 
        // Rentang halaman yang ditampilkan (opsional untuk estetika pagination)
        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages, $currentPage + 2);

        for ($page = $startPage; $page <= $endPage; $page++): 
        ?>
            <li class="page-item <?php echo ($page == $currentPage) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page; ?>#history_bulanan"><?php echo $page; ?></a>
            </li>
        <?php endfor; ?>

        <!-- Tombol Next dan Last -->
        <?php if ($currentPage < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>#history_bulanan">Next</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $totalPages; ?>#history_bulanan">Last</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>


    </div>
  </div>
</div>
  <!-- Include Footer -->
  <?php include('../includes/footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // --------------------------------------------- Grafik Pemasukan
var ctxPemasukan = document.getElementById('pemasukanChartBar').getContext('2d');
var pemasukanChartBar = new Chart(ctxPemasukan, {
  type: 'bar',
  data: {
    labels: ['Hari Ini', 'Bulan Ini', 'Tahun Ini'],
    datasets: [{
      label: 'Total Pemasukan',
      data: [
        <?php echo isset($totalPemasukanHariIni) ? $totalPemasukanHariIni : 0; ?>, 
        <?php echo isset($totalPemasukanBulanIni) ? $totalPemasukanBulanIni : 0; ?>, 
        <?php echo isset($totalPemasukanTahunIni) ? $totalPemasukanTahunIni : 0; ?>
      ],
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
          return 'Rp ' + value.toLocaleString(); // Format angka dengan format lokal
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          callback: function(value) {
            return 'Rp ' + value.toLocaleString(); // Format angka dengan format lokal
          }
        }
      }
    }
  },
  plugins: [ChartDataLabels] // Enable plugin
});


 // --------------------------------------------- Grafik Pie

// Ambil data dari PHP (total pemasukan dan pengeluaran)
var totalPemasukan = <?php echo $rowPemasukan['total_pemasukan'] ?: 0; ?>;
var totalPengeluaran = <?php echo $rowPengeluaran['total_pengeluaran'] ?: 0; ?>;

// Data untuk grafik pie
var data = {
  labels: ['Pemasukan', 'Pengeluaran'],
  datasets: [{
    data: [totalPemasukan, totalPengeluaran],
    backgroundColor: ['#4CAF50', '#F44336'],
    hoverBackgroundColor: ['#45a049', '#e53935']
  }]
};

// Konfigurasi grafik
var options = {
  responsive: true,
  plugins: {
    datalabels: {
      display: true,  // Memastikan data label ditampilkan
      color: 'black',
      font: {
        weight: 'bold',
        size: 10
      },
      formatter: function(value, context) {
        return 'Rp ' + value.toLocaleString('id-ID');
      },
      anchor: function(context) {
        // Tentukan posisi anchor, apakah di atas atau di bawah
        return context.dataIndex === 0 ? 'end' : 'start';  // Pemasukan di atas, Pengeluaran di bawah
      },
      align: function(context) {
        // Sesuaikan posisi label
        return context.dataIndex === 0 ? 'top' : 'bottom';  // Pemasukan di atas, Pengeluaran di bawah
      }
    },
    tooltip: {
      callbacks: {
        label: function(tooltipItem) {
          return 'Rp ' + tooltipItem.raw.toLocaleString('id-ID');
        }
      }
    }
  }
};

// Membuat grafik pie
var ctx = document.getElementById('pieChart').getContext('2d');
var myPieChart = new Chart(ctx, {
  type: 'pie',
  data: data,
  options: options,
  plugins: [ChartDataLabels] // Enable plugin
});

//  // --------------------------------------------- Grafik Pengeluaran
var ctxPengeluaran = document.getElementById('pengeluaranChartBar').getContext('2d');
var pengeluaranChartBar = new Chart(ctxPengeluaran, {
  type: 'bar',
  data: {
    labels: ['Hari Ini', 'Bulan Ini', 'Tahun Ini'],
    datasets: [{
      label: 'Total Pengeluaran',
      data: [
        <?php echo isset($totalPengeluaranHariIni) ? $totalPengeluaranHariIni : 0; ?>,
        <?php echo isset($totalPengeluaranBulanIni) ? $totalPengeluaranBulanIni : 0; ?>,
        <?php echo isset($totalPengeluaranTahunIni) ? $totalPengeluaranTahunIni : 0; ?>
      ],
      backgroundColor: 'rgba(255, 99, 132, 0.2)',
      borderColor: 'rgb(255, 99, 132)',
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
          return 'Rp ' + value.toLocaleString(); // Format angka dengan format lokal
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          callback: function(value) {
            return 'Rp ' + value.toLocaleString(); // Format angka dengan format lokal
          }
        }
      }
    }
  },
  plugins: [ChartDataLabels] // Enable plugin
});
  </script>
</body>
</html>
