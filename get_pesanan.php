<?php
include 'koneksi.php';
header('Content-Type: application/json');

// ambil data dari tabel detail_pembelian
$sql = "SELECT id_detail_pembelian, id_produk, jumlah, subtotal, waktu FROM detail_pembelian ORDER BY waktu DESC";
$result = $koneksi->query($sql);

$data = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }
}

echo json_encode($data);
?>