<?php
session_start();

// 1. Hapus semua variabel di dalam session
$_SESSION = array();

// 2. Hancurkan session-nya
session_destroy();

// 3. Alihkan pengguna kembali ke halaman utama (index.php)
header("Location: index.php");

exit();
?>