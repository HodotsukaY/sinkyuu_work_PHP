<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ランキング</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    /* ===== 背景など（共通） ===== */
    :root{
      --bg1:#277b3c;
      --bg2:#16602c;
      --bg3:#0e3f1d;
      --text:#ffffff;
    }
    *{ box-sizing:border-box; }
    html,body{ height:100%; }
    body{
      margin:0;
      font-family:"Segoe UI","Noto Sans JP",sans-serif;
      color:var(--text);
      background: radial-gradient(circle at 20% 20%, var(--bg1) 0, var(--bg2) 55%, var(--bg3) 100%);
      padding-top: 72px; /* 上の固定UIぶん */
    }
    .page{
      max-width: 900px;
      margin: 40px auto;
      padding: 0 20px;
    }

    /* ===== 画像のUIに合わせた戻る＋ハンバーガー ===== */
    .top-ui{
      position: fixed;
      top: 14px;
      left: 14px;
      right: 14px;
      z-index: 10000;
      display:flex;
      justify-content:space-between;
      align-items:center;
      pointer-events:none;
    }
    .ui-back,.ui-menu{ pointer-events:auto; }

    .ui-back{
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:10px 14px;
      border-radius:999px;
      border:none;
      background: rgba(0,0,0,0.78);
      color:#fff;
      font-weight:800;
      cursor:pointer;
      box-shadow: 0 10px 22px rgba(0,0,0,0.35);
    }
    .ui-menu{
      width:48px;
      height:48px;
      border-radius:12px;
      border:none;
      background: rgba(0,0,0,0.78);
      cursor:pointer;
      box-shadow: 0 10px 22px rgba(0,0,0,0.35);
      display:grid;
      place-items:center;
    }
    .ui-back:active,.ui-menu:active{ transform:translateY(1px); }

    .ui-burger{ display:flex; flex-direction:column; gap:5px; }
    .ui-burger span{
      width:20px;
      height:2px;
      background:#fff;
      border-radius:2px;
      display:block;
    }

    /* ===== メニュー（右上に出るやつ） ===== */
    .menu-overlay{
      position:fixed;
      inset:0;
      background:rgba(0,0,0,0.35);
      opacity:0;
      pointer-events:none;
      transition:opacity .18s;
      z-index: 9998;
    }
    .menu-panel{
      position:fixed;
      top:72px;
      right:14px;
      width:220px;
      padding:10px;
      border-radius:14px;
      background:rgba(0,0,0,0.78);
      border:1px solid rgba(255,255,255,0.14);
      opacity:0;
      transform:translateY(-6px);
      pointer-events:none;
      transition:opacity .18s, transform .18s;
      z-index: 9999;
      backdrop-filter: blur(8px);
      box-shadow: 0 20px 50px rgba(0,0,0,0.45);
    }
    .menu-panel a{
      display:block;
      padding:10px 12px;
      border-radius:10px;
      color:#fff;
      text-decoration:none;
      font-weight:800;
      background:rgba(255,255,255,0.08);
      border:1px solid rgba(255,255,255,0.12);
      margin-bottom:8px;
    }
    .menu-panel a:last-child{ margin-bottom:0; }

    .menu-overlay.open{ opacity:1; pointer-events:auto; }
    .menu-panel.open{ opacity:1; transform:translateY(0); pointer-events:auto; }

    /* ===== 検索バー ===== */
    .search-bar{
      display:flex;
      align-items:stretch;
      margin: 0 auto 25px;
      width:100%;
      max-width:650px;
      box-shadow:0 4px 10px rgba(0,0,0,0.35);
    }
    .search-tab-label{
      background:#f5f5f5;
      color:#222;
      padding:10px 20px;
      font-weight:700;
      border-top-left-radius:4px;
    }
    .search-input-wrap{
      flex:1;
      background:#6b7a4b;
      display:flex;
      align-items:center;
      padding:0 10px;
    }
    .search-input-wrap input{
      width:100%;
      padding:6px 8px;
      border-radius:4px;
      border:none;
      outline:none;
      font-size:14px;
    }
    .search-button{
      background:#d0d6db;
      color:#222;
      padding:10px 25px;
      font-weight:700;
      border:none;
      cursor:pointer;
      border-top-right-radius:4px;
      border-bottom-right-radius:4px;
    }

    /* ===== ランキング表 ===== */
    .ranking-box{
      margin: 0 auto;
      max-width:650px;
      border:1px solid rgba(255,255,255,0.3);
      background: rgba(0,0,0,0.15);
      padding: 12px 18px 18px;
    }
    table{
      width:100%;
      border-collapse:collapse;
      color:#e8ffe8;
      font-size:14px;
    }
    thead th{
      text-align:left;
      padding:6px 4px;
      border-bottom:1px solid rgba(255,255,255,0.3);
    }
    tbody td{ padding:6px 4px; }
    tbody tr:nth-child(odd){ background:rgba(255,255,255,0.03); }
    .col-rank{ width:50px; }
    .col-username{ width:40%; font-style:italic; }
    .col-point{ width:100px; }
    .col-id{ width:120px; }
  </style>
</head>

<body>
  <!-- 統一UI（これだけ残す） -->
  <header class="top-ui">
    <button class="ui-back" type="button" onclick="goBack()">
      <span>◀</span><span>戻る</span>
    </button>

    <button class="ui-menu" type="button" aria-label="menu" onclick="toggleMenu()">
      <span class="ui-burger" aria-hidden="true">
        <span></span><span></span><span></span>
      </span>
    </button>
  </header>

  <div class="menu-overlay" id="menuOverlay" onclick="closeMenu()"></div>
  <nav class="menu-panel" id="menuPanel" aria-hidden="true">
    <a href="Stert_Window.php">Start</a>
    <a href="Login.php">Login</a>
    <a href="GameChange.php">Games</a>
    <a href="ranking.php">Ranking</a>
    <a href="New_User.php">New User</a>
  </nav>

  <div class="page">
    <!-- 検索バー -->
    <div class="search-bar">
      <div class="search-tab-label">ユーザー検索</div>
      <div class="search-input-wrap">
        <input type="text" id="search-input" placeholder="Username / User IDで検索">
      </div>
      <button class="search-button" id="search-btn">検索</button>
    </div>

    <!-- ランキング表 -->
    <div class="ranking-box">
      <table id="ranking-table">
        <thead>
          <tr>
            <th class="col-rank">Rank</th>
            <th class="col-username">Username</th>
            <th class="col-point">Point</th>
            <th class="col-id">User ID</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>1</td><td>USER_NAME_01</td><td>9,500 Pt</td><td>ID001</td></tr>
          <tr><td>2</td><td>USER_NAME_02</td><td>9,000 Pt</td><td>ID002</td></tr>
          <tr><td>3</td><td>USER_NAME_03</td><td>8,500 Pt</td><td>ID003</td></tr>
          <tr><td>4</td><td>USER_NAME_04</td><td>8,000 Pt</td><td>ID004</td></tr>
          <tr><td>5</td><td>USER_NAME_05</td><td>7,500 Pt</td><td>ID005</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    // 検索（Username / User ID）
    const input = document.getElementById('search-input');
    const btn = document.getElementById('search-btn');
    const tbody = document.getElementById('ranking-table').tBodies[0];

    function doSearch(){
      const q = input.value.trim().toLowerCase();
      Array.from(tbody.rows).forEach(row => {
        const username = row.cells[1].textContent.toLowerCase();
        const userId   = row.cells[3].textContent.toLowerCase();
        const hit = !q || username.includes(q) || userId.includes(q);
        row.style.display = hit ? '' : 'none';
      });
    }
    btn.addEventListener('click', doSearch);
    input.addEventListener('keydown', e => { if(e.key === 'Enter') doSearch(); });

    // 戻る（履歴がない時の保険付き）
    function goBack(){
      if(history.length > 1) history.back();
      else location.href = "GameChange.php";
    }

    // メニュー開閉
    function toggleMenu(){
      const p = document.getElementById("menuPanel");
      const o = document.getElementById("menuOverlay");
      const open = !p.classList.contains("open");
      p.classList.toggle("open", open);
      o.classList.toggle("open", open);
      p.setAttribute("aria-hidden", String(!open));
    }
    function closeMenu(){
      document.getElementById("menuPanel").classList.remove("open");
      document.getElementById("menuOverlay").classList.remove("open");
      document.getElementById("menuPanel").setAttribute("aria-hidden","true");
    }
    document.addEventListener("keydown",(e)=>{ if(e.key==="Escape") closeMenu(); });
  </script>
</body>
</html>
