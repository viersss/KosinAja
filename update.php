<?php
// Menggunakan template header
$page_title = 'Edit Properti Kos';
require 'header.php';

// Keamanan: Pastikan user login, perannya owner, dan ada ID di URL
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'owner' || !isset($_GET['id'])) {
    header("Location: login.php?status=access_denied");
    exit();
}

require 'php/connect.php';
$id_kos_to_update = $_GET['id'];
$id_pemilik = $_SESSION['user_id'];

// Logika untuk memproses form saat disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kos = trim($_POST['nama_kos']);
    $alamat = trim($_POST['alamat']);
    $harga = filter_input(INPUT_POST, 'harga', FILTER_VALIDATE_INT);
    $jenis = $_POST['jenis_kos'];
    $fasilitas = trim($_POST['fasilitas']);
    $kontak = trim($_POST['kontak_pemilik']);

    $stmt = $pdo->prepare(
        "UPDATE kos SET nama_kos = ?, alamat = ?, harga_per_bulan = ?, fasilitas = ?, jenis_kos = ?, kontak_pemilik = ?
         WHERE id_kos = ? AND id_pemilik = ?"
    );
    // Jalankan update, pastikan user hanya bisa update properti miliknya sendiri
    $stmt->execute([$nama_kos, $alamat, $harga, $fasilitas, $jenis, $kontak, $id_kos_to_update, $id_pemilik]);

    // Alihkan ke dashboard setelah berhasil
    header("Location: dashboard.php?status=update_success");
    exit();
}

// Ambil data kos yang akan diedit dari database
$stmt = $pdo->prepare("SELECT * FROM kos WHERE id_kos = ? AND id_pemilik = ?");
$stmt->execute([$id_kos_to_update, $id_pemilik]);
$kos = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika kos tidak ditemukan atau bukan milik user, alihkan
if (!$kos) {
    header("Location: dashboard.php?status=not_found");
    exit();
}
?>

<main>
    <div class="container">
        <div class="form-container glass-card" style="max-width: 800px;">
            <h2 class="section-title">Edit Properti: <?= htmlspecialchars($kos['nama_kos']) ?></h2>
            <p class="section-subtitle">Perbarui detail properti kos Anda melalui form di bawah ini.</p>

            <form action="update.php?id=<?= $id_kos_to_update ?>" method="POST">
                <div class="form-group">
                    <label for="nama_kos">Nama Kos</label>
                    <input type="text" id="nama_kos" name="nama_kos" value="<?= htmlspecialchars($kos['nama_kos']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" rows="3" required><?= htmlspecialchars($kos['alamat']) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="harga">Harga per Bulan (Rp)</label>
                    <input type="number" id="harga" name="harga" value="<?= htmlspecialchars($kos['harga_per_bulan']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="jenis_kos">Jenis Kos</label>
                    <select id="jenis_kos" name="jenis_kos">
                        <option value="Putra" <?= ($kos['jenis_kos'] == 'Putra') ? 'selected' : '' ?>>Putra</option>
                        <option value="Putri" <?= ($kos['jenis_kos'] == 'Putri') ? 'selected' : '' ?>>Putri</option>
                        <option value="Campur" <?= ($kos['jenis_kos'] == 'Campur') ? 'selected' : '' ?>>Campur</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fasilitas">Fasilitas (pisahkan dengan koma)</label>
                    <textarea id="fasilitas" name="fasilitas" rows="3"><?= htmlspecialchars($kos['fasilitas']) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="kontak_pemilik">Nomor Telepon/WhatsApp</label>
                    <input type="text" id="kontak_pemilik" name="kontak_pemilik" value="<?= htmlspecialchars($kos['kontak_pemilik']) ?>" required>
                </div>
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</main>

<?php require 'footer.php'; ?>
