<?php
session_start();
include 'config.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil dan sanitasi data dari form
    $nama_customer = mysqli_real_escape_string($conn, $_POST['nama_customer']);
    $nomor_hp = mysqli_real_escape_string($conn, $_POST['nomor_hp']);
    $merk = mysqli_real_escape_string($conn, $_POST['merk']);
    $tipe = mysqli_real_escape_string($conn, $_POST['tipe']);
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $pembayaran = mysqli_real_escape_string($conn, $_POST['pembayaran']);
    $user_id = $_SESSION['kasir']; // Ambil user_id dari sesi

    // Ambil harga produk dan stok
    $query = mysqli_query($conn, "SELECT id, harga_jual, stok FROM produk WHERE merek = '$merk' AND tipe = '$tipe'");
    $data = mysqli_fetch_assoc($query);
    
    if ($data) {
        $produk_id = $data['id'];
        $harga_jual = $data['harga_jual'];
        $stok = $data['stok'];
        $total_harga = $harga_jual * $jumlah;

        // Cek apakah stok mencukupi
        if ($stok >= $jumlah) {
            // Kurangi stok
            $stok_baru = $stok - $jumlah;

            // Buat kode transaksi unik
            $kode_transaksi = uniqid('TRX-');

            // Simpan transaksi
            $query = "INSERT INTO transaksi (kode_transaksi, produk_id, jumlah, total_harga, metode_pembayaran, tanggal, nama_customer, nomor_hp, user_id) 
                      VALUES ('$kode_transaksi', '$produk_id', '$jumlah', '$total_harga', '$pembayaran', NOW(), '$nama_customer', '$nomor_hp', '$user_id')";
            
            if (mysqli_query($conn, $query)) {
                // Ambil ID transaksi terakhir
                $transaksi_id = mysqli_insert_id($conn);

                // Debugging: Periksa nilai dari $transaksi_id
                echo "ID Transaksi Terakhir: " . $transaksi_id . "<br>";

                // Perbarui stok produk
                $update_query = "UPDATE produk SET stok = '$stok_baru' WHERE id = '$produk_id'";
                mysqli_query($conn, $update_query);

                // Redirect ke halaman nota untuk mencetak
                header("Location: nota.php?id=$transaksi_id");
                exit;
            } else {
                echo "Gagal menyimpan transaksi: " . mysqli_error($conn);
            }
        } else {
            echo "Stok tidak mencukupi.";
        }
    } else {
        echo "Produk tidak ditemukan.";
    }
}
?>