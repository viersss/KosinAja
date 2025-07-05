<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kebijakan Privasi - KosinAja</title>
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
            <h1 class="section-title">Kebijakan Privasi</h1>
            <p class="legal-update">Terakhir diperbarui: 22 Juni 2025</p>

            <p>Selamat datang di KosinAja. Kami menghargai privasi Anda dan berkomitmen untuk melindunginya. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda.</p>

            <h3>1. Informasi yang Kami Kumpulkan</h3>
            <p>Kami mengumpulkan informasi yang Anda berikan langsung kepada kami saat Anda mendaftar, seperti:</p>
            <ul>
                <li>Nama Pengguna (Username)</li>
                <li>Alamat Email</li>
                <li>Password (dienkripsi)</li>
                <li>Peran (Pencari Kos atau Pemilik Kos)</li>
            </ul>

            <h3>2. Bagaimana Kami Menggunakan Informasi Anda</h3>
            <p>Informasi yang kami kumpulkan digunakan untuk:</p>
            <ul>
                <li>Menyediakan, mengoperasikan, dan memelihara layanan kami.</li>
                <li>Memproses transaksi dan mengelola akun Anda.</li>
                <li>Memperbaiki, mempersonalisasi, dan memperluas layanan kami.</li>
                <li>Berkomunikasi dengan Anda, baik secara langsung atau melalui salah satu mitra kami, termasuk untuk layanan pelanggan.</li>
            </ul>

            <h3>3. Keamanan Data</h3>
            <p>Keamanan informasi Anda penting bagi kami. Kami menggunakan langkah-langkah keamanan teknis dan organisasi yang wajar untuk melindungi data pribadi Anda dari akses, penggunaan, atau pengungkapan yang tidak sah. Termasuk di dalamnya adalah penggunaan parameterized statement untuk mencegah SQL Injection dan hashing password.</p>

            <h3>4. Perubahan pada Kebijakan Privasi Ini</h3>
            <p>Kami dapat memperbarui Kebijakan Privasi kami dari waktu ke waktu. Kami akan memberitahu Anda tentang perubahan apa pun dengan memposting Kebijakan Privasi baru di halaman ini.</p>
        </div>
    </main>

    <footer class="site-footer">
    </footer>
    <script src="js/script.js"></script>
</body>
</html>
