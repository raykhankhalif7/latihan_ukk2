<?php
require_once '../../config/koneksi.php';
require_once '../layout/header.php';

if ($_SESSION['role'] !== 'peminjam') { header("Location: ../../index.php"); exit; }

$id_pm = $_SESSION['id_peminjam'];
$query = "SELECT p.*, pg.denda, pg.status_pengembalian 
          FROM peminjaman p 
          LEFT JOIN pengembalian pg ON p.id_peminjaman=pg.id_peminjaman 
          WHERE p.id_peminjam='$id_pm'";
$riwayat = mysqli_query($koneksi, $query);
?>

<h1 class="text-2xl font-bold mb-4">Riwayat Peminjaman Saya</h1>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-slate-200 border-b">
        <tr>
            <th class="p-3 text-left">ID Transaksi</th>
            <th class="p-3 text-left">Tanggal Pinjam</th>
            <th class="p-3 text-left">Rencana Kembali</th>
            <th class="p-3 text-left">Status</th>
            <th class="p-3 text-left">Denda</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($r = mysqli_fetch_assoc($riwayat)): ?>
        <tr class="border-b">
            <td class="p-3"><?= $r['id_peminjaman'] ?></td>
            <td class="p-3"><?= $r['tanggal_pinjam'] ?></td>
            <td class="p-3"><?= $r['tanggal_kembali_rencana'] ?></td>
            <td class="p-3 uppercase font-bold text-xs"><?= $r['status'] ?></td>
            <td class="p-3">Rp <?= number_format($r['denda'] ?: 0, 0, ',', '.') ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once '../layout/footer.php'; ?>