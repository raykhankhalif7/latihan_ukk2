<?php
require_once '../../config/koneksi.php';
require_once '../layout/header.php';

if ($_SESSION['role'] !== 'admin') { header("Location: ../../index.php"); exit; }

if (isset($_POST['tambah'])) {
    $k = $_POST['nama_kategori'];
    mysqli_query($koneksi, "INSERT INTO kategori (nama_kategori) VALUES ('$k')");
    catatLog($koneksi, $_SESSION['id_user'], "Menambahkan kategori: $k");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM kategori WHERE id_kategori='$id'");
}

$kategori = mysqli_query($koneksi, "SELECT * FROM kategori");
?>

<h1 class="text-2xl font-bold mb-4">CRUD Data Kategori</h1>

<form method="POST" class="bg-white p-4 rounded shadow mb-6 flex gap-3">
    <input type="text" name="nama_kategori" placeholder="Nama Kategori" required class="border p-2 rounded flex-1">
    <button type="submit" name="tambah" class="bg-blue-600 text-white px-4 rounded font-semibold">Tambah</button>
</form>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-slate-200 border-b">
        <tr>
            <th class="p-3 text-left">ID</th>
            <th class="p-3 text-left">Nama Kategori</th>
            <th class="p-3 text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($k = mysqli_fetch_assoc($kategori)): ?>
        <tr class="border-b">
            <td class="p-3"><?= $k['id_kategori'] ?></td>
            <td class="p-3"><?= $k['nama_kategori'] ?></td>
            <td class="p-3 text-center">
                <a href="?hapus=<?= $k['id_kategori'] ?>" onclick="return confirm('Hapus?')" class="text-red-600 font-bold">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once '../layout/footer.php'; ?>