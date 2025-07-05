<?php
session_start();
require 'php/connect.php';

// Keamanan: Pastikan user login dan data POST ada
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$id_user = $_SESSION['user_id'];
$id_kos = $_POST['id_kos'];
$tgl_masuk = $_POST['tgl_masuk'];
$durasi = $_POST['durasi'];
$total_biaya = $_POST['total_biaya'];

// Masukkan data booking ke dalam database
$stmt = $pdo->prepare(
    "INSERT INTO bookings (id_kos, id_user, tgl_mulai_sewa, durasi_bulan, total_harga)
     VALUES (?, ?, ?, ?, ?)"
);

$booking_berhasil = $stmt->execute([$id_kos, $id_user, $tgl_masuk, $durasi, $total_biaya]);

if($booking_berhasil) {
    $message = "Booking Anda telah kami terima dan sedang diproses! Pemilik kos akan segera menghubungi Anda untuk konfirmasi lebih lanjut. Terima kasih telah menggunakan KosinAja.";
} else {
    $message = "Maaf, terjadi kesalahan saat memproses booking Anda. Silakan coba lagi.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Booking - KosinAja</title>
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

    <div class="container" style="text-align: center; padding-top: 8rem;">
        <div class="glass-card" style="padding: 3rem;">
            <h1 class="section-title"><?= $booking_berhasil ? 'Booking Berhasil!' : 'Booking Gagal' ?></h1>
            <p class="section-subtitle" style="font-size: 1.2rem;"><?= $message ?></p>
            <a href="profile.php" class="btn-primary" style="margin-top: 2rem;">Lihat Riwayat Booking</a>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
