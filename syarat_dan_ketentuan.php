<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syarat & Ketentuan - KosinAja</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">

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
                            <a href="<?= $_SESSION['role'] == 'owner' ? 'dashboard.php' : 'profile.php' ?>">Dashboard</a>
                            <a href="#">Pengaturan Akun</a>
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
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>

    <main>
        <div class="container legal-page">
            <h1 class="section-title">Syarat & Ketentuan</h1>
            <p class="legal-update">Berlaku efektif mulai: 22 Juni 2025</p>

            <p>Dengan mengakses atau menggunakan layanan KosinAja, Anda setuju untuk terikat oleh Syarat dan Ketentuan ini.</p>

            <h3>1. Penggunaan Layanan</h3>
            <ul>
                <li>Anda harus berusia minimal 17 tahun untuk menggunakan layanan ini.</li>
                <li>Anda bertanggung jawab atas keakuratan informasi yang Anda berikan, termasuk data kos yang diunggah oleh pemilik.</li>
                <li>Penggunaan layanan untuk tujuan ilegal atau tidak sah dilarang keras.</li>
            </ul>

            <h3>2. Akun Pengguna</h3>
            <p>Anda bertanggung jawab untuk menjaga kerahasiaan akun dan password Anda. Segala aktivitas yang terjadi di bawah akun Anda adalah tanggung jawab Anda sepenuhnya.</p>

            <h3>3. Konten</h3>
            <p>Pemilik kos yang mengunggah informasi properti menjamin bahwa mereka memiliki hak untuk melakukannya dan informasi yang diberikan adalah benar dan tidak menyesatkan. KosinAja berhak untuk menghapus konten yang dianggap tidak pantas atau melanggar aturan tanpa pemberitahuan sebelumnya.</p>

            <h3>4. Batasan Tanggung Jawab</h3>
            <p>KosinAja adalah platform yang menghubungkan pencari dan pemilik kos. Kami tidak bertanggung jawab atas transaksi, komunikasi, atau perselisihan apa pun yang terjadi antara pengguna. Informasi yang disajikan "sebagaimana adanya" dan kami tidak menjamin keakuratannya secara mutlak.</p>
        </div>
    </main>

    <footer class="site-footer">
    </footer>
    <script src="js/script.js"></script>
</body>
</html>
