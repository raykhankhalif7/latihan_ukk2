<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role'])) {
    header("Location: ../../index.php");
    exit;
}
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Peminjaman Alat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 min-h-screen">
    <nav class="bg-slate-900 text-white px-6 py-4 flex justify-between items-center shadow-md">
        <div class="font-bold text-xl flex items-center gap-2">
            <i class="fa-solid fa-toolbox text-blue-400"></i> PinjamAlat
        </div>
        <div class="flex items-center gap-4 text-sm">
            <?php if ($role == 'admin'): ?>
                <a href="../admin/user.php" class="hover:text-blue-400">User</a>
                <a href="../admin/kategori.php" class="hover:text-blue-400">Kategori</a>
                <a href="../admin/alat.php" class="hover:text-blue-400">Alat</a>
                <a href="../admin/log.php" class="hover:text-blue-400">Log</a>
            <?php elseif ($role == 'petugas'): ?>
                <a href="../petugas/persetujuan.php" class="hover:text-blue-400">Persetujuan</a>
                <a href="../petugas/pengembalian.php" class="hover:text-blue-400">Pengembalian</a>
                <a href="../petugas/cetak.php" class="hover:text-blue-400">Cetak Laporan</a>
            <?php elseif ($role == 'peminjam'): ?>
                <a href="../peminjam/katalog.php" class="hover:text-blue-400">Katalog Alat</a>
                <a href="../peminjam/riwayat.php" class="hover:text-blue-400">Riwayat Saya</a>
            <?php endif; ?>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-xs text-slate-300">
                <?= isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : $_SESSION['nama_peminjam'] ?>
                (<b class="uppercase text-blue-400"><?= $role ?></b>)
            </span>
            <a href="../../logout.php" class="bg-red-600 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-red-700">
                Logout
            </a>
        </div>
    </nav>
    <div class="p-6 max-w-7xl mx-auto">