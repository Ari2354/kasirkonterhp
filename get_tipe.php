<?php
session_start();
include 'config.php'; // Koneksi ke database

if (isset($_POST['merk'])) {
    $merk = $_POST['merk'];

    // Query untuk mengambil tipe berdasarkan merek
    $query = "SELECT DISTINCT tipe FROM produk WHERE merek = '$merk'";
    $result = mysqli_query($conn, $query);

    // Cek apakah ada tipe yang ditemukan
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row['tipe'] . '">' . $row['tipe'] . '</option>';
        }
    } else {
        echo '<option value="">Tidak ada tipe ditemukan</option>';
    }
}
?>