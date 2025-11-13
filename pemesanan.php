<?php include 'koneksi.php';
 ?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>UMKM PUDDINGKU - Pemesanan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    /* ---------- Tema & dasar ---------- */
    :root{
      --pudding-cream:#FFF5E6;
      --pudding-brown:#8B5E3C;
      --pudding-brown-dark:#6b462c;
      --accent:#D9B38C;
    }
    body{ background: #fffaf7; font-family: 'Poppins', sans-serif; color:var(--pudding-brown-dark); }

    /* navbar */
    nav.navbar{ position:relative; z-index:10; }
    .navbar-brand{ color:var(--pudding-brown)!important; font-weight:700; display:flex; align-items:center; gap:.6rem;}
    .navbar-brand img{ width:36px; height:36px; object-fit:cover; border-radius:6px; }

    /* hero */
    .hero{
      position:relative;
      color:#2f1f16;
      text-align:center;
      padding:80px 0;
      background:linear-gradient(rgba(255,245,235,0.6), rgba(255,235,220,0.6)),
                 url('gambar/bg/bg_1 slide.jpg') center/cover no-repeat;
      overflow:hidden;
    }
    .hero::before{
      content:""; position:absolute; inset:0;
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(2px);
      z-index:1;
    }
    .hero .container{ position:relative; z-index:2; }
    .hero h1{ animation: fadeInDown 1s ease; }
    .hero p{ animation: fadeInUp 1.1s ease; }

    @keyframes fadeInDown { from{opacity:0; transform:translateY(-20px)} to{opacity:1; transform:none} }
    @keyframes fadeInUp { from{opacity:0; transform:translateY(20px)} to{opacity:1; transform:none} }

    /* cards */
    .card{ border:none; border-radius:12px; overflow:hidden; transition:transform .18s, box-shadow .18s; }
    .card:hover{ transform:translateY(-6px); box-shadow:0 8px 20px rgba(0,0,0,0.08); }
    .card-img-top{ height:160px; object-fit:cover; }

    /* qty control rapi */
    .qty-control{ display:flex; align-items:center; gap:8px; min-width:110px; justify-content:center; }
    .qty-control button{ width:34px; height:34px; padding:0; display:flex; align-items:center; justify-content:center; }
    .qty-control span{ min-width:26px; text-align:center; font-weight:600; }

    /* category dropdown */
    .category-toggle{ font-size:22px; cursor:pointer; color:var(--pudding-brown); background:none; border:none; }
    .dropdown-menu-custom{ display:none; position:absolute; right:0; background:#fff; border-radius:10px; padding:8px; box-shadow:0 6px 18px rgba(0,0,0,0.08); }
    .dropdown-menu-custom button{ display:block; width:100%; border:none; background:none; text-align:left; padding:8px 12px; border-radius:6px; color:var(--pudding-brown-dark); }
    .dropdown-menu-custom button.active, .dropdown-menu-custom button:hover{ background:var(--pudding-cream); }

    /* toasts positioning */
    .toast-container{ position: fixed; bottom: 16px; right: 16px; z-index: 1200; }

    /* small responsive tweaks */
    @media (max-width:576px){
      .card-img-top{ height:120px; }
      .qty-control{ min-width:90px; gap:6px; }
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg shadow-sm bg-white">
  <div class="container d-flex justify-content-between align-items-center">
    <a class="navbar-brand fw-bold" href="#">
      <img src="gambar/logo_puddingku.png" alt="Logo Puddingku" onerror="this.style.display='none'">
      UMKM PUDDINGKU
    </a>

    <div class="d-flex align-items-center">
      <div class="position-relative me-3">
        <button class="category-toggle" id="categoryToggle" aria-label="Kategori"><i class="bi bi-list"></i></button>
        <div class="dropdown-menu-custom" id="categoryMenu">
          <button data-category="all" class="active">Semua</button>
          <button data-category="Brownies">Brownies</button>
          <button data-category="Cake">Cake</button>
          <button data-category="Cookies">Cookies</button>
          <button data-category="Dessert">Dessert</button>
          <button data-category="Pudding">Pudding</button>
        </div>
      </div>

      <button id="trackOrderBtn" class="btn btn-outline-success me-2" title="Lacak Pesanan"><i class="bi bi-truck"></i></button>

      <button id="openCartBtn" class="btn btn-outline-primary position-relative">
        <i class="bi bi-cart3"></i>
        <span id="cartCount" class="badge bg-danger rounded-pill" style="position:absolute; top:-6px; right:-6px; font-size:.7rem;">0</span>
      </button>
    </div>
  </div>
</nav>

<header class="hero py-5 text-center">
  <div class="container">
    <h1 class="fw-bold">Pemesanan Online - Mudah & Cepat</h1>
    <p class="lead">Pilih produk favoritmu, tambahkan ke keranjang, lalu checkout 🎂</p>
  </div>
</header>

<main class="container my-5">
  <div class="row" id="productList"></div>
</main>

<footer class="py-4 text-center" style="background:var(--pudding-cream); color:var(--pudding-brown);">
  &copy; <span id="year"></span> UMKM PUDDINGKU — Semua Hak Dilindungi 🍮
</footer>

<!-- Modal Keranjang -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Keranjang Anda</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="cartItems"></div>
        <hr>
        <div class="d-flex justify-content-between">
          <strong>Total:</strong>
          <h5 id="cartTotal">Rp0</h5>
        </div>
      </div>
      <div class="modal-footer">
        <button id="clearCartBtn" class="btn btn-outline-danger">Kosongkan</button>
        <button id="checkoutBtn" class="btn btn-primary">Checkout</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Checkout -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Formulir Pemesanan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="checkoutForm" class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nama</label>
          <input required type="text" class="form-control" id="customerName" placeholder="Nama lengkap">
        </div>
        <div class="mb-3">
          <label class="form-label">Alamat / Catatan</label>
          <textarea required id="address" class="form-control" rows="2" placeholder="Alamat pengiriman / catatan"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Nomor Telepon</label>
          <input required type="tel" id="phone" class="form-control" placeholder="08xxxxxxxx">
        </div>
        <div class="mb-3">
          <label class="form-label">Metode Pembayaran</label>
          <select id="paymentMethod" class="form-select">
            <option value="cash">Bayar di tempat (COD)</option>
            <option value="bank">Transfer Bank</option>
            <option value="gopay">E-Wallet</option>
          </select>
        </div>
        <div class="text-end">
          <button type="submit" class="btn btn-success">Kirim Pesanan & Bayar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Toasts -->
<div class="toast-container">
  <div id="addToast" class="toast align-items-center text-bg-primary border-0 mb-2" role="alert" aria-live="polite" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">Produk ditambahkan ke keranjang 🛒</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>

  <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="polite" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">Pesanan berhasil dikirim. Mengarahkan ke Tracking...</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ---------- Data produk (ID unik tiap item) ---------- */
const products = [
  // brownies
  {id: '', name: 'Brownies Burnt Cheese Cake', price: 15000, category: 'Brownies', img: 'gambar/brownies/brownie burnt cheese cake.jpg'},
  {id: '', name: 'Browkies', price: 12000, category: 'Brownies', img: 'gambar/brownies/brownie.jpg'},
  {id: '43567', name: 'Brownies Bites', price: 15000, category: 'Brownies', img: 'gambar/brownies/brownies bites.jpg'},
  // cake
  {id: 'd1', name: 'Blueberry Mouse Cake', price: 15000, category: 'Cake', img: 'gambar/cake/blueberry mouse.jpg'},
  {id: '19800', name: 'Chocolate Mouse Cake', price: 50000, category: 'Cake', img: 'gambar/cake/chocolate mouse cake.jpg'},
  {id: '17965', name: 'Cookies & Cream Mouse', price: 15000, category: 'Cake', img: 'gambar/cake/cookies&cream mouse.jpg'},
  {id: '17113', name: 'Mango Mouse', price: 15000, category: 'Cake', img: 'gambar/cake/manggo mouse.jpg'},
  {id: '19983', name: 'Matcha Mouse Cake', price: 15000, category: 'Cake', img: 'gambar/cake/matcha mouse cake.jpg'},
  {id: '21789', name: 'Peach Mouse Cake', price: 15000, category: 'Cake', img: 'gambar/cake/peach mouse cake.jpg'},
  {id: 'd8', name: 'Choco Mouse Cake 10cm', price: 15000, category: 'Cake', img: 'gambar/cake/shoco mouse cake tart.jpg'},
  {id: 'd9', name: 'Strawberry Mouse Cake', price: 15000, category: 'Cake', img: 'gambar/cake/strawberri mpuse cake.jpg'},
  {id: 'd10', name: 'Strawberry Petite Cake', price: 15000, category: 'Cake', img: 'gambar/cake/strawberry petite cake.jpg'},
  {id: 'd11', name: 'Strawberry Short Cake 10cm', price: 50000, category: 'Cake', img: 'gambar/cake/strawberry short cake.jpg'},
  {id: 'd12', name: 'Taro Mouse Cake', price: 15000, category: 'Cake', img: 'gambar/cake/taro mouse.jpg'},
  {id: 'd13', name: 'Tiramisu Mouse Cake', price: 15000, category: 'Cake', img: 'gambar/cake/tiramisu mouse cake.jpg'},
  // cookies
  {id: 'c1', name: 'Cookies Chococips', price: 5000, category: 'Cookies', img: 'gambar/cookies/cookies_chococips.jpg'},
  {id: 'c2', name: 'Cookies Oatmilk', price: 5000, category: 'Cookies', img: 'gambar/cookies/cookies_oatmilk.jpg'},
  {id: 'c3', name: 'Cookies Oreo', price: 5000, category: 'Cookies', img: 'gambar/cookies/cookies_oreo.jpg'},
  {id: 'c4', name: 'Cookies Chocolate', price: 5000, category: 'Cookies', img: 'gambar/cookies/coookies_chocolate.jpg'},
  {id: 'c5', name: 'Cookies Redvelvet', price: 5000, category: 'Cookies', img: 'gambar/cookies/redvelvet.jpg'},
  // dessert
  {id: 'a1', name: 'Banofee', price: 20000, category: 'Dessert', img: 'gambar/dessert/banafe 2.jpg'},
  {id: 'a2', name: 'Cheese Cuit Strawberry', price: 20000, category: 'Dessert', img: 'gambar/dessert/cheese cuit strawberry.jpg'},
  {id: 'a3', name: 'Dessert Box Keju', price: 20000, category: 'Dessert', img: 'gambar/dessert/dessert box keju.jpg'},
  {id: 'a4', name: 'Milk Bath Chocolate', price: 20000, category: 'Dessert', img: 'gambar/dessert/milk bath dessert box chocolate.jpg'},
  {id: 'a5', name: 'Milk Bath Keju', price: 20000, category: 'Dessert', img: 'gambar/dessert/milk bath dessert box.jpg'},
  {id: 'a6', name: 'Milk Bun', price: 20000, category: 'Dessert', img: 'gambar/dessert/milk bun.jpg'},
  {id: 'a7', name: 'Mille Crepes Chocolate', price: 18000, category: 'Dessert', img: 'gambar/dessert/mille crepes chocolate.jpg'},
  {id: 'a8', name: 'Mille Crepes Strawberry', price: 18000, category: 'Dessert', img: 'gambar/dessert/mille crepes strawberry.jpg'},
  // pudding
  {id: 'b1', name: 'Jerry Cheese Pudding', price: 45000, category: 'Pudding', img: 'gambar/pudding/jerry cheese pudding.jpg'},
  {id: '65489', name: 'Jiggly Pudding Rabbit', price: 10000, category: 'Pudding', img: 'gambar/pudding/jiggly pudding rabbit.jpg'},
  {id: 'b3', name: 'Rainbow Petite Pudding', price: 12000, category: 'Pudding', img: 'gambar/pudding/rainbow petite pudding.jpg'},
  {id: '67221', name: 'Silky Pudding Banana', price: 4000, category: 'Pudding', img: 'gambar/pudding/silky pudding banana.jpg'},
  {id: '12987', name: 'Silky Pudding Leci', price: 4000, category: 'Pudding', img: 'gambar/pudding/silky pudding leci.jpg'},
  {id: '19765', name: 'Silky Pudding Matcha', price: 4000, category: 'Pudding', img: 'gambar/pudding/silky pudding matcha.jpg'},
  {id: '67543', name: 'Silky Pudding Strawberry', price: 4000, category: 'Pudding', img: 'gambar/pudding/silky pudding strawberry.jpg'},
  {id: 'b8', name: 'Silky Pudding Taro', price: 4000, category: 'Pudding', img: 'gambar/pudding/silky pudding taro.jpg'},
  {id: '17490', name: 'Silky Pudding Chocolate', price: 4000, category: 'Pudding', img: 'gambar/pudding/silky pudding_chocolate.jpg'},
  {id: '12098', name: 'Silky Pudding Mango', price: 4000, category: 'Pudding', img: 'gambar/pudding/silky pudding_mango.jpg'},
  {id: 'b11', name: 'Silky Pudding Bubble Gum', price: 4000, category: 'Pudding', img: 'gambar/pudding/silky pudiing_bubble gum.jpg'},
  {id: '11789', name: 'Tosuni Jiggly Pudding', price: 45000, category: 'Pudding', img: 'gambar/pudding/tosuni jiggly  pudding.jpg'},
  {id: 'b13', name: 'Tripple Choco Pudding', price: 8000, category: 'Pudding', img: 'gambar/pudding/tripple choco pudding cup.jpg'}
];
/* ---------- Helper ---------- */
const formatRupiah = n => 'Rp' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
let cart = JSON.parse(localStorage.getItem('umkm_cart') || '{}');

function saveCart(){ localStorage.setItem('umkm_cart', JSON.stringify(cart)); renderCartCount(); renderCartModal(); }
function addToCart(id){
  if(!cart[id]) cart[id]=0;
  cart[id]++;
  saveCart();
  bootstrap.Toast.getOrCreateInstance(document.getElementById('addToast')).show();
}
function removeFromCart(id){ delete cart[id]; saveCart(); }
function changeQty(id, q){ if(q<=0) removeFromCart(id); else cart[id]=q; saveCart(); }
function clearCart(){ cart={}; saveCart(); }
function cartItemsDetailed(){ return Object.keys(cart).map(id=>{ const p=products.find(x=>x.id===id); if(!p) return null; return {...p, qty:cart[id]}; }).filter(Boolean); }

/* ---------- Render produk dengan filter ---------- */
function renderProducts(filter='all'){
  const container=document.getElementById('productList');
  container.classList.add('opacity-0');
  setTimeout(()=> {
    container.innerHTML='';
    const list = products.filter(p => filter==='all' || p.category===filter);
    list.forEach(p=>{
      const col=document.createElement('div'); col.className='col-6 col-md-4 col-lg-3 mb-4';
      col.innerHTML=`
        <div class="card h-100 shadow-sm text-center">
          <img src="${p.img}" class="card-img-top" alt="${p.name}" onerror="this.style.objectFit='cover'">
          <div class="card-body d-flex flex-column justify-content-between">
            <h6 class="fw-semibold">${p.name}</h6>
            <div class="d-flex justify-content-between align-items-center mt-3">
              <strong>${formatRupiah(p.price)}</strong>
              <button class="btn btn-sm btn-primary" onclick="addToCart('${p.id}')"><i class="bi bi-cart-plus"></i></button>
            </div>
          </div>
        </div>
      `;
      container.appendChild(col);
    });
    container.classList.remove('opacity-0');
  }, 120);
}

/* ---------- Render Cart Modal ---------- */
function renderCartModal(){
  const container=document.getElementById('cartItems');
  const totalEl=document.getElementById('cartTotal');
  const items=cartItemsDetailed();
  if(items.length===0){ container.innerHTML='<p class="text-muted">Keranjang kosong.</p>'; totalEl.innerText='Rp0'; return; }
  container.innerHTML='';
  let total=0;
  items.forEach(it=>{
    const subtotal = it.price * it.qty; total += subtotal;
    const row=document.createElement('div'); row.className='d-flex align-items-center justify-content-between mb-3';
    row.innerHTML=`
      <div class="d-flex align-items-center" style="gap:12px;">
        <img src="${it.img}" class="rounded" style="width:70px;height:70px;object-fit:cover;">
        <div>
          <div class="fw-semibold">${it.name}</div>
          <div class="small text-muted">${formatRupiah(it.price)}</div>
        </div>
      </div>
      <div class="qty-control">
        <button class="btn btn-sm btn-outline-secondary" onclick="changeQty('${it.id}', ${it.qty-1})">-</button>
        <span id="qty-${it.id}">${it.qty}</span>
        <button class="btn btn-sm btn-outline-secondary" onclick="changeQty('${it.id}', ${it.qty+1})">+</button>
      </div>
      <div class="text-end" style="width:100px;">${formatRupiah(subtotal)}</div>
    `;
    container.appendChild(row);
  });
  totalEl.innerText = formatRupiah(total);
}

/* ---------- Cart count ---------- */
function renderCartCount(){
  const count = Object.values(cart).reduce((a,b)=>a+b,0);
  document.getElementById('cartCount').innerText = count;
}

/* ---------- DOM Ready ---------- */
document.addEventListener('DOMContentLoaded', ()=>{
  document.getElementById('year').innerText = new Date().getFullYear();
  renderProducts(); renderCartCount();

  const cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
  const checkoutModal = new bootstrap.Modal(document.getElementById('checkoutModal'));

  document.getElementById('openCartBtn').addEventListener('click', ()=>{ renderCartModal(); cartModal.show(); });
  document.getElementById('clearCartBtn').addEventListener('click', ()=>{ if(confirm('Kosongkan keranjang?')) clearCart(); });

  document.getElementById('checkoutBtn').addEventListener('click', ()=>{
    if(Object.keys(cart).length===0) return alert('Keranjang masih kosong!');
    cartModal.hide(); setTimeout(()=> checkoutModal.show(), 200);
  });

  /* Kategori dropdown */
  const catToggle = document.getElementById('categoryToggle');
  const catMenu = document.getElementById('categoryMenu');
  catToggle.addEventListener('click', ()=> catMenu.style.display = catMenu.style.display==='block' ? 'none' : 'block');
  catMenu.querySelectorAll('button').forEach(btn=>{
    btn.addEventListener('click',(e)=>{
      catMenu.querySelectorAll('button').forEach(b=>b.classList.remove('active'));
      e.target.classList.add('active');
      renderProducts(e.target.getAttribute('data-category'));
      catMenu.style.display='none';
    });
  });

  /* Track button open tracking.html */
  document.getElementById('trackOrderBtn').addEventListener('click', ()=> {
    window.location.href = 'tracking.php';
  });

  /* Checkout submit */
  document.getElementById('checkoutForm').addEventListener('submit', async (e)=>{
  e.preventDefault();

  const customer = document.getElementById('customerName').value;
  const address  = document.getElementById('address').value;
  const phone    = document.getElementById('phone').value;
  const payment  = document.getElementById('paymentMethod').value;
  const items    = cartItemsDetailed();

  if(items.length === 0) {
    alert("Keranjang masih kosong!");
    return;
  }

  // Simpan ke database (loop semua item)
 for (let item of items) {
    console.log("DEBUG ITEM:", item);
  const formData = new FormData();

  // ID produk (pastikan nilainya angka, bukan 'p1' atau 'b2')
  formData.append("id_produk", item.id);

  // Jumlah produk
  formData.append("jumlah", item.qty);

  // Bersihkan harga (hapus Rp, titik, dll)
  const cleanPrice = parseInt(item.price.toString().replace(/\D/g, "")) || 0;
  formData.append("harga_pembelian", cleanPrice);

  try {
    const response = await fetch("simpan_pesanan.php", {
      method: "POST",
      body: formData,
    });
    const result = await response.text();
    console.log(result);

    // Menampilkan hasil di konsol atau alert
    alert(result);
  } catch (error) {
    console.error("❌ Gagal kirim data:", error);
    alert("Terjadi kesalahan saat mengirim pesanan!");
  }
}

  // Simpan juga data pesanan lokal (tracking)
  const orderData = { customer, address, phone, payment, items, time: new Date().toISOString() };
  localStorage.setItem('umkm_last_order', JSON.stringify(orderData));
  localStorage.setItem('umkm_tracking_step', '0');
  clearCart();
  bootstrap.Toast.getOrCreateInstance(document.getElementById('successToast')).show();

  setTimeout(()=>{ window.location.href = 'tracking.php'; }, 1200);
});

});
</script>
</body>
</html>