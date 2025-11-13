<?php
include 'koneksi.php';

// ambil data produk dari tabel
$query = "SELECT * FROM produk";
$result = mysqli_query($koneksi, $query);

$produk = [];
while ($row = mysqli_fetch_assoc($result)) {
    $produk[] = $row;
}

// kirim data dalam format JSON
header('Content-Type: application/json');
echo json_encode($produk);
?>