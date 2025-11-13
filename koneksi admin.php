<?php
session_start();
include 'koneksi.php';

$id_karyawan = $_POST['id_karyawan'];
$password    = $_POST['password'];

// Cek data di tabel akun
$query  = "SELECT * FROM akun WHERE id_karyawan='$id_karyawan' AND password='$password'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) > 0) {
    // Login berhasil
    $_SESSION['id_karyawan'] = $id_karyawan;
    header("Location: dashboard.php"); // arahkan ke halaman dashboard
    exit;
} else {
    // Login gagal
    echo "<script>
            alert('ID atau Password salah!');
            window.location.href='admin.php';
          </script>";
}
?>