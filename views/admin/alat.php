<?php
require_once '../../config/koneksi.php';
require_once '../layout/header.php';

if ($_SESSION['role'] !== 'admin') { header("Location: ../../index.php"); exit; }

if (isset($_POST['tambah'])) {
    $kat = $_POST['id_kategori'];
    $nama = $_POST['nama_alat'];
    $stok = $_POST['stok'];
    $kondisi = $_POST['kondisi'];
    mysqli_query($koneksi, "INSERT INTO alat (id_kategori, nama_alat, stok, kondisi) VALUES ('$kat', '$nama', '$stok', '$kondisi')");
    catatLog($koneksi, $_SESSION['id_user'], "Menambahkan alat: $nama");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM alat WHERE id_alat='$id'");
}

$kategori = mysqli_query($koneksi, "SELECT * FROM kategori");
$alat = mysqli_query($koneksi, "SELECT a.*, k.nama_kategori FROM alat a JOIN kategori k ON a.id_kategori=k.id_kategori");
?>

<h1 class="text-2xl font-bold mb-4">CRUD Data Alat</h1>

<form method="POST" class="bg-white p-4 rounded shadow mb-6 grid grid-cols-5 gap-3">
    <select name="id_kategori" class="border p-2 rounded" required>
        <option value="">Pilih Kategori</option>
        <?php while($k = mysqli_fetch_assoc($kategori)): ?>
            <option value="<?= $k['id_kategori'] ?>"><?= $k['nama_kategori'] ?></option>
        <?php endwhile; ?>
    </select>
    <input type="text" name="nama_alat" placeholder="Nama Alat" required class="border p-2 rounded">
    <input type="number" name="stok" placeholder="Stok" required class="border p-2 rounded">
    <input type="text" name="kondisi" placeholder="Kondisi (misal: Baik)" required class="border p-2 rounded">
    <button type="submit" name="tambah" class="bg-blue-600 text-white rounded font-semibold">Tambah</button>
</form>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-slate-200 border-b">
        <tr>
            <th class="p-3 text-left">Kategori</th>
            <th class="p-3 text-left">Nama Alat</th>
            <th class="p-3 text-left">Stok</th>
            <th class="p-3 text-left">Kondisi</th>
            <th class="p-3 text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($a = mysqli_fetch_assoc($alat)): ?>
        <tr class="border-b">
            <td class="p-3"><?= $a['nama_kategori'] ?></td>
            <td class="p-3"><?= $a['nama_alat'] ?></td>
            <td class="p-3"><?= $a['stok'] ?></td>
            <td class="p-3"><?= $a['kondisi'] ?></td>
            <td class="p-3 text-center">
                <a href="?hapus=<?= $a['id_alat'] ?>" onclick="return confirm('Hapus?')" class="text-red-600 font-bold">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once '../layout/footer.php'; ?>