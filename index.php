<?php
require_once __DIR__ . '/config/koneksi.php';

// Ambil data alat beserta kategorinya untuk ditampilkan ke publik
$query = "SELECT a.*, k.nama_kategori FROM alat a JOIN kategori k ON a.id_kategori = k.id_kategori";
$alat_list = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Peminjaman Alat Sekolah</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col">

    <!-- Navigation Bar Sederhana -->
    <nav class="bg-blue-700 text-white shadow-md">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2 font-bold text-xl">
                <i class="fa-solid fa-toolbox"></i>
                <span>PinjamAlat</span>
            </div>
            <a href="login.php" class="bg-white text-blue-700 hover:bg-slate-100 px-4 py-2 rounded font-semibold text-sm transition">
                <i class="fa-solid fa-right-to-bracket mr-1"></i> Login Masuk
            </a>
        </div>
    </nav>

    <!-- Header / Kata Sambutan -->
    <header class="bg-white border-b border-slate-200 py-10 px-6">
        <div class="max-w-6xl mx-auto space-y-3">
            <h1 class="text-3xl font-bold text-slate-900">
                Selamat Datang di Portal Peminjaman Alat
            </h1>
            <p class="text-slate-600 text-base leading-relaxed max-w-3xl">
                Sistem pelayanan inventaris dan peminjaman alat praktikum sekolah. Silakan periksa daftar alat yang tersedia di bawah ini sebelum mengajukan peminjaman.
            </p>
            <div class="pt-2">
                <a href="login.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded text-sm font-semibold">
                    Ajukan Peminjaman
                </a>
            </div>
        </div>
    </header>

    <!-- Katalog Alat Sederhana (Tanpa Gambar) -->
    <main class="max-w-6xl mx-auto w-full px-6 py-10 flex-grow">
        <h2 class="text-2xl font-bold text-slate-800 mb-6 border-b pb-2">
            Daftar Alat Tersedia
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php if ($alat_list && mysqli_num_rows($alat_list) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($alat_list)): ?>
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 flex flex-col justify-between">
                        
                        <!-- Informasi Detail Alat -->
                        <div class="mb-4">
                            <span class="text-xs font-semibold bg-blue-50 text-blue-600 px-2.5 py-1 rounded border border-blue-100 uppercase">
                                <?= $row['nama_kategori'] ?>
                            </span>
                            <h3 class="text-lg font-bold text-slate-800 mt-3">
                                <?= $row['nama_alat'] ?>
                            </h3>
                            <p class="text-sm text-slate-500 mt-2">
                                Kondisi: <span class="font-medium text-slate-700"><?= $row['kondisi'] ?></span>
                            </p>
                            <p class="text-sm text-slate-600 mt-1">
                                Stok: 
                                <span class="font-bold <?= $row['stok'] > 0 ? 'text-green-600' : 'text-red-600' ?>">
                                    <?= $row['stok'] ?> unit
                                </span>
                            </p>
                        </div>

                        <!-- Tombol Aksi -->
                        <div>
                            <?php if ($row['stok'] > 0): ?>
                                <a href="login.php" class="block text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded text-sm font-semibold transition">
                                    Pinjam Alat
                                </a>
                            <?php else: ?>
                                <button disabled class="w-full bg-slate-200 text-slate-500 py-2 rounded text-sm font-semibold cursor-not-allowed">
                                    Stok Habis
                                </button>
                            <?php endif; ?>
                        </div>

                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-3 text-center py-8 text-slate-500 bg-white rounded border">
                    Belum ada data alat yang tersedia.
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer Sederhana -->
    <footer class="bg-slate-800 text-slate-300 text-sm py-4 text-center mt-auto">
        <p>&copy; <?= date('Y') ?> Aplikasi Peminjaman Alat Sekolah</p>
    </footer>

</body>
</html>