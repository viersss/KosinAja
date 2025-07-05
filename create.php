<?php
// Menggunakan template header.php
$page_title = 'Tambah Kos Baru';
require 'header.php';

// Keamanan: Pastikan user sudah login dan perannya adalah 'owner'
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php?status=access_denied");
    exit();
}

require 'php/connect.php';

// Logika untuk memproses form saat disubmit (method POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pemilik = $_SESSION['user_id'];
    $nama_kos = trim($_POST['nama_kos']);
    $alamat = trim($_POST['alamat']);
    $harga = filter_input(INPUT_POST, 'harga', FILTER_VALIDATE_INT);
    $jenis = $_POST['jenis_kos'];
    $fasilitas = trim($_POST['fasilitas']);
    $kontak = trim($_POST['kontak_pemilik']);

    // Masukkan semua data ke tabel 'kos'
    // Catatan: 'gambar' di sini tidak di-handle, perlu ditambahkan logika upload gambar jika diperlukan
    $gambar_utama = 'default.jpg'; // Placeholder, ganti dengan logika upload gambar
    $stmt = $pdo->prepare(
        "INSERT INTO kos (id_pemilik, nama_kos, alamat, harga_per_bulan, fasilitas, jenis_kos, gambar, kontak_pemilik)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([$id_pemilik, $nama_kos, $alamat, $harga, $fasilitas, $jenis, $gambar_utama, $kontak]);

    // Alihkan kembali ke dashboard dengan pesan sukses
    header("Location: dashboard.php?status=create_success");
    exit();
}
?>

<main>
    <div class="container">
        <div class="form-container glass-card" style="max-width: 800px;">
            <div class="section-header" style="margin-bottom: 2rem;">
                <h2 class="section-title">Tambah Properti Kos Baru</h2>
                <p class="section-subtitle">Isi semua detail di bawah ini untuk properti kos Anda.</p>
            </div>

            <form action="create.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nama_kos">Nama Kos</label>
                    <input type="text" id="nama_kos" name="nama_kos" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="harga">Harga per Bulan (Rp)</label>
                    <input type="number" id="harga" name="harga" required placeholder="Contoh: 850000">
                </div>
                <div class="form-group">
                    <label for="jenis_kos">Jenis Kos</label>
                    <select id="jenis_kos" name="jenis_kos">
                        <option value="Putra">Putra</option>
                        <option value="Putri">Putri</option>
                        <option value="Campur">Campur</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fasilitas">Fasilitas (pisahkan dengan koma)</label>
                    <textarea id="fasilitas" name="fasilitas" rows="3" placeholder="Contoh: AC, Kamar Mandi Dalam, Wi-Fi"></textarea>
                </div>
                <div class="form-group">
                    <label for="kontak_pemilik">Nomor Telepon/WhatsApp</label>
                    <input type="text" id="kontak_pemilik" name="kontak_pemilik" required placeholder="Gunakan format 62... (contoh: 628123456789)">
                </div>

                <button type="submit" class="btn-submit">Simpan Properti</button>
            </form>
        </div>
    </div>
</main>

<?php require 'footer.php'; ?>
