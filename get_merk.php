<?php
session_start();
include 'config.php'; // Koneksi ke database

if (isset($_POST['kategori'])) {
    $kategori = $_POST['kategori'];

    // Query untuk mengambil merek berdasarkan kategori
    $query = "SELECT DISTINCT merek FROM produk WHERE kategori = '$kategori'";
    $result = mysqli_query($conn, $query);

    // Cek apakah ada merek yang ditemukan
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row['merek'] . '">' . $row['merek'] . '</option>';
        }
    } else {
        echo '<option value="">Tidak ada merek ditemukan</option>';
    }
}
?>