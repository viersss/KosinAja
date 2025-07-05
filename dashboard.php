<?php
// Menggunakan template header.php untuk tampilan yang konsisten
$page_title = 'Dashboard Pemilik';
require 'header.php';

// Keamanan: Pastikan user sudah login dan perannya adalah 'owner'
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php?status=access_denied");
    exit();
}

require 'php/connect.php';

// Ambil hanya data kos yang dimiliki oleh user yang sedang login
$id_pemilik = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM kos WHERE id_pemilik = ? ORDER BY id_kos DESC");
$stmt->execute([$id_pemilik]);
$daftar_kos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <div class="container">
        <div class="dashboard-header">
            <h1 class="section-title">Dashboard Properti Saya</h1>
            <a href="create.php" class="btn-primary">Tambah Kos Baru</a>
        </div>

        <?php
        // Menampilkan pesan sukses setelah membuat, mengedit, atau menghapus data
        if (isset($_GET['status'])):
            $status = $_GET['status'];
            $message = '';
            if ($status == 'create_success') $message = 'Properti kos baru berhasil ditambahkan!';
            if ($status == 'update_success') $message = 'Properti kos berhasil diperbarui!';
            if ($status == 'delete_success') $message = 'Properti kos berhasil dihapus.';

            if (!empty($message)) {
                echo '<p class="success-message">' . $message . '</p>';
            }
        endif;
        ?>

        <div class="dashboard-table-container glass-card">
            <h3>Daftar Properti Kos Anda</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Kos</th>
                            <th>Alamat</th>
                            <th>Harga/Bulan</th>
                            <th>Jenis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($daftar_kos)): ?>
                            <tr>
                                <td colspan="5" style="text-align:center; padding: 2rem;">Anda belum memiliki properti kos. Silakan tambahkan yang baru.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($daftar_kos as $kos): ?>
                            <tr>
                                <td><?= htmlspecialchars($kos['nama_kos']) ?></td>
                                <td><?= htmlspecialchars($kos['alamat']) ?></td>
                                <td>Rp <?= number_format($kos['harga_per_bulan']) ?></td>
                                <td><?= htmlspecialchars($kos['jenis_kos']) ?></td>
                                <td class="action-links">
                                    <a href="update.php?id=<?= $kos['id_kos'] ?>" class="btn-secondary btn-sm">Edit</a>
                                    <a href="delete.php?id=<?= $kos['id_kos'] ?>" class="btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus properti ini? Data yang dihapus tidak bisa dikembalikan.');">Hapus</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php
// Menggunakan template footer.php
require 'footer.php';
?>
