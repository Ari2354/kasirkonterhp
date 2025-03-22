<?php
session_start();
session_destroy(); // Menghancurkan semua sesi
header('Location: login_kasir.php'); // Mengarahkan kembali ke halaman login kasir
exit;
?>