<?php
require_once '../../config/koneksi.php';
require_once '../layout/header.php';

if ($_SESSION['role'] !== 'petugas') { header("Location: ../../index.php"); exit; }

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $st = $_GET['aksi'] == 'setujui' ? 'disetujui' : 'ditolak';
    $id_petugas = $_SESSION['id_user'];
    
    mysqli_query($koneksi, "UPDATE peminjaman SET status='$st', id_petugas='$id_petugas' WHERE id_peminjaman='$id'");
    catatLog($koneksi, $id_petugas, "Mengubah status peminjaman ID $id menjadi $st");
}

$query = "SELECT p.*, pm.nama_peminjam FROM peminjaman p JOIN peminjam pm ON p.id_peminjam=pm.id_peminjam WHERE p.status='pending'";
$list = mysqli_query($koneksi, $query);
?>

<h1 class="text-2xl font-bold mb-4">Persetujuan Peminjaman (Pending)</h1>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-slate-200 border-b">
        <tr>
            <th class="p-3 text-left">ID</th>
            <th class="p-3 text-left">Peminjam</th>
            <th class="p-3 text-left">Tgl Pinjam</th>
            <th class="p-3 text-left">Rencana Kembali</th>
            <th class="p-3 text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($r = mysqli_fetch_assoc($list)): ?>
        <tr class="border-b">
            <td class="p-3"><?= $r['id_peminjaman'] ?></td>
            <td class="p-3"><?= $r['nama_peminjam'] ?></td>
            <td class="p-3"><?= $r['tanggal_pinjam'] ?></td>
            <td class="p-3"><?= $r['tanggal_kembali_rencana'] ?></td>
            <td class="p-3 text-center space-x-2">
                <a href="?aksi=setujui&id=<?= $r['id_peminjaman'] ?>" class="bg-green-600 text-white px-3 py-1 rounded text-xs">Setujui</a>
                <a href="?aksi=tolak&id=<?= $r['id_peminjaman'] ?>" class="bg-red-600 text-white px-3 py-1 rounded text-xs">Tolak</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once '../layout/footer.php'; ?>