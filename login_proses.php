<?php
include 'koneksi.php';

$id_karyawan = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM akun WHERE id_karyawan='$id_karyawan' AND password='$password'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) > 0) {
    session_start();
    $_SESSION['id_karyawan'] = $id_karyawan;
    echo "success";
} else {
    echo "error";
}
?>