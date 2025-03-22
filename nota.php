<?php
session_start();
include 'config.php'; // Koneksi ke database

// Cek apakah kasir sudah login
if (!isset($_SESSION['kasir'])) {
    header('Location: login_kasir.php');
    exit;
}

// Cek apakah ID transaksi ada di URL
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data transaksi berdasarkan ID
$query = mysqli_query($conn, "SELECT t.*, p.merek, p.tipe, u.username AS kasir FROM transaksi t 
                              JOIN produk p ON t.produk_id = p.id 
                              JOIN users u ON t.user_id = u.id 
                              WHERE t.id = '$id'");

// Debugging: Periksa apakah query berhasil dijalankan
if (!$query) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

$data = mysqli_fetch_assoc($query);

// Debugging: Periksa apakah data transaksi ditemukan
if (!$data) {
    echo "Transaksi tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
        }
        .nota {
            width: 210mm; /* Lebar kertas A4 */
            margin: 0 auto;
            padding: 10px;
            border: 1px solid #000;
        }
        .nota-header, .nota-footer {
            text-align: center;
        }
        .nota-body {
            margin-top: 10px;
        }
        .nota-body p {
            margin: 0;
            padding: 0;
        }
        @media print {
            body {
                font-size: 12px;
            }
            .nota {
                width: 210mm; /* Lebar kertas A4 */
                margin: 0;
                padding: 0;
                border: none;
            }
            .nota-header, .nota-footer {
                text-align: center;
            }
            .nota-body {
                margin-top: 10px;
            }
            .nota-body p {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
        .no-print {
            margin-top: 20px;
            text-align: center;
        }
        .no-print .btn {
            margin: 5px;
        }
    </style>
</head>
<body>
    <div class="nota">
        <div class="nota-header">
            <h2>Nota Transaksi</h2>
            <p>ID Transaksi: <?php echo $data['id']; ?></p>
            <p>Kode Transaksi: <?php echo $data['kode_transaksi']; ?></p>
            <p>Tanggal: <?php echo $data['tanggal']; ?></p>
            <p>Kasir: <?php echo $data['kasir']; ?></p>
        </div>
        <div class="nota-body">
            <p>Nama Customer: <?php echo $data['nama_customer']; ?></p>
            <p>Nomor HP: <?php echo $data['nomor_hp']; ?></p>
            <p>Merk: <?php echo $data['merek']; ?></p>
            <p>Tipe: <?php echo $data['tipe']; ?></p>
            <p>Jumlah: <?php echo $data['jumlah']; ?></p>
            <p>Total Harga: Rp. <?php echo number_format($data['total_harga']); ?></p>
            <p>Metode Pembayaran: <?php echo $data['metode_pembayaran']; ?></p>
        </div>
    </div>
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Cetak Nota</button>
        <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
</body>
</html>