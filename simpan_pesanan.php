<?php
$koneksi = mysqli_connect("localhost", "root", "", "umkm_terbaru", 3310);

// Cek koneksi
if (!$koneksi) {
  die("❌ Koneksi gagal: " . mysqli_connect_error());
}

// Ambil data dari POST
$id_produk = $_POST['id_produk'] ?? '';
$jumlah = (int)($_POST['jumlah'] ?? 0);
$harga_pembelian = (int)($_POST['harga_pembelian'] ?? 0);

// Validasi dasar
if (empty($id_produk) || $jumlah <= 0 || $harga_pembelian <= 0) {
  die("❌ Data tidak valid atau tidak lengkap.");
}

// Hitung subtotal
$subtotal = $jumlah * $harga_pembelian;

// Siapkan query (4 tanda tanya)
$query = "INSERT INTO detail_pembelian (id_produk, jumlah, harga_pembelian, subtotal, waktu)
          VALUES (?, ?, ?, ?, NOW())";
$stmt = mysqli_prepare($koneksi, $query);

// Binding (4 variabel total)
mysqli_stmt_bind_param($stmt, "siii", $id_produk, $jumlah, $harga_pembelian, $subtotal);

// Eksekusi dan cek hasil
if (mysqli_stmt_execute($stmt)) {
  echo "✅ Pesanan berhasil disimpan ke database.";
} else {
  echo "❌ Gagal menyimpan ke database: " . mysqli_error($koneksi);
}

// Tutup koneksi
mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>