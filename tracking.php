<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Tracking Order - Puddingku</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root{
      --cream:#FFF6E6;
      --brown:#8B5E3C;
      --brown-dark:#6b462c;
      --accent-start:#F5E0C7;
      --accent-end:#C79B78;
    }
    body{ background: linear-gradient(180deg, #f6fbf7 0%, #fffdfa 100%); font-family:'Poppins',sans-serif; color:var(--brown-dark); padding:32px 12px; }

    .wrap{ max-width:920px; margin:0 auto; }
    .card-tracking{
      background: linear-gradient(180deg, var(--cream), #fff);
      padding:26px;
      border-radius:14px;
      box-shadow:0 8px 30px rgba(107,70,44,0.08);
    }

    .tracking-title{ display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; }
    .tracking-title h4{ margin:0; font-weight:700; color:var(--brown-dark); }
    .tracking-title .meta{ color:#7b5b45; font-size:.95rem; }

    /* progress bar horizontal */
    .progress-wrap{ position:relative; margin:34px 0 14px; height:80px; }
    .progress-line{
      position:absolute; left:6%; right:6%; top:40px; height:8px; background:#eee; border-radius:8px; z-index:1;
      overflow:hidden;
    }
    .progress-fill{
      position:absolute; left:6%; top:40px; height:8px; background: linear-gradient(90deg, #F5D7B0, #C79B78); border-radius:8px; z-index:2; transition:width .5s ease;
    }

    .steps{
      position:relative; display:flex; justify-content:space-between; align-items:flex-start; gap:8px; z-index:3;
    }
    .step{
      width:16%;
      text-align:center;
      position:relative;
      transform:translateY(-6px);
    }
    .step .dot{
      width:44px; height:44px; border-radius:50%; margin:0 auto 8px;
      display:flex; align-items:center; justify-content:center; font-size:18px;
      background:#fff; border:4px solid #eee; box-shadow:0 6px 12px rgba(0,0,0,0.04);
      transition: all .36s ease;
    }
    .step.active .dot{
      background: linear-gradient(180deg, #fff, #F8ECE0);
      border-color: var(--brown);
      box-shadow: 0 8px 18px rgba(139,94,60,0.12);
      transform:scale(1.05);
    }
    .step .label{ font-weight:600; font-size:.92rem; color:#4a372b; }
    .step .sub{ font-size:.82rem; color:#7b6150; margin-top:4px; }

    /* order detail */
    .order-info{ margin-top:18px; display:flex; gap:16px; flex-wrap:wrap; }
    .order-info .box{ background:#fff; padding:12px 14px; border-radius:10px; flex:1; min-width:180px; box-shadow:0 3px 10px rgba(0,0,0,0.03); }
    .order-info .box p{ margin:0; font-size:.95rem; color:#4f3a2f; }
    .order-actions{ margin-top:18px; display:flex; gap:12px; flex-wrap:wrap; }

    /* responsive small screens: ikon dan label akan membungkus */
    @media (max-width:640px){
      .step .dot{ width:40px; height:40px; font-size:16px; }
      .step .label{ font-size:.85rem; }
      .progress-wrap{ height:100px; }
    }

  </style>
</head>
<body>
  <div class="wrap">
    <div class="card-tracking">
      <div class="tracking-title">
        <h4>Tracking Pesanan <small class="text-muted">#<span id="orderId">—</span></small></h4>
        <div class="meta">Estimasi tiba: <strong id="eta">—</strong> &nbsp;|&nbsp; Kurir: <strong>PuddingkuExpress</strong></div>
      </div>

      <div class="progress-wrap">
        <div class="progress-line"></div>
        <div class="progress-fill" id="progressFill" style="width:0%"></div>

        <div class="steps" id="stepsContainer">
          <div class="step" data-step="0">
            <div class="dot"><i class="bi bi-check2-square"></i></div>
            <div class="label">Diterima</div>
            <div class="sub">Pesanan masuk</div>
          </div>
          <div class="step" data-step="1">
            <div class="dot"><i class="bi bi-box-seam"></i></div>
            <div class="label">Dikemas</div>
            <div class="sub">Sedang dikemas</div>
          </div>
          <div class="step" data-step="2">
            <div class="dot"><i class="bi bi-send"></i></div>
            <div class="label">Dikirim</div>
            <div class="sub">Diserahkan ke kurir</div>
          </div>
          <div class="step" data-step="3">
            <div class="dot"><i class="bi bi-truck"></i></div>
            <div class="label">Dalam Perjalanan</div>
            <div class="sub">Sedang menuju alamat</div>
          </div>
          <div class="step" data-step="4">
            <div class="dot"><i class="bi bi-house-door"></i></div>
            <div class="label">Selesai</div>
            <div class="sub">Pesanan sudah sampai</div>
          </div>
        </div>
      </div>

      <div class="order-info">
        <div class="box">
          <p><strong>Nama:</strong> <span id="nameVal">—</span></p>
        </div>
        <div class="box">
          <p><strong>No. HP:</strong> <span id="phoneVal">—</span></p>
        </div>
        <div class="box">
          <p style="max-width:360px;"><strong>Alamat:</strong> <span id="addrVal">—</span></p>
        </div>
      </div>

      <div class="order-actions">
        <button id="btnRefresh" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-clockwise"></i> Refresh Status</button>
        <a href="pemesanan.php" class="btn btn-outline-primary btn-sm"><i class="bi bi-arrow-left"></i> Kembali Belanja</a>
      </div>
    </div>
  </div>

<script>
/* ---------- Ambil data order dari localStorage ---------- */
const steps = Array.from(document.querySelectorAll('.step'));
const fill = document.getElementById('progressFill');

function loadOrderData(){
  const lastOrder = JSON.parse(localStorage.getItem('umkm_last_order'));
  if(!lastOrder){
    document.getElementById('orderId').innerText = '—';
    document.getElementById('eta').innerText = '—';
    document.getElementById('nameVal').innerText = '-';
    document.getElementById('phoneVal').innerText = '-';
    document.getElementById('addrVal').innerText = '-';
    return null;
  }
  // tampilkan data
  document.getElementById('orderId').innerText = (lastOrder.time || '').slice(2,19).replace(/[:T]/g,'').replace(/\..+/, '') || '—';
  // buat ETA sederhana (contoh: +2 hari dari sekarang)
  const etaDate = new Date(lastOrder.time || new Date().toISOString());
  etaDate.setDate(etaDate.getDate()+2);
  document.getElementById('eta').innerText = etaDate.toLocaleDateString('id-ID');
  document.getElementById('nameVal').innerText = lastOrder.customer || '-';
  document.getElementById('phoneVal').innerText = lastOrder.phone || '-';
  document.getElementById('addrVal').innerText = lastOrder.address || '-';
  return lastOrder;
}

/* ---------- Render status berdasarkan umkm_tracking_step ---------- */
function renderStatus(){
  const raw = localStorage.getItem('umkm_tracking_step');
  let idx = raw !== null ? parseInt(raw,10) : null;
  if(Number.isNaN(idx)) idx = 0;
  if(idx === null) idx = 0;
  // pastikan range
  if(idx < 0) idx = 0; if(idx > steps.length-1) idx = steps.length-1;
  steps.forEach((s,i)=>{
    if(i <= idx) s.classList.add('active'); else s.classList.remove('active');
  });
  const pct = (idx / (steps.length - 1)) * 100;
  fill.style.width = pct + '%';
}

/* ---------- Auto-increment simulation (untuk demo) ---------- */
let autoInterval = null;
function startAutoProgressSimulation(){
  // hanya jalan jika ada last order
  if(!localStorage.getItem('umkm_last_order')) return;
  if(autoInterval) return;
  autoInterval = setInterval(()=>{
    let step = parseInt(localStorage.getItem('umkm_tracking_step') || '0', 10);
    if(step < steps.length - 1){
      step++;
      localStorage.setItem('umkm_tracking_step', String(step));
      renderStatus();
    } else {
      clearInterval(autoInterval);
      autoInterval = null;
    }
  }, 15000); // tiap 15 detik naik (sesuaikan)
}

/* ---------- Manual refresh ---------- */
document.getElementById('btnRefresh').addEventListener('click', ()=>{
  renderStatus();
});

/* ---------- Inisialisasi halaman ---------- */
document.addEventListener('DOMContentLoaded', ()=>{
  loadOrderData();
  renderStatus();
  startAutoProgressSimulation();
});
</script>
</body>
</html>
