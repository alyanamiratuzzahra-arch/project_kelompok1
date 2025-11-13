<?php include 'koneksi.php'; ?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PUDDINGKU - Admin Dashboard</title>
  <meta name="description" content="Demo admin dashboard untuk UMKM Puddingku - daftar pesanan, profit, tracking order, dan login admin (static demo).">
  <style>
    :root{
      --bg:#0f1724; --card:#111827; --muted:#9ca3af; --accent:#f97316; --accent-2:#fb923c; --glass: rgba(255,255,255,0.03);
      --success:#10b981; --danger:#ef4444; --surface:#0b1220; --white:#fff;
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    }
    *{box-sizing:border-box}
    body{margin:0;background:linear-gradient(180deg,#071024 0%, #071821 50%);color:var(--white);min-height:100vh}

    /* Layout */
    .app{display:grid;grid-template-columns:260px 1fr;gap:24px;padding:28px}
    .sidebar{background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));padding:20px;border-radius:14px;height:calc(100vh - 56px);position:sticky;top:28px}
    .brand{display:flex;gap:12px;align-items:center;margin-bottom:20px}
    .logo{width:48px;height:48px;border-radius:10px;background:linear-gradient(135deg,var(--accent),var(--accent-2));display:flex;align-items:center;justify-content:center;font-weight:700}
    .logo span{font-size:20px}
    .brand h1{margin:0;font-size:18px}
    .brand p{margin:0;color:var(--muted);font-size:12px}
    nav a{display:flex;align-items:center;gap:12px;padding:10px;border-radius:10px;color:var(--muted);text-decoration:none;margin-bottom:6px}
    nav a.active, nav a:hover{background:var(--glass);color:var(--white)}

    .main{padding:6px 10px}
    .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}
    .header-left{display:flex;gap:12px;align-items:center}
    .search{background:transparent;border:1px solid rgba(255,255,255,0.06);padding:8px 10px;border-radius:10px;color:var(--white);min-width:360px}
    .user{display:flex;gap:12px;align-items:center}
    .avatar{width:42px;height:42px;border-radius:10px;background:linear-gradient(135deg,#ef9a9a,#f48fb1);display:flex;align-items:center;justify-content:center}

    /* cards */
    .row{display:flex;gap:18px;margin-bottom:18px}
    .card{background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));padding:18px;border-radius:12px;flex:1}
    .kpi{display:flex;justify-content:space-between;align-items:center}
    .kpi .title{font-size:12px;color:var(--muted)}
    .kpi .value{font-size:22px;font-weight:700}

    /* Orders table */
    .table{width:100%;border-collapse:collapse;margin-top:12px}
    .table th, .table td{padding:10px 12px;text-align:left;font-size:14px;border-bottom:1px solid rgba(255,255,255,0.03)}
    .table th{color:var(--muted);font-weight:600}
    .status{padding:6px 8px;border-radius:8px;font-weight:600;font-size:13px}
    .status.pending{background:rgba(245,158,11,0.12);color:#f59e0b}
    .status.prep{background:rgba(59,130,246,0.08);color:#3b82f6}
    .status.ship{background:rgba(16,185,129,0.08);color:#10b981}
    .status.cancel{background:rgba(239,68,68,0.08);color:#ef4444}

    .btn{display:inline-block;padding:8px 12px;border-radius:8px;background:var(--accent);color:#111827;font-weight:700;border:none;cursor:pointer}
    .btn.ghost{background:transparent;border:1px solid rgba(255,255,255,0.06);color:var(--white)}

    /* Modal / right panel */
    .drawer{position:fixed;right:18px;top:80px;width:420px;height:calc(100vh - 160px);background-color: #071024;border-radius:12px;padding:16px;box-shadow:0 10px 30px rgba(2,6,23,0.6);overflow:auto;display:none}
    .drawer.open{display:block}
    .drawer h3{margin-top:0}

    /* Login screen */
    .login-wrap{position:fixed;inset:0;display:flex;align-items:center;justify-content:center;background:linear-gradient(180deg, rgba(2,6,23,0.7), rgba(2,6,23,0.85));backdrop-filter: blur(4px)}
    .login{background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));padding:28px;border-radius:12px;width:420px}
    .login h2{margin:0 0 6px 0}
    .form-row{margin-bottom:12px}
    label{display:block;color:var(--muted);font-size:13px;margin-bottom:6px}
    input[type=text], input[type=password], select{width:100%;padding:10px;border-radius:8px;border:1px solid rgba(255,255,255,0.04);background:transparent;color:var(--white)}

    /* small */
    .muted{color:var(--muted);font-size:13px}
    .order-actions{display:flex;gap:8px;align-items:center}

    /* responsive */
    @media (max-width:900px){.app{grid-template-columns:1fr;}.sidebar{position:relative;height:auto}.search{min-width:0;width:100%}}
  </style>
</head>
<body>

  <div id="loginScreen" class="login-wrap" aria-hidden="false">
    <div class="login" role="dialog" aria-label="Admin login">
      <h2>PUDDINGKU Admin</h2>
      <p class="muted">Masuk untuk mengelola pesanan, profit, dan tracking order.</p>
      <div class="form-row">
        <label>Username</label>
        <input id="username" type="text" value="" placeholder="admin">
      </div>
      <div class="form-row">
        <label>Password</label>
        <input id="password" type="password" placeholder="••••••••">
      </div>
      <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:10px">
        <button id="loginBtn" class="btn">Login</button>
        <button id="demoBtn" class="btn ghost">Login Demo</button>
      </div>
      <p class="muted" style="margin-top:12px;font-size:12px">Credentials demo: <strong>admin</strong> / <strong>puddingku123</strong></p>
    </div>
  </div>

  <div id="app" class="app" style="display:none">
    <aside class="sidebar">
      <div class="brand">
        <div class=""><span></span></div>
        <img src="../file UMKM/image/logo.png" width="50" height="50">
        <div>
          <h1>UMKM PUDDINGKU</h1>
          <p>Admin Dashboard</p>
        </div>
      </div>
      <nav>
        <a href="#" class="active" data-section="dashboard">Dashboard</a>
        <a href="#" data-section="orders">Daftar Pesanan</a>
        <a href="#" data-section="products">Produk (demo)</a>
        <a href="#" data-section="settings">Pengaturan</a>
        <a href="#" id="logoutLink">Logout</a>
      </nav>
      <div style="position:absolute;bottom:20px;left:20px;right:20px">
        <div class="muted">Version demo • static</div>
      </div>
    </aside>

    <main class="main">
      <div class="header">
        <div class="header-left">
          <input id="search" class="search" placeholder="Cari pesanan atau pelanggan...">
        </div>
        <div class="user">
          <div style="text-align:right;margin-right:8px">
            <div class="muted">Halo, Admin</div>
            <div style="font-weight:700">Puddingku</div>
          </div>
          <div class=""></div>
          <img src="../file UMKM/image/WhatsApp_Image_2025-11-11_at_08.29.11_059bab4d-removebg-preview.png" width="50px" height="50px">
        </div>
      </div>

      <!-- dashboard -->
      <section id="section-dashboard">
        <div class="row">
          <div class="card" style="flex:2">
            <div style="display:flex;justify-content:space-between;align-items:center">
              <div>
                <div class="muted">Ringkasan Hari Ini</div>
                <h2 id="todayOrders">0 Pesanan</h2>
                <div class="muted">Total profit: <strong id="todayProfit">Rp0</strong></div>
              </div>
              <div style="text-align:right">
                <div class="muted">Total profit (semua waktu)</div>
                <div style="font-weight:700;font-size:22px;margin-top:6px"> <span id="totalProfit">Rp0</span></div>
                <div style="margin-top:8px"><button id="exportBtn" class="btn ghost">Export JSON</button></div>
              </div>
            </div>

            <div style="margin-top:16px">
              <h3 style="margin:0">Pesanan terbaru</h3>
              <table class="table" id="recentTable">
                <thead>
                  <tr><th>No</th><th>Pelanggan</th><th>Items</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>

          <div style="width:320px;display:flex;flex-direction:column;gap:12px">
            <div class="card">
              <div class="kpi"><div>
                <div class="title">Pesanan Pending</div>
                <div class="value" id="cntPending">0</div>
              </div><div style="font-size:12px;color:var(--muted)">Update otomatis</div></div>
            </div>
            <div class="card">
              <div class="kpi"><div>
                <div class="title">Sedang Diproses</div>
                <div class="value" id="cntPrep">0</div>
              </div><div style="font-size:12px;color:var(--muted)">Status</div></div>
            </div>
            <div class="card">
              <div class="kpi"><div>
                <div class="title">Terkirim</div>
                <div class="value" id="cntShip">0</div>
              </div><div style="font-size:12px;color:var(--muted)">Selesai</div></div>
            </div>
          </div>
        </div>
      </section>

      <!-- orders -->
      <section id="section-orders" style="display:none">
        <div class="card">
          <h3>Daftar Pesanan</h3>
          <div style="margin-top:10px">
            <table class="table" id="ordersTable">
              <thead>
                <tr><th>No</th><th>Waktu</th><th>Pelanggan</th><th>Items</th><th>Total</th><th>Status</th><th>Tracking</th><th>Aksi</th></tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- products (demo) -->
      <section id="section-products" style="display:none">
        <div class="card">
          <h3>Produk (demo)</h3>
          <div class="muted">Daftar produk hanya untuk tampilan - tidak mempengaruhi backend</div>
          <ul id="productList"></ul>
        </div>
      </section>

      <section id="section-settings" style="display:none">
        <div class="card">
          <h3>Pengaturan</h3>
          <p class="muted">Di demo ini pengaturan terbatas. Data disimpan di localStorage browser.</p>
          <div style="margin-top:12px">
            <button id="resetData" class="btn ghost">Reset Data Demo</button>
          </div>
        </div>
      </section>

    </main>

    <aside id="drawer" class="drawer" aria-hidden="true">
      <div style="display:flex;justify-content:space-between;align-items:center">
        <h3>Detail Pesanan</h3>
        <button id="closeDrawer" class="btn ghost">Tutup</button>
      </div>
      <div id="drawerContent" style="margin-top:8px"></div>
    </aside>
  </div>

  <script>
    /* ---------- Demo data & persistence ---------- */
    const DEMO_USER = {username:'admin', password:'puddingku123'};
    const SAMPLE_ORDERS = [
      {id: 'PUD-1001', created: '2025-11-07 09:12', customer: 'Siti', phone: '081234567890', items:[{name:'Pudding Coklat', qty:2, price:12000},{name:'Pudding Keju', qty:1, price:15000}], total:39000, profit:15000, status:'pending', tracking:'Menunggu konfirmasi'},
      {id: 'PUD-1002', created: '2025-11-07 10:40', customer: 'Budi', phone: '082233445566', items:[{name:'Pudding Matcha', qty:3, price:10000}], total:30000, profit:12000, status:'prep', tracking:'Sedang dikemas'},
      {id: 'PUD-1003', created: '2025-11-06 18:22', customer: 'Rina', phone: '081398765432', items:[{name:'Pudding Oreo', qty:1, price:13000}], total:13000, profit:5000, status:'ship', tracking:'Dalam pengiriman'}
    ];

    function readData(){
      const raw = localStorage.getItem('puddingku_data');
      if(!raw){
        const seed = {orders:SAMPLE_ORDERS, products:[{name:'Pudding Coklat'},{name:'Pudding Keju'},{name:'Pudding Matcha'},{name:'Pudding Oreo'}]};
        localStorage.setItem('puddingku_data', JSON.stringify(seed));
        return seed;
      }
      try{return JSON.parse(raw);}catch(e){localStorage.removeItem('puddingku_data');return readData();}
    }
    function saveData(data){localStorage.setItem('puddingku_data', JSON.stringify(data));}

    let state = readData();

    /* ---------- Auth ---------- */
    function showApp(){document.getElementById('loginScreen').style.display='none';document.getElementById('app').style.display='grid';renderAll();}
    function logout(){localStorage.removeItem('puddingku_admin_auth');location.reload();}
    document.getElementById('logoutLink').addEventListener('click', (e)=>{e.preventDefault();logout();});

    // login handlers
    document.getElementById('loginBtn').addEventListener('click', ()=>{
      const u = document.getElementById('username').value.trim();
      const p = document.getElementById('password').value.trim();
     fetch('login_proses.php', {
  method: 'POST',
  headers: {'Content-Type': 'application/x-www-form-urlencoded'},
  body: `username=${encodeURIComponent(u)}&password=${encodeURIComponent(p)}`
})
.then(res => res.text())
.then(res => {
  if(res.trim() === 'success'){
    localStorage.setItem('puddingku_admin_auth', JSON.stringify({user:u, ts:Date.now()}));
    showApp();
  } else {
    alert('ID Karyawan atau Password salah!');
  }
})
.catch(err => alert('Gagal terhubung ke server.'));
    });
    document.getElementById('demoBtn').addEventListener('click', ()=>{document.getElementById('username').value='admin';document.getElementById('password').value='puddingku123';document.getElementById('loginBtn').click();});

    // auto-login if already auth
    if(localStorage.getItem('puddingku_admin_auth')){
      showApp();
    }

    /* ---------- Navigation ---------- */
    document.querySelectorAll('nav a[data-section]').forEach(a=>a.addEventListener('click', (e)=>{
      e.preventDefault();document.querySelectorAll('nav a').forEach(x=>x.classList.remove('active'));a.classList.add('active');const sec=a.dataset.section;document.querySelectorAll('main section').forEach(s=>s.style.display='none');document.getElementById('section-'+sec).style.display='block';
      // close drawer if open
      closeDrawer();
    }));

    /* ---------- Rendering ---------- */
    function formatRupiah(n){return 'Rp'+n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}

    function calcCounts(){
      const orders = state.orders||[];
      const cntPending = orders.filter(o=>o.status==='pending').length;
      const cntPrep = orders.filter(o=>o.status==='prep').length;
      const cntShip = orders.filter(o=>o.status==='ship').length;
      const today = new Date().toISOString().slice(0,10);
      const todayOrders = orders.filter(o=>o.created && o.created.startsWith(today));
      const totalProfit = orders.reduce((s,o)=>s+(o.profit||0),0);
      const todayProfit = todayOrders.reduce((s,o)=>s+(o.profit||0),0);
      document.getElementById('cntPending').innerText=cntPending;
      document.getElementById('cntPrep').innerText=cntPrep;
      document.getElementById('cntShip').innerText=cntShip;
      document.getElementById('totalProfit').innerText=formatRupiah(totalProfit);
      document.getElementById('todayProfit').innerText=formatRupiah(todayProfit);
      document.getElementById('todayOrders').innerText=(todayOrders.length)+' Pesanan';
    }

    function renderRecent(){
      const tbody = document.querySelector('#recentTable tbody');tbody.innerHTML='';
      const orders = state.orders.slice().reverse().slice(0,6);
      orders.forEach((o,i)=>{
        const tr=document.createElement('tr');
        tr.innerHTML=`<td>${i+1}</td><td>${o.customer}</td><td>${o.items.length}</td><td>${formatRupiah(o.total)}</td><td><span class="status ${o.status}">${o.status.toUpperCase()}</span></td><td><div class="order-actions"><button class="btn ghost" data-id="${o.id}" data-action="view">Lihat</button></div></td>`;
        tbody.appendChild(tr);
      });
    }
      async function renderOrdersTable(){
  const tbody = document.querySelector('#ordersTable tbody');
  tbody.innerHTML = '<tr><td colspan="7">Memuat data pesanan...</td></tr>';

  try {
    const res = await fetch('get_pesanan.php');
    const data = await res.json();
    tbody.innerHTML = '';

    if (data.length === 0) {
      tbody.innerHTML = '<tr><td colspan="7">Belum ada pesanan.</td></tr>';
      return;
    }

    data.forEach((p, i) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${i + 1}</td>
        <td>${p.waktu}</td>
        <td>${p.id_produk}</td>
        <td>${p.jumlah}</td>
        <td>Rp${parseInt(p.subtotal).toLocaleString('id-ID')}</td>
        <td><span class="status ship">SELESAI</span></td>
        <td><button class="btn ghost">Detail</button></td>
      `;
      tbody.appendChild(tr);
    });
  } catch (err) {
    console.error(err);
    tbody.innerHTML = '<tr><td colspan="7">Gagal memuat data dari server.</td></tr>';
  }
}
      // set selects and attach listeners
      document.querySelectorAll('.trackingSelect').forEach(sel=>{
        const id=sel.dataset.id; const o=state.orders.find(x=>x.id===id);
        if(o) sel.value=o.tracking||'Menunggu konfirmasi';
        sel.addEventListener('change', (e)=>{ const val=e.target.value; const order=state.orders.find(x=>x.id===id); if(order){order.tracking=val; if(val==='Selesai'){order.status='ship'} else if(val==='Sedang dikemas'){order.status='prep'} else if(val==='Menunggu konfirmasi'){order.status='pending'} else {order.status='prep'}; saveData(state); renderAll(); alert('Tracking diubah: '+id+' → '+val);} });
      });

      // view buttons
      document.querySelectorAll('button[data-action="view"]').forEach(b=>b.addEventListener('click', (e)=>{const id=b.dataset.id;openDrawer(id);}));
    
async function renderProducts(){
  const section = document.getElementById('section-products');
  const card = section.querySelector('.card');
  card.innerHTML = `
    <h3>Daftar Produk</h3>
    <div style="margin-top:10px;overflow-x:auto;">
      <table class="table" id="produkTable">
        <thead>
          <tr><th>No</th><th>Nama Produk</th><th>Harga</th><th>Stok</th></tr>
        </thead>
        <tbody>
          <tr><td colspan="4">Memuat data produk...</td></tr>
        </tbody>
      </table>
    </div>
  `;

  try {
    const res = await fetch('get_produk.php');
    const data = await res.json();
    const tbody = document.querySelector('#produkTable tbody');
    tbody.innerHTML = '';

    if (data.length === 0) {
      tbody.innerHTML = '<tr><td colspan="4">Belum ada produk.</td></tr>';
    } else {
      data.forEach((p, i) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${i + 1}</td>
          <td>${p.nama_produk}</td>
          <td>Rp${parseInt(p.harga).toLocaleString('id-ID')}</td>
          <td>${p.stock}</td>
        `;
        tbody.appendChild(tr);
      });
    }
  } catch (err) {
    const tbody = document.querySelector('#produkTable tbody');
    tbody.innerHTML = '<tr><td colspan="4">Gagal memuat data produk.</td></tr>';
    console.error(err);
  }
}
    function renderRecentTableForDashboard(){renderRecent();}

    function renderAll(){calcCounts();renderRecentTableForDashboard();renderOrdersTable();renderProducts();}

    /* ---------- Drawer (order detail) ---------- */
    const drawer=document.getElementById('drawer');
    function openDrawer(id){const order=state.orders.find(o=>o.id===id);if(!order) return;drawer.classList.add('open');drawer.setAttribute('aria-hidden','false');const content=document.getElementById('drawerContent');content.innerHTML='';
      const html = [];
      html.push('<div class="muted">ID: '+order.id+'</div>');
      html.push('<h3>'+order.customer+'</h3>');
      html.push('<div class="muted">'+order.created+'</div>');
      html.push('<div style="margin-top:8px"><strong>Items</strong><ul>');
      order.items.forEach(it=>{html.push('<li>'+it.name+' x'+it.qty+' — '+formatRupiah(it.price*it.qty)+'</li>')});
      html.push('</ul></div>');
      html.push('<div style="margin-top:8px"><strong>Total: </strong>'+formatRupiah(order.total)+'</div>');
      html.push('<div style="margin-top:8px"><label class="muted">Status</label><select id="statusSelect"><option value="pending">Pending</option><option value="prep">Prep</option><option value="ship">Ship</option><option value="cancel">Cancel</option></select></div>');
      html.push('<div style="margin-top:8px"><label class="muted">Tracking</label><input id="trackingInput" value="'+(order.tracking||'')+'"/></div>');
      html.push('<div style="margin-top:12px;display:flex;gap:8px;justify-content:flex-end"><button id="saveOrderBtn" class="btn">Simpan</button><button id="deleteOrderBtn" class="btn ghost">Hapus</button></div>');
      content.innerHTML=html.join('');
      document.getElementById('statusSelect').value=order.status;
      document.getElementById('saveOrderBtn').addEventListener('click', ()=>{order.status=document.getElementById('statusSelect').value;order.tracking=document.getElementById('trackingInput').value;saveData(state);renderAll();alert('Perubahan disimpan.');});
      document.getElementById('deleteOrderBtn').addEventListener('click', ()=>{if(confirm('Hapus pesanan '+order.id+'?')){state.orders=state.orders.filter(x=>x.id!==order.id);saveData(state);renderAll();closeDrawer();}});
    }
    function closeDrawer(){drawer.classList.remove('open');drawer.setAttribute('aria-hidden','true');}
    document.getElementById('closeDrawer').addEventListener('click', closeDrawer);

    /* ---------- Search */
    document.getElementById('search').addEventListener('input', (e)=>{const q=e.target.value.trim().toLowerCase(); if(!q){renderOrdersTable();return;} const tbody=document.querySelector('#ordersTable tbody');tbody.innerHTML=''; state.orders.filter(o=>o.customer.toLowerCase().includes(q) || o.id.toLowerCase().includes(q) || (o.phone||'').includes(q)).forEach((o,idx)=>{const tr=document.createElement('tr');tr.innerHTML=`<td>${idx+1}</td><td>${o.created}</td><td>${o.customer}<div class="muted">${o.phone||''}</div></td><td>${o.items.map(it=>it.name+' x'+it.qty).join('<br>')}</td><td>${formatRupiah(o.total)}</td><td><span class="status ${o.status}">${o.status.toUpperCase()}</span></td><td>${o.tracking||''}</td><td><button class="btn" data-id="${o.id}" data-action="view">Detail</button></td>`;tbody.appendChild(tr);});
      document.querySelectorAll('button[data-action="view"]').forEach(b=>b.addEventListener('click', (e)=>{openDrawer(b.dataset.id);}));
    });

    /* ---------- Export / reset ---------- */
    document.getElementById('exportBtn').addEventListener('click', ()=>{const data = JSON.stringify(state, null, 2);const blob=new Blob([data],{type:'application/json'});const url=URL.createObjectURL(blob);const a=document.createElement('a');a.href=url;a.download='puddingku_data.json';a.click();URL.revokeObjectURL(url);});

    document.getElementById('resetData').addEventListener('click', ()=>{if(confirm('Reset data demo ke keadaan awal?')){localStorage.removeItem('puddingku_data');state=readData();renderAll();alert('Reset selesai.')}});

    document.getElementById('closeDrawer').addEventListener('click', closeDrawer);

    /* ---------- Init attach events for recent view buttons (delegation) ---------- */
    document.addEventListener('click', (e)=>{
      const btn = e.target.closest('button[data-action]'); if(!btn) return; const action=btn.dataset.action; const id=btn.dataset.id; if(action==='view') openDrawer(id);
    });

    /* ---------- export for debugging ---------- */
    window._puddingku_state = state;

    // initial render already handled by showApp when authed or after login
  </script>
</body>
</html>