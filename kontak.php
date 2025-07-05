<?php
session_start();
// (Jika ada logika backend lain, bisa ditambahkan di sini, misal untuk mengirim email dari form)
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hubungi Kami - KosinAja</title>
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
                            <a href="<?= $_SESSION['role'] == 'owner' ? 'dashboard.php' : 'profile.php' ?>">Profile Saya</a>
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
            <div class="section-header" style="margin-bottom: 2rem;">
                <h1 class="section-title">Hubungi Kami</h1>
                <p class="section-subtitle">Punya pertanyaan, masukan, atau butuh bantuan? Jangan ragu untuk menghubungi kami melalui informasi di bawah atau kirimkan pesan melalui form.</p>
            </div>

            <div class="contact-page-layout">

                <div class="contact-info-wrapper">
                    <div class="contact-info-item glass-card">
                        <h3>Alamat Kantor</h3>
                        <p>Jl. Otto Iskandardinata No. 64C<br>Jakarta Timur, DKI Jakarta, 13330<br>Indonesia</p>
                    </div>
                    <div class="contact-info-item glass-card">
                        <h3>Email & Telepon</h3>
                        <p><strong>Email:</strong> 222313427@stis.ac.id<br><strong>Telepon:</strong> 0812 8112 3487</p>
                    </div>
                     <div class="contact-info-item glass-card">
                        <h3>Jam Operasional</h3>
                        <p>Senin - Jumat<br>09:00 - 17:00 WIB</p>
                    </div>
                </div>

                <div class="contact-form-wrapper glass-card">
                    <h2>Kirim Pesan</h2>
                    <form action="#" method="POST">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Alamat Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="pesan">Pesan Anda</label>
                            <textarea id="pesan" name="pesan" rows="6" required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="site-footer">
        <div class="footer-main-content">
            <h2 class="footer-logo">KosinAja</h2>
            <p class="footer-tagline">Platform terpercaya untuk menemukan hunian nyaman di sekitar Politeknik Statistika STIS.</p>
            <div class="footer-cta">
                <p>Anda pemilik kos? Daftarkan properti Anda sekarang juga!</p>
                <a href="register.php" class="btn-primary">Jadi Partner Kami</a>
            </div>
            <div class="footer-social-links">
                <a href="https://www.instagram.com/vierxyr" target="_blank" title="Instagram" aria-label="Instagram"><svg viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a>
                <a href="https://github.com/viersss" target="_blank" title="GitHub" aria-label="GitHub">
                <svg viewBox="0 0 16 16" fill="currentColor" width="24" height="24" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82a7.68 7.68 0 0 1 2-.27c.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"/>
                </svg>
                </a>
            </div>
        </div>
        <div class="footer-bottom-bar">
            <div class="copyright-text">
                &copy; 2025 KosinAja. Proyek Akhir Individu - Pemrograman Berbasis Web.
            </div>
            <div class="legal-links">
                <a href="kebijakan_privasi.php">Kebijakan Privasi</a>
                <a href="syarat_dan_ketentuan.php">Syarat & Ketentuan</a>
            </div>
        </div>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>
