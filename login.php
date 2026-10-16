<?php
session_start();
require_once __DIR__ . '/config/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = $_POST['password'];
    $role     = $_POST['role'];

    if ($role == 'admin' || $role == 'petugas') {
        // Login untuk Admin / Petugas dari tabel USERS
        $query  = "SELECT * FROM users WHERE (username = '$username' OR nama_lengkap = '$username') AND password = '$password' AND role = '$role'";
        $result = mysqli_query($koneksi, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['id_user']      = $user['id_user'];
            $_SESSION['username']     = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['role']         = $user['role'];

            if (function_exists('catatLog')) {
                catatLog($koneksi, $user['id_user'], "User ".$user['username']." berhasil login.");
            }

            if ($user['role'] == 'admin') {
                header('Location: views/admin/user.php');
            } else {
                header('Location: views/petugas/persetujuan.php');
            }
            exit;
        } else {
            $error = 'Username/Nama, Password, atau Role salah!';
        }

    } else if ($role == 'peminjam') {
        // 1. Cek di tabel PEMINJAM dulu
        $query_pm = "SELECT * FROM peminjam WHERE nama_peminjam = '$username'";
        $res_pm   = mysqli_query($koneksi, $query_pm);

        if ($res_pm && mysqli_num_rows($res_pm) > 0) {
            $pm = mysqli_fetch_assoc($res_pm);
            $_SESSION['id_peminjam']   = $pm['id_peminjam'];
            $_SESSION['nama_peminjam'] = $pm['nama_peminjam'];
            $_SESSION['role']          = 'peminjam';

            header('Location: views/peminjam/katalog.php');
            exit;
        } else {
            // 2. Cek fallback di tabel USERS jika terdaftar sebagai user
            $query_usr = "SELECT * FROM users WHERE (username = '$username' OR nama_lengkap = '$username') AND password = '$password'";
            $res_usr   = mysqli_query($koneksi, $query_usr);

            if ($res_usr && mysqli_num_rows($res_usr) > 0) {
                $usr = mysqli_fetch_assoc($res_usr);
                $_SESSION['id_peminjam']   = $usr['id_user'];
                $_SESSION['nama_peminjam'] = $usr['nama_lengkap'];
                $_SESSION['role']          = 'peminjam';

                header('Location: views/peminjam/katalog.php');
                exit;
            } else {
                $error = 'Data Peminjam tidak ditemukan di database!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Peminjaman Alat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md border border-slate-200">
        <h2 class="text-2xl font-bold text-center text-slate-800 mb-6">Login Sistem</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-200 text-red-700 p-3 rounded-lg mb-4 text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span><?= $error ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-slate-600 text-sm font-medium mb-1">Masuk Sebagai</label>
                <select name="role" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-slate-800">
                    <option value="peminjam">Peminjam / Siswa</option>
                    <option value="petugas">Petugas</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-slate-600 text-sm font-medium mb-1">Username / Nama Peminjam</label>
                <input type="text" name="username" required placeholder="Masukkan username atau nama" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-slate-600 text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" required placeholder="Masukkan password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                Masuk
            </button>
        </form>

        <div class="mt-5 text-center border-t pt-4">
            <a href="index.php" class="text-sm text-blue-600 hover:underline inline-flex items-center gap-1">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali ke Halaman Utama</span>
            </a>
        </div>
    </div>
</body>
</html>