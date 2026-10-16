<?php
require_once '../../config/koneksi.php';
require_once '../layout/header.php';

if ($_SESSION['role'] !== 'petugas') { header("Location: ../../index.php"); exit; }

if (isset($_POST['proses'])) {
    $id_p = $_POST['id_peminjaman'];
    $tgl = date('Y-m-d');
    $status_k = $_POST['status_pengembalian'];
    $denda = ($status_k == 'rusak') ? 50000 : (($status_k == 'hilang') ? 150000 : 0);

    mysqli_query($koneksi, "INSERT INTO pengembalian (id_peminjaman, tanggal_dikembalikan, denda, status_pengembalian) VALUES ('$id_p', '$tgl', '$denda', '$status_k')");
    mysqli_query($koneksi, "UPDATE peminjaman SET status='selesai' WHERE id_peminjaman='$id_p'");
    
    catatLog($koneksi, $_SESSION['id_user'], "Memproses pengembalian ID $id_p ($status_k)");
}

$query = "SELECT p.*, pm.nama_peminjam FROM peminjaman p JOIN peminjam pm ON p.id_peminjam=pm.id_peminjam WHERE p.status='disetujui'";
$list = mysqli_query($koneksi, $query);
?>

<h1 class="text-2xl font-bold mb-4">Memantau & Memproses Pengembalian</h1>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-slate-200 border-b">
        <tr>
            <th class="p-3 text-left">Peminjam</th>
            <th class="p-3 text-left">Tgl Pinjam</th>
            <th class="p-3 text-left">Kondisi Pengembalian</th>
            <th class="p-3 text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($r = mysqli_fetch_assoc($list)): ?>
        <tr class="border-b">
            <td class="p-3"><?= $r['nama_peminjam'] ?></td>
            <td class="p-3"><?= $r['tanggal_pinjam'] ?></td>
            <form method="POST">
                <input type="hidden" name="id_peminjaman" value="<?= $r['id_peminjaman'] ?>">
                <td class="p-3">
                    <select name="status_pengembalian" class="border p-1 rounded text-sm">
                        <option value="baik">Baik (Rp 0)</option>
                        <option value="rusak">Rusak (Denda Rp 50.000)</option>
                        <option value="hilang">Hilang (Denda Rp 150.000)</option>
                    </select>
                </td>
                <td class="p-3 text-center">
                    <button type="submit" name="proses" class="bg-blue-600 text-white px-3 py-1 rounded text-xs">Selesaikan</button>
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once '../layout/footer.php'; ?>