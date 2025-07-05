<?php
session_start();
require 'php/connect.php'; // Hubungkan ke database

// --- LOGIKA PENCARIAN TERSTRUKTUR ---
$search_results = [];
// Ambil nilai input untuk repopulasi form
$nama_kos_val = isset($_GET['nama_kos']) ? htmlspecialchars($_GET['nama_kos']) : '';
$alamat_val = isset($_GET['alamat']) ? htmlspecialchars($_GET['alamat']) : '';
$fasilitas_val = isset($_GET['fasilitas']) ? htmlspecialchars($_GET['fasilitas']) : 'semua';

// Cek apakah ada parameter pencarian yang dikirim
$is_search = !empty(trim($nama_kos_val)) || !empty(trim($alamat_val)) || $fasilitas_val !== 'semua';

if ($is_search) {
    $sql_conditions = [];
    $params = [];

    if (!empty(trim($nama_kos_val))) {
        $sql_conditions[] = "nama_kos LIKE ?";
        $params[] = '%' . trim($nama_kos_val) . '%';
    }
    if (!empty(trim($alamat_val))) {
        $sql_conditions[] = "alamat LIKE ?";
        $params[] = '%' . trim($alamat_val) . '%';
    }
    if ($fasilitas_val !== 'semua') {
        $sql_conditions[] = "fasilitas LIKE ?";
        $params[] = '%' . trim($fasilitas_val) . '%';
    }

    $sql = "SELECT * FROM kos";
    if (!empty($sql_conditions)) {
        $sql .= " WHERE " . implode(" AND ", $sql_conditions);
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// --- LOGIKA MENGAMBIL KOS POPULER ---
$popular_stmt = $pdo->query("SELECT * FROM kos ORDER BY RAND() LIMIT 3");
$popular_kos = $popular_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KosinAja - Temukan Kos Ideal Anda Dekat STIS</title>
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
                <a href="#popular">Pilihan Kos</a>
                <a href="#features">Fitur</a>
                <a href="#about">Tentang</a>
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

        <section class="hero-main" id="search">
<div class="hero-slideshow">
    <div class="hero-slide active" style="background-image: url('https://images.pexels.com/photos/6970025/pexels-photo-6970025.jpeg');"></div>
    <div class="hero-slide" style="background-image: url('https://images.pexels.com/photos/3946657/pexels-photo-3946657.jpeg');"></div>
    <div class="hero-slide" style="background-image: url('https://images.pexels.com/photos/439227/pexels-photo-439227.jpeg');"></div>
</div>

                <div class="hero-overlay"></div>
                <div class="hero-content">
                <h1>Temukan Kosan Ideal Anda</h1>
                <p>Platform modern dan terpercaya untuk menemukan kos idaman di sekitar kampus Politeknik Statistika STIS.</p>

                <div class="search-box">
                    <form action="index.php#results" method="GET" class="structured-search-form">

                        <div class="search-field" style="position: relative;">
                            <label for="nama_kos">Nama Kos</label>
                            <input type="text" id="nama_kos" name="nama_kos" placeholder="Contoh: Kos Melati" value="<?= $nama_kos_val ?>" autocomplete="off">
                            <div class="suggestion-box" id="nama_kos_suggestions"></div>
                        </div>

                        <div class="search-field" style="position: relative;">
                            <label for="alamat">Lokasi</label>
                            <input type="text" id="alamat" name="alamat" placeholder="Contoh: Jatinegara" value="<?= $alamat_val ?>" autocomplete="off">
                            <div class="suggestion-box" id="alamat_suggestions"></div>
                        </div>

                        <div class="search-field">
                            <label for="fasilitas">Fasilitas Utama</label>
                            <select id="fasilitas" name="fasilitas">
                                <option value="semua" <?= $fasilitas_val == 'semua' ? 'selected' : '' ?>>Semua Fasilitas</option>
                                <option value="AC" <?= $fasilitas_val == 'AC' ? 'selected' : '' ?>>AC</option>
                                <option value="Kamar Mandi Dalam" <?= $fasilitas_val == 'Kamar Mandi Dalam' ? 'selected' : '' ?>>Kamar Mandi Dalam</option>
                                <option value="Wi-Fi" <?= $fasilitas_val == 'Wi-Fi' ? 'selected' : '' ?>>Wi-Fi</option>
                                <option value="Parkir Mobil" <?= $fasilitas_val == 'Parkir Mobil' ? 'selected' : '' ?>>Parkir Mobil</option>
                            </select>
                        </div>

                        <button type="submit" class="btn-search-structured">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </button>

                    </form>
                </div>

            </div>
        </section>

        <?php if ($is_search): ?>
        <section id="results" class="container">
            <div class="section-header">
                <?php if (!empty($search_results)): ?>
                    <h2 class="section-title">Hasil Pencarian</h2>
                <?php else: ?>
                    <h2 class="section-title">Pencarian Tidak Ditemukan</h2>
                    <p class="section-subtitle">Maaf, tidak ada kos yang ditemukan dengan kriteria pencarian Anda. Coba gunakan kata kunci lain.</p>
                <?php endif; ?>
            </div>
            <div class="results-grid">
                <?php foreach($search_results as $kos): ?>
                <div class="kos-card glass-card">
                    <h3><?= htmlspecialchars($kos['nama_kos']) ?></h3>
                    <p><strong>Alamat:</strong> <?= htmlspecialchars($kos['alamat']) ?></p>
                    <div class="price-and-type">
                        <span class="price">Rp <?= number_format($kos['harga_per_bulan'], 0, ',', '.') ?> / bulan</span>
                        <span class="type-badge"><?= htmlspecialchars($kos['jenis_kos']) ?></span>
                    </div>
                   <div class="card-actions">
                        <a href="detail.php?id=<?= $kos['id_kos'] ?>" class="btn-primary">Lihat Detail</a>
                   </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>


        <section id="features" class="container">
            <div class="section-header">
                <h2 class="section-title">Semua yang Anda Butuhkan</h2>
                <p class="section-subtitle">Kami menyediakan berbagai fitur canggih yang dirancang untuk kemudahan dan keamanan Anda, baik sebagai pencari maupun pemilik kos.</p>
            </div>
            <div class="features-grid-6">
                <div class="feature-card glass-card">
                    <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M22 3H2l8 9.46V19l4 2v-8.46L22 3z"></path></svg></div>
                    <h3>Filter Pencarian Canggih</h3>
                    <p>Temukan Lebih Cepat. Cari kos berdasarkan nama, lokasi, dan fasilitas utama seperti AC atau Wi-Fi untuk hasil yang paling relevan.</p>
                </div>
                <div class="feature-card glass-card">
                    <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></div>
                    <h3>Dashboard Pemilik Kos</h3>
                    <p>Antarmuka yang intuitif bagi pemilik kos untuk menambah, mengedit, dan mengelola semua properti mereka dengan mudah (CRUD).</p>
                </div>
                <div class="feature-card glass-card">
                     <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></div>
                    <h3>Akun Terpisah</h3>
                    <p>Pengalaman yang disesuaikan untuk setiap peran. Baik Anda pencari kos atau pemilik properti, fitur yang Anda butuhkan selalu tersedia.</p>
                </div>
                <div class="feature-card glass-card">
                     <div class="feature-icon"><svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg></div>
                    <h3>Akses Di Mana Saja</h3>
                    <p>Tampilan modern dengan efek glassmorphism yang dapat diakses dengan nyaman di desktop, tablet, maupun smartphone Anda.</p>
                </div>
                <div class="feature-card glass-card">
                    <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></div>
                    <h3>Data Anda Aman</h3>
                    <p>Dibangun dengan fokus keamanan. Kami melindungi data Anda dari serangan umum seperti SQL Injection menggunakan parameterized statement.</p>
                </div>
                <div class="feature-card glass-card">
                    <div class="feature-icon"><svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg></div>
                    <h3>Kode yang Dioptimalkan</h3>
                    <p>Dibuat dari dasar untuk menjamin efisiensi, kecepatan, dan keandalan maksimal bagi setiap pengguna.</p>
                </div>
            </div>
        </section>

        <section id="popular" class="container">
        <div class="section-header">
            <h2 class="section-title">Kos Terpopuler Pilihan Mahasiswa</h2>
            <p class="section-subtitle">Jelajahi beberapa pilihan kos terbaik dan terpopuler yang tersedia saat ini di sekitar kampus STIS.</p>
        </div>
        <div class="popular-grid">
            <?php foreach ($popular_kos as $kos): ?>
            <div class="info-card glass-card">

                <h3 class="info-card-title"><?= htmlspecialchars($kos['nama_kos']) ?></h3>
                <p class="info-card-address"><strong>Alamat:</strong> <?= htmlspecialchars($kos['alamat']) ?></p>

                <div class="info-card-details">
                    <span class="price">Rp <?= number_format($kos['harga_per_bulan'], 0, ',', '.') ?> / bulan</span>
                    <span class="type-badge"><?= htmlspecialchars($kos['jenis_kos']) ?></span>
                </div>

                <div class="info-card-actions">
                    <a href="detail.php?id=<?= $kos['id_kos'] ?>" class="btn-primary">Lihat Detail</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
            </section>

            <section id="steps" class="container">
                 <div class="section-header">
                    <h2 class="section-title">Hanya 3 Langkah Mudah</h2>
                    <p class="section-subtitle">Menemukan kos impian Mahasiswa tidak pernah semudah ini. Ikuti langkah-langkah berikut.</p>
                </div>
                <div class="steps-grid">
                    <div class="step-item">
                        <div class="step-number">1</div>
                        <h3>Cari & Filter</h3>
                        <p>Gunakan fitur filter canggih kami untuk menemukan kos yang sesuai dengan kriteria dan anggaran Anda.</p>
                    </div>
                    <div class="step-item">
                        <div class="step-number">2</div>
                        <h3>Hubungi Pemilik</h3>
                        <p>Lihat detail kos dan hubungi pemilik secara langsung melalui informasi kontak yang tersedia untuk bertanya atau survei.</p>
                    </div>
                    <div class="step-item">
                        <div class="step-number">3</div>
                        <h3>Tempati Hunian Baru</h3>
                        <p>Setelah kesepakatan tercapai, Anda siap untuk menempati hunian baru Anda yang nyaman dan strategis.</p>
                    </div>
                </div>
            </section>

        <section id="about" class="container">
            <div class="about-container glass-card">
                <div class="about-image">
                    <img src="media/kos_keren.jpg" alt="Tim KosinAja" loading="lazy">
                </div>
                <div class="about-content">
                    <h2 class="section-title" style="text-align:left;">
                        Tentang <span class="logo">KosinAja</span>
                    </h2>
                    <p class="text-justify">
                        KosinAja adalah platform pencarian kos yang dirancang untuk memberikan pengalaman mencari hunian yang mudah, modern, dan terpercaya bagi mahasiswa Politeknik Statistika STIS. Platform ini hadir untuk membantu mahasiswa menemukan hunian yang sesuai kebutuhan dengan lebih efisien dan nyaman.
                    </p>
                    <p class="text-justify">
                        Didukung oleh teknologi web yang aman dan responsif, KosinAja memudahkan pengguna dalam menjelajahi pilihan kos, melihat detail fasilitas, dan membuat keputusan yang tepat. Komitmen kami adalah menghadirkan layanan pencarian hunian yang praktis, informatif, dan dapat diandalkan.
                    </p>
                </div>
            </div>
        </section>

        <section id="faq" class="container faq-section">
            <div class="container">
                <h2 class="section-title">Frequently Asked Questions (FAQ)</h2>
                <br>
                <br>
                <br>

                <div class="faq-container">
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Bagaimana cara memesan kos di KosinAja?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>Anda bisa memesan langsung melalui halaman detail kos yang Anda minati. Cukup pilih tanggal masuk dan durasi sewa, lalu klik tombol "Booking Sekarang". Pastikan Anda sudah login terlebih dahulu.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Metode pembayaran apa saja yang diterima?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>Kami menerima pembayaran melalui transfer bank ke rekening yang tertera saat Anda melakukan konfirmasi booking. Kami sedang berupaya untuk menambahkan metode pembayaran lainnya seperti e-wallet.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Apakah saya bisa melihat kamar kos sebelum memesan?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>Tentu! Anda sangat dianjurkan untuk melakukan survei lokasi. Silakan hubungi pemilik kos melalui nomor WhatsApp yang tersedia di halaman detail untuk membuat janji temu.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Apakah harga yang tertera sudah termasuk listrik dan air?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>Kebijakan ini berbeda-beda untuk setiap kos. Mohon periksa bagian "Fasilitas" dan deskripsi pada halaman detail kos untuk informasi lengkap, atau tanyakan langsung kepada pemilik kos.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Berapa lama durasi sewa minimal?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>Umumnya, durasi sewa minimal adalah 1 bulan. Namun, beberapa pemilik kos mungkin memiliki kebijakan yang berbeda. Anda bisa memilih durasi sewa saat akan melakukan booking.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Apakah ada jam malam?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>Peraturan mengenai jam malam atau akses 24 jam tergantung pada kebijakan masing-masing pemilik kos. Informasi ini biasanya tercantum dalam deskripsi kos.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Apakah saya boleh menerima tamu?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>Setiap kos memiliki peraturan tamu yang berbeda. Ada yang memperbolehkan tamu menginap dengan biaya tambahan, ada pula yang hanya memperbolehkan tamu berkunjung hingga jam tertentu. Pastikan untuk menanyakan hal ini kepada pemilik kos.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Bagaimana jika saya ingin membatalkan pesanan?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>Kebijakan pembatalan dan pengembalian dana (refund) diatur sepenuhnya oleh pemilik kos. KosinAja menyarankan Anda untuk membahas hal ini secara langsung dengan pemilik sebelum melakukan pembayaran.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Bagaimana keamanan di lingkungan kos?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>Keamanan adalah prioritas. Banyak kos yang kami tampilkan dilengkapi dengan CCTV dan penjaga keamanan. Untuk detail spesifik, silakan lihat fasilitas yang ditawarkan di halaman detail kos.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Siapa yang harus saya hubungi jika ada masalah di kamar kos?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>Untuk semua masalah yang berkaitan dengan fasilitas atau kondisi kamar, Anda dapat langsung menghubungi pemilik atau penjaga kos yang kontaknya telah diberikan saat Anda pertama kali masuk.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script>
            // Skrip ini khusus untuk FAQ dan tidak akan mengganggu skrip lain
            // Memastikan skrip berjalan setelah semua elemen halaman dimuat
            document.addEventListener('DOMContentLoaded', function () {
                // Mengambil semua tombol pertanyaan di dalam .faq-container
                const faqContainer = document.querySelector('.faq-container');
                if (faqContainer) {
                    const faqQuestions = faqContainer.querySelectorAll('.faq-question');

                    // Menambahkan event listener (fungsi klik) untuk setiap tombol
                    faqQuestions.forEach(button => {
                        button.addEventListener('click', () => {
                            // Menambahkan atau menghapus class 'active' pada tombol yang diklik
                            button.classList.toggle('active');

                            // Mengambil elemen jawaban yang berada tepat setelah tombol
                            const answer = button.nextElementSibling;

                            // Memeriksa apakah jawaban sedang terbuka atau tertutup, lalu mengubahnya
                            if (answer.style.maxHeight) {
                                // Jika terbuka (punya nilai maxHeight), tutup dengan mengaturnya ke null
                                answer.style.maxHeight = null;
                            } else {
                                // Jika tertutup, buka dengan mengatur maxHeight sesuai tinggi kontennya
                                answer.style.maxHeight = answer.scrollHeight + 'px';
                            }
                        });
                    });
                }
            });
        </script>

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
