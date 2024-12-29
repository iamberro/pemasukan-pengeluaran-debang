<?php
session_start();
include('../data/db.php'); // Menyertakan koneksi database

// Mendapatkan bulan dan tahun untuk filter
$month = date('m');
$year = date('Y');

// Mengambil total pemasukan bulan ini
$sqlPemasukan = "SELECT SUM(total) AS total_pemasukan FROM pemasukan WHERE MONTH(tanggal) = '$month' AND YEAR(tanggal) = '$year'";
$resultPemasukan = mysqli_query($conn, $sqlPemasukan);
$rowPemasukan = mysqli_fetch_assoc($resultPemasukan);

// Mengambil total pengeluaran bulan ini
$sqlPengeluaran = "SELECT SUM(total) AS total_pengeluaran FROM pengeluaran WHERE MONTH(tanggal) = '$month' AND YEAR(tanggal) = '$year'";
$resultPengeluaran = mysqli_query($conn, $sqlPengeluaran);
$rowPengeluaran = mysqli_fetch_assoc($resultPengeluaran);

// Menghitung selisih antara pemasukan dan pengeluaran
$totalPemasukan = $rowPemasukan['total_pemasukan'] ?: 0;
$totalPengeluaran = $rowPengeluaran['total_pengeluaran'] ?: 0;
$selisih = $totalPemasukan - $totalPengeluaran;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Include Header -->
    <?php include('../includes/header.php'); ?>

    <div class="container mt-5">
        <h1 class="text-center">Laporan Pemasukan dan Pengeluaran</h1>

        <!--div class="d-flex justify-content-end mb-4">
    <a href="export_report_pdf.php" class="btn btn-danger">Download Laporan (PDF)</a>
</div-->


        <div class="row">
            <!-- Total Pemasukan Bulan Ini -->
            <div class="col-md-6">
                <div class="box p-3 mb-4">
                    <h4>Total Pemasukan Bulan Ini</h4>
                    <p>Rp <?php echo number_format($totalPemasukan, 0, ',', '.'); ?></p>
                </div>
            </div>

            <!-- Total Pengeluaran Bulan Ini -->
            <div class="col-md-6">
                <div class="box p-3 mb-4">
                    <h4>Total Pengeluaran Bulan Ini</h4>
                    <p>Rp <?php echo number_format($totalPengeluaran, 0, ',', '.'); ?></p>
                </div>
            </div>
        </div>

        <!-- Selisih Pemasukan dan Pengeluaran -->
        <div class="row">
            <div class="col-md-12">
                <div class="box p-3 mb-4">
                    <h4>Selisih Pemasukan dan Pengeluaran</h4>
                    <p>Rp 
                        <?php 
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
    <div class="col-md-12" id="history-transaksi">
        <h3>History Transaksi</h3>
        <!-- Membungkus tabel dengan class 'table-responsive' -->
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
                    // Tentukan jumlah baris per halaman
                    $rowsPerPage = 10;

                    // Hitung total data
                    $sqlCountHistory = "SELECT COUNT(*) AS total FROM (
                        SELECT tanggal FROM pemasukan 
                        WHERE MONTH(tanggal) = '$month' AND YEAR(tanggal) = '$year'
                        UNION ALL
                        SELECT tanggal FROM pengeluaran 
                        WHERE MONTH(tanggal) = '$month' AND YEAR(tanggal) = '$year'
                    ) AS total_data";
                    $resultCountHistory = mysqli_query($conn, $sqlCountHistory);
                    $totalRows = mysqli_fetch_assoc($resultCountHistory)['total'];

                    // Hitung total halaman
                    $totalPages = ceil($totalRows / $rowsPerPage);

                    // Halaman saat ini
                    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    if ($currentPage < 1) $currentPage = 1;
                    if ($currentPage > $totalPages) $currentPage = $totalPages;

                    // Offset untuk query
                    $offset = ($currentPage - 1) * $rowsPerPage;

                    // Query dengan pagination
                    $sqlHistory = "SELECT tanggal, 'Pemasukan' AS jenis, kategori, total FROM pemasukan 
                                    WHERE MONTH(tanggal) = '$month' AND YEAR(tanggal) = '$year'
                                    UNION ALL
                                    SELECT tanggal, 'Pengeluaran' AS jenis, kategori, total FROM pengeluaran 
                                    WHERE MONTH(tanggal) = '$month' AND YEAR(tanggal) = '$year'
                                    ORDER BY tanggal DESC 
                                    LIMIT $offset, $rowsPerPage";
                    $resultHistory = mysqli_query($conn, $sqlHistory);

                    // Cek jika ada data
                    if (mysqli_num_rows($resultHistory) > 0) {
                        while ($rowHistory = mysqli_fetch_assoc($resultHistory)) {
                            // Tentukan warna berdasarkan jenis transaksi
                            $colorClass = ($rowHistory['jenis'] == 'Pemasukan') ? 'text-primary' : 'text-danger';

                            // Format tanggal
                            $formattedDate = date('d-m-Y', strtotime($rowHistory['tanggal']));

                            echo "<tr>
                                    <td>" . $formattedDate . "</td>
                                    <td class='$colorClass'>" . $rowHistory['jenis'] . "</td>
                                    <td>" . $rowHistory['kategori'] . "</td>
                                    <td>Rp " . number_format($rowHistory['total'], 0, ',', '.') . "</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>Tidak ada data yang tersedia</td></tr>";
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
                <a class="page-link" href="?page=1#history-transaksi">First</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>#history-transaksi">Previous</a>
            </li>
        <?php endif; ?>

        <!-- Nomor Halaman -->
        <?php 
        // Menentukan rentang halaman
        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages, $currentPage + 2);

        for ($page = $startPage; $page <= $endPage; $page++): 
        ?>
            <li class="page-item <?php echo ($page == $currentPage) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page; ?>#history-transaksi"><?php echo $page; ?></a>
            </li>
        <?php endfor; ?>

        <!-- Tombol Next dan Last -->
        <?php if ($currentPage < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>#history-transaksi">Next</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $totalPages; ?>#history-transaksi">Last</a>
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
</body>
</html>
