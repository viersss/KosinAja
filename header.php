<?php
// Memulai session di file template ini agar semua halaman otomatis memiliki session
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' - ' : '' ?>KosinAja</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="glass-card">
        <nav>
            <a href="index.php" class="logo">KosinAja</a>
            <div class="nav-links">
                <a href="index.php#popular">Pilihan Kos</a>
                <a href="index.php#features">Fitur</a>
                <a href="index.php#about">Tentang</a>
                <a href="kontak.php">Kontak</a>
            </div>
            <div class="nav-auth">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-username"><?= htmlspecialchars($_SESSION['username']) ?> &#9662;</a>
                        <div class="dropdown-menu glass-card">
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'owner'): ?>
                                <a href="dashboard.php">Dashboard Saya</a>
                            <?php else: ?>
                                <a href="profile.php">Profil Saya</a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a href="logout.php">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn-secondary">Login</a>
                    <a href="register.php" class="btn-primary">Register</a>
                <?php endif; ?>
            </div>
            <!-- Hamburger menu icon -->
            <div class="hamburger-menu">&#9776;</div>
        </nav>
    </header>

    <!-- Mobile Navigation Overlay -->
    <div class="mobile-nav-overlay">
        <div class="mobile-nav-content">
            <nav class="mobile-nav-links">
                <a href="index.php#popular">Pilihan Kos</a>
                <a href="index.php#features">Fitur</a>
                <a href="index.php#about">Tentang</a>
                <a href="kontak.php">Kontak</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'owner'): ?>
                        <a href="dashboard.php">Dashboard Saya</a>
                    <?php else: ?>
                        <a href="profile.php">Profil Saya</a>
                    <?php endif; ?>
                    <a href="logout.php" class="btn-secondary">Logout</a>
                <?php else: ?>
                    <div class="mobile-auth-buttons">
                        <a href="login.php" class="btn-secondary">Login</a>
                        <a href="register.php" class="btn-primary">Register</a>
                    </div>
                <?php endif; ?>
            </nav>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
