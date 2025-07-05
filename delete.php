<?php
// Selalu mulai session untuk memeriksa data login
session_start();

// Muat file koneksi database
require 'php/connect.php';

// --- BAGIAN KEAMANAN PENTING ---
// 1. Pastikan pengguna sudah login
// 2. Pastikan peran pengguna adalah 'owner'
// 3. Pastikan ada ID kos yang dikirim melalui URL
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'owner' || !isset($_GET['id'])) {
    // Jika salah satu tidak terpenuhi, alihkan ke halaman login
    header("Location: login.php?status=access_denied");
    exit();
}

// Ambil ID kos yang akan dihapus dari URL
$id_kos_to_delete = $_GET['id'];
// Ambil ID pemilik kos yang sedang login dari session
$id_pemilik = $_SESSION['user_id'];


// --- PROSES PENGHAPUSAN DATA ---

// Buat perintah SQL untuk menghapus data.
// Perintah ini HANYA akan menghapus baris jika 'id_kos' DAN 'id_pemilik' cocok.
// Ini mencegah pemilik A menghapus properti milik pemilik B.
$stmt = $pdo->prepare("DELETE FROM kos WHERE id_kos = ? AND id_pemilik = ?");

// Jalankan perintah dengan data yang aman
$stmt->execute([$id_kos_to_delete, $id_pemilik]);

// Setelah selesai (berhasil atau tidak), alihkan kembali ke dashboard dengan pesan sukses
header("Location: dashboard.php?status=delete_success");
exit();
?>