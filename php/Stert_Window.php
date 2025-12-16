<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Start</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    *{ box-sizing:border-box; margin:0; padding:0; }

    body{
      width:100vw;
      height:100vh;
      display:flex;
      justify-content:center;
      align-items:center;
      cursor:pointer;
      background: radial-gradient(circle at 20% 20%, #277b3c 0, #16602c 55%, #0e3f1d 100%);
      overflow:hidden;
      padding-top: 72px; /* 固定UIぶん（Startは必要なら） */
    }

    .container{
      text-align:center;
      animation: fadeIn 1.0s ease-out forwards;
    }

    .start-img{
      width:65vw;
      max-width:650px;
      border-radius:0;
      box-shadow:none;
    }

    .start-text{
      margin-top:30px;
      font-size:28px;
      font-weight:bold;
      color:#ffeb8a;
      text-shadow:0 0 4px rgba(0,0,0,0.6);
      animation: blink 1.4s infinite;
      letter-spacing:4px;
    }

    @keyframes blink{
      0%,100%{ opacity:.35; }
      50%{ opacity:1; }
    }
    @keyframes fadeIn{
      from{ opacity:0; transform:scale(1.08); }
      to{ opacity:1; transform:scale(1); }
    }

    /* ===== 画像のUIに合わせた戻る＋ハンバーガー ===== */
    .top-ui{
      position:fixed;
      top:14px;
      left:14px;
      right:14px;
      z-index:10000;
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
      background:rgba(0,0,0,0.78);
      color:#fff;
      font-weight:800;
      cursor:pointer;
      box-shadow:0 10px 22px rgba(0,0,0,0.35);
    }
    .ui-menu{
      width:48px;
      height:48px;
      border-radius:12px;
      border:none;
      background:rgba(0,0,0,0.78);
      cursor:pointer;
      box-shadow:0 10px 22px rgba(0,0,0,0.35);
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

    /* ===== メニュー ===== */
    .menu-overlay{
      position:fixed;
      inset:0;
      background:rgba(0,0,0,0.35);
      opacity:0;
      pointer-events:none;
      transition:opacity .18s;
      z-index:9998;
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
      z-index:9999;
      backdrop-filter:blur(8px);
      box-shadow:0 20px 50px rgba(0,0,0,0.45);
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
  </style>
</head>

<body onclick="goLogin()">
  <!-- 統一UI（Startでも表示したいならこれ） -->

  <div class="container">
    <img src="img/title_SW (1).png" class="start-img" alt="start image">
    <div class="start-text">タップしてスタート</div>
  </div>

  <script>
    function goLogin(){
      location.href = "Login.php";
    }

    function goBack(){
      if(history.length > 1) history.back();
      else location.href = "GameChange.php";
    }

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
