<?php
require_once '../../config/koneksi.php';
require_once '../layout/header.php';

if ($_SESSION['role'] !== 'peminjam') { header("Location: ../../index.php"); exit; }

if (isset($_POST['pinjam'])) {
    $id_pm = $_SESSION['id_peminjam'];
    $id_alat = $_POST['id_alat'];
    $jumlah = $_POST['jumlah'];
    $tgl_pinjam = date('Y-m-d');
    $tgl_kembali = $_POST['tgl_kembali'];

    mysqli_query($koneksi, "INSERT INTO peminjaman (id_peminjam, tanggal_pinjam, tanggal_kembali_rencana, status) VALUES ('$id_pm', '$tgl_pinjam', '$tgl_kembali', 'pending')");
    $id_peminjaman = mysqli_insert_id($koneksi);

    mysqli_query($koneksi, "INSERT INTO detail_peminjaman (id_peminjaman, id_alat, jumlah) VALUES ('$id_peminjaman', '$id_alat', '$jumlah')");
    
    echo "<script>alert('Pengajuan peminjaman berhasil dikirim!');</script>";
}

$alat = mysqli_query($koneksi, "SELECT a.*, k.nama_kategori FROM alat a JOIN kategori k ON a.id_kategori=k.id_kategori");
?>

<h1 class="text-2xl font-bold mb-6">Katalog Alat Sedia Ada</h1>

<div class="grid grid-cols-3 gap-6">
    <?php while($a = mysqli_fetch_assoc($alat)): ?>
    <div class="bg-white p-5 rounded-lg shadow border border-slate-200">
        <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded"><?= $a['nama_kategori'] ?></span>
        <h3 class="font-bold text-lg mt-2"><?= $a['nama_alat'] ?></h3>
        <p class="text-sm text-slate-500 my-1">Kondisi: <?= $a['kondisi'] ?> | Stok: <?= $a['stok'] ?></p>
        
        <form method="POST" class="mt-4 space-y-2">
            <input type="hidden" name="id_alat" value="<?= $a['id_alat'] ?>">
            <input type="number" name="jumlah" min="1" max="<?= $a['stok'] ?>" value="1" required class="w-full border p-1 rounded text-sm" placeholder="Jumlah">
            <input type="date" name="tgl_kembali" required class="w-full border p-1 rounded text-sm">
            <button type="submit" name="pinjam" class="w-full bg-blue-600 text-white py-1.5 rounded text-sm font-semibold">Ajukan Pinjam</button>
        </form>
    </div>
    <?php endwhile; ?>
</div>

<?php require_once '../layout/footer.php'; ?>