<?php
session_start();
require 'php/connect.php';

// Validasi: Pastikan data POST ada
if (!isset($_POST['id_kos']) || !isset($_POST['tgl_masuk']) || !isset($_POST['durasi'])) {
    // Jika tidak ada data, kembalikan ke halaman utama
    header("Location: index.php");
    exit();
}

$id_kos = $_POST['id_kos'];
$tgl_masuk_str = $_POST['tgl_masuk'];
$durasi = (int)$_POST['durasi'];

// Ambil data kos dari database
$stmt = $pdo->prepare("SELECT * FROM kos WHERE id_kos = ?");
$stmt->execute([$id_kos]);
$kos = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$kos) {
    header("Location: index.php");
    exit();
}

// Hitung total biaya dan tanggal selesai sewa
$harga_per_bulan = $kos['harga_per_bulan'];
$total_biaya = $harga_per_bulan * $durasi;

$tgl_masuk = new DateTime($tgl_masuk_str);
$tgl_selesai = new DateTime($tgl_masuk_str);
$tgl_selesai->add(new DateInterval("P{$durasi}M"));

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Booking - KosinAja</title>
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
        <div class="container">
            <div class="section-header">
                <h1 class="section-title">Konfirmasi Pesanan Anda</h1>
                <p class="section-subtitle">Harap periksa kembali detail pesanan Anda di bawah sebelum melakukan konfirmasi final.</p>
            </div>

            <div class="confirmation-layout">
                <div class="property-details glass-card">
                    <h3>Properti yang Dipesan</h3>
                    <div class="property-card">
                        <img src="media/<?= htmlspecialchars($kos['gambar']) ?>" alt="Foto <?= htmlspecialchars($kos['nama_kos']) ?>">
                        <div class="property-info">
                            <h4><?= htmlspecialchars($kos['nama_kos']) ?></h4>
                            <p><?= htmlspecialchars($kos['alamat']) ?></p>
                        </div>
                    </div>
                </div>

                <div class="order-summary glass-card">
                    <h3>Ringkasan Pesanan</h3>
                    <ul>
                        <li><span>Tanggal Mulai Sewa:</span> <strong><?= $tgl_masuk->format('d F Y') ?></strong></li>
                        <li><span>Tanggal Selesai Sewa:</span> <strong><?= $tgl_selesai->format('d F Y') ?></strong></li>
                        <li><span>Durasi:</span> <strong><?= htmlspecialchars($durasi) ?> bulan</strong></li>
                        <li><span>Harga per Bulan:</span> <strong>Rp <?= number_format($harga_per_bulan) ?></strong></li>
                        <li class="total"><span>Total Biaya:</span> <strong class="total-price">Rp <?= number_format($total_biaya) ?></strong></li>
                    </ul>

                    <form action="booking.php" method="POST">
                        <input type="hidden" name="id_kos" value="<?= htmlspecialchars($id_kos) ?>">
                        <input type="hidden" name="tgl_masuk" value="<?= htmlspecialchars($tgl_masuk_str) ?>">
                        <input type="hidden" name="durasi" value="<?= htmlspecialchars($durasi) ?>">
                        <input type="hidden" name="total_biaya" value="<?= htmlspecialchars($total_biaya) ?>">

                        <button type="submit" class="btn-submit">Konfirmasi & Booking Final</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="site-footer">
    </footer>
    <script src="js/script.js"></script>
</body>
</html>
