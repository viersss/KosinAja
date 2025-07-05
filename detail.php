<?php
session_start();
require 'php/connect.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_kos = $_GET['id'];

// Ambil data kos spesifik dari database
$stmt = $pdo->prepare("SELECT kos.*, users.username AS pemilik_username FROM kos JOIN users ON kos.id_pemilik = users.id WHERE kos.id_kos = ?");
$stmt->execute([$id_kos]);
$kos = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$kos) {
    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kos: <?= htmlspecialchars($kos['nama_kos']) ?> - KosinAja</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
        <div class="container detail-page-container">
            <div class="detail-main">
                <div class="detail-header">
                    <h1 class="detail-title"><?= htmlspecialchars($kos['nama_kos']) ?></h1>
                    <p class="detail-address"><?= htmlspecialchars($kos['alamat']) ?></p>
                </div>

                <div class="detail-description glass-card">
                    <div class="type-badge" style="float:right;"><?= htmlspecialchars($kos['jenis_kos']) ?></div>
                    <h3 class="section-heading">Deskripsi</h3>
                    <p>Selamat datang di <?= htmlspecialchars($kos['nama_kos']) ?>, hunian nyaman dan strategis yang ideal untuk mahasiswa. Dengan fasilitas lengkap dan lingkungan yang kondusif, kami menawarkan pengalaman tinggal terbaik untuk menunjang aktivitas akademis Anda.</p>

                    <h3 class="section-heading">Fasilitas</h3>
                    <div class="facilities-grid">
                        <?php
                            $fasilitas = explode(',', $kos['fasilitas']);
                            foreach($fasilitas as $f):
                        ?>
                        <span class="facility-tag"><?= htmlspecialchars(trim($f)) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <aside class="detail-sidebar">
                <div class="booking-card glass-card">
                    <div class="price-container">
                        <span class="price-amount">Rp <?= number_format($kos['harga_per_bulan']) ?></span>
                        <span class="price-period">/ bulan</span>
                    </div>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form action="konfirmasi_booking.php" method="POST">
                            <input type="hidden" name="id_kos" value="<?= $kos['id_kos'] ?>">
                            <div class="form-group">
                                <label for="tgl_masuk">Tanggal Mulai Sewa</label>
                                <input type="date" id="tgl_masuk" name="tgl_masuk" required>
                            </div>
                             <div class="form-group">
                                <label for="durasi">Durasi Sewa (bulan)</label>
                                <input type="number" id="durasi" name="durasi" min="1" value="1" required>
                            </div>
                            <button type="submit" class="btn-submit">Booking Sekarang</button>
                        </form>
                    <?php else: ?>
                        <div class="login-prompt" style="text-align: center; margin: 20px 0;">
                            <p style="margin-bottom: 12px;">Anda harus login terlebih dahulu untuk melakukan booking.</p>
                            <a href="login.php" class="btn-primary" style="display: block; box-sizing: border-box;">Login untuk Booking</a>
                        </div>
                    <?php endif; ?>

                    <div class="owner-contact" style="text-align: center; margin-top: 25px;">
                        <p style="margin-bottom: 10px;">Ada pertanyaan? Hubungi pemilik kos.</p>
                        <a href="https://wa.me/<?= htmlspecialchars($kos['kontak_pemilik']) ?>?text=Halo, saya tertarik dengan <?= urlencode($kos['nama_kos']) ?> dari KosinAja."
                           target="_blank"
                           class="btn-whatsapp"
                           style="display: block; box-sizing: border-box; text-decoration: none;">
                            Chat via WhatsApp
                        </a>
                    </div>
                    </div>
            </aside>
        </div>
    </main>

    <footer class="site-footer">
    </footer>
    <script src="js/script.js"></script>
</body>
</html>
