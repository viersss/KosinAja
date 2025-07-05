<?php
$page_title = 'Profil & Pengaturan';
require 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'php/connect.php';
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">Profile Anda</h1>
            <p class="section-subtitle">Kelola informasi dan keamanan akun Anda di bawah ini.</p>
        </div>

        <div class="profile-cards-container">

            <div class="profile-card-simple glass-card">
                <h3>Informasi Akun</h3>
                <ul>
                    <li><span>Username</span><strong><?= htmlspecialchars($user['username']) ?></strong></li>
                    <li><span>Email Saat Ini</span><strong><?= htmlspecialchars($user['email']) ?></strong></li>
                </ul>
            </div>

            <div class="profile-card-simple glass-card">
                <h3>Ubah Email</h3>
                <form action="php/update_profile.php" method="POST" class="settings-form">
                    <?php if(isset($_GET['status']) && $_GET['status'] == 'email_success'): ?>
                        <p class="success-message">Email berhasil diperbarui!</p>
                    <?php elseif(isset($_GET['status']) && $_GET['status'] == 'email_duplicate'): ?>
                        <p class="error-message">Email tersebut sudah digunakan.</p>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="new_email">Email Baru</label>
                        <input type="email" id="new_email" name="new_email" required>
                    </div>
                    <button type="submit" name="change_email" class="btn-submit">Simpan Email</button>
                </form>
            </div>

            <div class="profile-card-simple glass-card">
                <h3>Ubah Password</h3>
                <form action="php/update_profile.php" method="POST" class="settings-form">
                    <?php if(isset($_GET['status']) && $_GET['status'] == 'pwd_success'): ?>
                        <p class="success-message">Password berhasil diperbarui!</p>
                    <?php elseif(isset($_GET['status']) && in_array($_GET['status'], ['pwd_wrong', 'pwd_invalid', 'pwd_mismatch'])): ?>
                        <p class="error-message">
                            <?php
                                switch ($_GET['status']) {
                                    case 'pwd_wrong': echo 'Password lama salah.'; break;
                                    case 'pwd_invalid': echo 'Password baru tidak valid (min. 8 karakter, ada huruf & angka).'; break;
                                    case 'pwd_mismatch': echo 'Konfirmasi password baru tidak cocok.'; break;
                                }
                            ?>
                        </p>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="password_lama">Password Lama</label>
                        <input type="password" id="password_lama" name="password_lama" required>
                    </div>
                    <div class="form-group">
                        <label for="password_baru">Password Baru</label>
                        <input type="password" id="password_baru" name="password_baru" required minlength="8" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" title="Minimal 8 karakter, mengandung huruf dan angka.">
                    </div>
                    <div class="form-group">
                        <label for="konfirmasi_password_baru">Konfirmasi Password Baru</label>
                        <input type="password" id="konfirmasi_password_baru" name="konfirmasi_password_baru" required>
                    </div>
                    <button type="submit" name="change_password" class="btn-submit">Simpan Password</button>
                </form>
            </div>

        </div>
    </div>
</main>

<?php require 'footer.php'; ?>
