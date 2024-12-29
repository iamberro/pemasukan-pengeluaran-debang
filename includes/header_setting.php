<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Header Example</title>
  <link rel="stylesheet" href="../css/style.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <header class="bg-light py-3">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <!-- Logo -->
        <a href="index.php">
          <img src="../assets/images/logo/debang.png" alt="Logo" style="width: 150px; height: auto;">
        </a>

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light d-none d-md-block">
          <!-- Tombol Hamburger -->
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <!-- Navbar Menu -->
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                <a href="index.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">Home</a>
              </li>
              <li class="nav-item">
                <a href="setting.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'setting.php') ? 'active' : ''; ?>">Profile Setting</a>
              </li>
              <li class="nav-item">
                <a href="user_setting.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'user_setting.php') ? 'active' : ''; ?>">User Setting</a>
              </li>
              <li class="nav-item">
                <a href="history.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'history.php') ? 'active' : ''; ?>">History Setting</a>
              </li>
              <li class="nav-item">
                <a href="setting_details.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'setting_details.php') ? 'active' : ''; ?>">Details Setting</a>
              </li>
            </ul>
          </div>
        </nav>

        <!-- Dropdown dan Tombol CTA -->
        <div class="d-flex align-items-center">
            <!-- Dropdown Pengaturan -->
            <div class="dropdown me-2">
                <button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <!-- Ikon Hamburger Menu -->
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a></li>
                    <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'setting.php') ? 'active' : ''; ?>" href="setting.php">Profile Setting</a></li>
                    <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'user_setting.php') ? 'active' : ''; ?>" href="user_setting.php">User Setting</a></li>
                    <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'history.php') ? 'active' : ''; ?>" href="history.php">History Setting</a></li>
                    <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'setting_details.php') ? 'active' : ''; ?>" href="setting_details.php">Details Setting</a></li>
                </ul>
            </div>

            <!-- Dropdown Logout -->
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo ucwords(strtolower($_SESSION['username'])); ?> <!-- Nama pengguna -->
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="setting.php">Settings</a></li>
                <li><a class="dropdown-item" href="register.php">Register</a></li>
                <li><a class="dropdown-item" href="Logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Include Bootstrap JS (Bundled) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
