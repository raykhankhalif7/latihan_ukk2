<?php
require_once '../../config/koneksi.php';
require_once '../layout/header.php';

if ($_SESSION['role'] !== 'admin') { header("Location: ../../index.php"); exit; }

if (isset($_POST['tambah'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];
    $n = $_POST['nama_lengkap'];
    $r = $_POST['role'];
    mysqli_query($koneksi, "INSERT INTO users (username, password, nama_lengkap, role) VALUES ('$u', '$p', '$n', '$r')");
    catatLog($koneksi, $_SESSION['id_user'], "Menambahkan user baru: $u");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM users WHERE id_user='$id'");
    catatLog($koneksi, $_SESSION['id_user'], "Menghapus user ID: $id");
}

$users = mysqli_query($koneksi, "SELECT * FROM users");
?>

<h1 class="text-2xl font-bold mb-4">CRUD Data User</h1>

<form method="POST" class="bg-white p-4 rounded shadow mb-6 flex gap-3">
    <input type="text" name="username" placeholder="Username" required class="border p-2 rounded w-1/4">
    <input type="password" name="password" placeholder="Password" required class="border p-2 rounded w-1/4">
    <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required class="border p-2 rounded w-1/4">
    <select name="role" class="border p-2 rounded w-1/4">
        <option value="admin">Admin</option>
        <option value="petugas">Petugas</option>
    </select>
    <button type="submit" name="tambah" class="bg-blue-600 text-white px-4 rounded font-semibold">Tambah</button>
</form>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-slate-200 border-b">
        <tr>
            <th class="p-3 text-left">ID</th>
            <th class="p-3 text-left">Username</th>
            <th class="p-3 text-left">Nama Lengkap</th>
            <th class="p-3 text-left">Role</th>
            <th class="p-3 text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($u = mysqli_fetch_assoc($users)): ?>
        <tr class="border-b">
            <td class="p-3"><?= $u['id_user'] ?></td>
            <td class="p-3"><?= $u['username'] ?></td>
            <td class="p-3"><?= $u['nama_lengkap'] ?></td>
            <td class="p-3 uppercase font-bold text-xs"><?= $u['role'] ?></td>
            <td class="p-3 text-center">
                <a href="?hapus=<?= $u['id_user'] ?>" onclick="return confirm('Hapus user ini?')" class="text-red-600 font-bold">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once '../layout/footer.php'; ?>