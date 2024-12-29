<?php
session_start();
if (!isset($_SESSION['username'])) {
    // Jika belum login, redirect ke halaman login
    header("Location: login.php");
    exit();
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
    <h1>Selamat datang di Debang Tracking, <?php echo $_SESSION['username']; ?>!</h1>
    <p>Jangan Sampai Besar Pasak dari pada Tiang ~</p>

    <!-- Kotak-kotak untuk konten -->

<!-- Report Section -->
<div class="row">
  <div class="col-md-12">
    <a href="report.php" class="btn btn-success mb-3">Report</a>
    <div class="box p-3 mb-4">
      <h3>Report</h3>

      <!-- Tampilkan Total Pemasukan dan Pengeluaran -->
      <p><strong>Total Pemasukan Bulan Ini:</strong> Rp 
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

      <p><strong>Total Pengeluaran Bulan Ini:</strong> Rp 
        <?php 
          // Ambil total pengeluaran bulan ini
          $sqlPengeluaran = "SELECT SUM(total) AS total_pengeluaran FROM pengeluaran WHERE MONTH(tanggal) = '$month' AND YEAR(tanggal) = '$year'";
          $resultPengeluaran = mysqli_query($conn, $sqlPengeluaran);
          $rowPengeluaran = mysqli_fetch_assoc($resultPengeluaran);
          echo number_format($rowPengeluaran['total_pengeluaran'] ?: 0, 0, ',', '.');
        ?>
      </p>

      <!-- Hitung Selisih Pemasukan dan Pengeluaran -->
      <p><strong>Selisih Pemasukan dan Pengeluaran:</strong> Rp 
        <?php
          $totalPemasukan = $rowPemasukan['total_pemasukan'] ?: 0;
          $totalPengeluaran = $rowPengeluaran['total_pengeluaran'] ?: 0;
          $selisih = $totalPemasukan - $totalPengeluaran;

          if ($selisih >= 0) {
            echo number_format($selisih, 0, ',', '.') . " (Surplus)";
          } else {
            echo number_format(abs($selisih), 0, ',', '.') . " (Defisit)";
          }
        ?>
      </p>

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

    <!-- History dan Report -->
    <div class="row">
  <div class="col-md-12">
    <a href="history.php" class="btn btn-info mb-3">History</a>
    <div class="box p-3 mb-4">
      <h3>History Bulan Ini</h3>
      <!-- Tambahkan class table-responsive untuk responsivitas -->
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Jenis</th>
              <th>Kategori</th>
              <th>Jumlah</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Query untuk mengambil data pemasukan dan pengeluaran bulan ini
            $sql = "SELECT tanggal, 'Pemasukan' AS jenis, kategori AS keterangan, total 
                    FROM pemasukan 
                    WHERE MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE()) 
                    UNION 
                    SELECT tanggal, 'Pengeluaran' AS jenis, kategori, total 
                    FROM pengeluaran 
                    WHERE MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE()) 
                    ORDER BY tanggal DESC";
            $result = mysqli_query($conn, $sql);

            // Cek jika ada data
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                // Format tanggal
                $formattedDate = date('d-m-Y', strtotime($row['tanggal']));
                echo "<tr>
                        <td>" . $formattedDate . "</td>
                        <td>" . $row['jenis'] . "</td>
                        <td>" . $row['keterangan'] . "</td>
                        <td>Rp " . number_format($row['total'], 0, ',', '.') . "</td>
                      </tr>";
              }
            } else {
              // Jika tidak ada data
              echo "<tr><td colspan='4' class='text-center'>Tidak ada data yang tersedia</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

  <!-- Include Footer -->
  <?php include('../includes/footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Grafik Pemasukan
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

// Grafik Pengeluaran
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
