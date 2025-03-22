<?php
session_start();
include 'config.php'; // Koneksi ke database

// Cek apakah kasir sudah login
if (!isset($_SESSION['kasir'])) {
    header('Location: login_kasir.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir Smartphone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .card {
            border: 1px solid #007bff;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .form-label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Kasir Smartphone Mbk Umi</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        
                    </li>
                </ul>
                <span class="navbar-text">
                    Selamat datang, <?php echo $_SESSION['kasir_nama']; ?>
                </span>
                <a href="logout_kasir.php" class="btn btn-danger ms-3">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center">Kasir Smartphone</h1>
       
        <hr>

        <div class="row mt-4">
            <!-- Form Pilihan Kategori -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-list"></i> Pilih Kategori
                    </div>
                    <div class="card-body">
                        <form id="kategoriForm">
                            <div class="mb-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <select name="kategori" id="kategori" class="form-select" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="smartphone">Smartphone</option>
                                    <option value="aksesoris">Aksesoris</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Form Transaksi -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-shopping-cart"></i> Transaksi
                    </div>
                    <div class="card-body">
                        <form method="post" action="proses_tambah.php">
                            <div class="mb-3">
                                <label for="nama_customer" class="form-label">Nama Customer</label>
                                <input type="text" name="nama_customer" class="form-control" id="nama_customer" required>
                            </div>
                            <div class="mb-3">
                                <label for="nomor_hp" class="form-label">Nomor HP Customer</label>
                                <input type="text" name="nomor_hp" class="form-control" id="nomor_hp" required>
                            </div>
                            <div class="mb-3">
                                <label for="pembayaran" class="form-label">Metode Pembayaran</label>
                                <select name="pembayaran" class="form-select" id="pembayaran" required>
                                    <option value="tunai">Tunai</option>
                                    <option value="transfer">Transfer</option>
                                    <optgroup label="E-Wallet">
                                        <option value="ovo">OVO</option>
                                        <option value="gopay">Gopay</option>
                                        <option value="dana">Dana</option>
                                        <option value="shopepay">ShopePay</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="merk" class="form-label">Merk</label>
                                <select name="merk" id="merk" class="form-select" required>
                                    <option value="">Pilih Merk</option>
                                    <!-- Merk akan diisi berdasarkan kategori yang dipilih -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tipe" class="form-label">Tipe</label>
                                <select name="tipe" id="tipe" class="form-select" required>
                                    <option value="">Pilih Tipe</option>
                                    <!-- Tipe akan diisi berdasarkan merk yang dipilih -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah</label>
                                <input type="number" name="jumlah" class="form-control" id="jumlah" min="1" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Beli</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Ambil merk berdasarkan kategori yang dipilih
            $("#kategori").change(function() {
                var kategori = $(this).val();
                $.ajax({
                    url: 'get_merk.php',
                    type: 'POST',
                    data: {kategori: kategori},
                    success: function(data) {
                        $("#merk").html(data);
                        $("#tipe").html('<option value="">Pilih Tipe</option>'); // Reset tipe
                    }
                });
            });

            // Ambil tipe berdasarkan merk yang dipilih
            $("#merk").change(function() {
                var merk = $(this).val();
                $.ajax({
                    url: 'get_tipe.php',
                    type: 'POST',
                    data: {merk: merk},
                    success: function(data) {
                        $("#tipe").html(data);
                    }
                });
            });
        });
    </script>
</body>
</html>