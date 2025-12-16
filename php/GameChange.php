<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        
/* ===== 共通レイアウト（全ページ統一） ===== */
:root{
  --bg1:#277b3c;
  --bg2:#16602c;
  --bg3:#0e3f1d;
  --panel: rgba(255,255,255,0.10);
  --panel2: rgba(0,0,0,0.25);
  --text:#ffffff;
  --muted: rgba(255,255,255,0.75);
  --accent:#f4b034;
  --radius:16px;
}
*{ box-sizing:border-box; }
html,body{
            margin: 0;
            padding: 0;
  height:100%; }
body{
  margin:0;
  font-family: "Segoe UI","Noto Sans JP",sans-serif;
  color:var(--text);
  background: radial-gradient(circle at 20% 20%, var(--bg1) 0, var(--bg2) 55%, var(--bg3) 100%);
  padding: 92px 16px 24px; /* 固定ヘッダ分の余白 */
}
.page{
  max-width: 980px;
  margin: 0 auto;
}
.panel{
  background: var(--panel);
  border: 1px solid rgba(255,255,255,0.18);
  border-radius: var(--radius);
  box-shadow: 0 18px 40px rgba(0,0,0,0.35);
  padding: 18px;
}
h1,h2,h3{ margin: 0 0 12px; }
.small{ color: var(--muted); font-size: 0.95rem; }

/* 共通ヘッダ */
.site-header{
  position: fixed;
  left: 0; right: 0; top: 12px;
  z-index: 999;
  padding: 0 16px;
}
.site-header .header-inner{
  max-width: 980px;
  margin: 0 auto;
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap: 12px;
  padding: 10px 12px;
  border-radius: 14px;
  background: rgba(0,0,0,0.35);
  border: 1px solid rgba(255,255,255,0.14);
  backdrop-filter: blur(6px);
}
.header-title{
  font-weight: 800;
  letter-spacing: 0.06em;
  text-align:center;
  flex: 1;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.btn{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap: 8px;
  padding: 10px 12px;
  border-radius: 12px;
  border: none;
  font-weight: 800;
  cursor: pointer;
  text-decoration:none;
  user-select:none;
  -webkit-tap-highlight-color: transparent;
}
.btn:active{ transform: translateY(1px); }
.btn-primary{ background: var(--accent); color:#111; box-shadow: 0 6px 0 rgba(0,0,0,0.25); }
.btn-secondary{ background: rgba(255,255,255,0.16); color: #fff; box-shadow: 0 6px 0 rgba(0,0,0,0.25); border:1px solid rgba(255,255,255,0.18); }
.btn-back{ background:#111; color:#fff; box-shadow: 0 6px 0 rgba(0,0,0,0.45); }

.return_btn {
            background-color: black;
            color: aliceblue;
            border-radius: 30%;
            width: 7vh;
            min-width: 80px;

            letter-spacing: 0.3em;
            display: block;
            position: absolute;
            top: 10px;
        }

        .ranking_btn{
            position: absolute;
            bottom: 10%;
            width: 17%;
            height: 5%;
            font-size: 120%;
            background-color: goldenrod;
            left: 40%;
        }

        img {
            width: 100%;
            vertical-align: top;
            border-radius: 20%;
        }

        .game_btn {
            width: 30%;
            margin: 0 5vh;
            background-color:rgba(255, 255, 255, 0);
            border-color: rgba(255, 255, 255, 0);
        }

        .screen{
            margin: 0 auto;
        }

        body {
            display: flex;
            align-items: center;
            text-align: center;
            height: 98vh;
            background:
                radial-gradient(circle at 20% 20%, #277b3c 0, #16602c 55%, #0e3f1d 100%);
            width: 97%;
        }

        /* 共通ボタン / ヘッダスタイル */
        .site-header{
          position: fixed;
          left: 0;
          right: 0;
          top: 12px;
          display:flex;
          justify-content:space-between;
          align-items:center;
          gap:12px;
          padding: 0 14px;
          z-index: 10010;
          pointer-events: none;
        }
        .site-header .btn { pointer-events: auto; }

        /* 統一ボタン */
        .btn{
          display:inline-flex;
          align-items:center;
          justify-content:center;
          gap:8px;
          padding:10px 16px;
          border-radius:12px;
          border: none;
          font-weight:700;
          cursor:pointer;
          box-shadow: 0 6px 0 rgba(0,0,0,0.2);
          background: #f4b034;
          color:#111;
          font-size:16px;
        }
        .btn-secondary { background:#ffe082; color:#111; box-shadow:0 4px 0 #b06304; }
        .btn-back { background:#111; color:#fff; box-shadow: 0 4px 0 rgba(0,0,0,0.6); }

        /* ハンバーガー */
        .btn-hamburger{
          width:48px;height:48px;padding:8px;border-radius:10px;background:#222;color:#fff;
          display:flex;align-items:center;justify-content:center;
        }
        .btn-hamburger .bar{ display:block; width:20px; height:2px; background:#fff; margin:3px 0; }

        /* サイドメニュー */
        .side-menu{
          position: fixed;
          top:0;
          right: -320px;
          width: 280px;
          height:100vh;
          background: linear-gradient(180deg,#164f2b,#0f3a1e);
          color:#fff;
          padding: 70px 18px;
          box-shadow: -8px 0 24px rgba(0,0,0,0.5);
          transition: right .32s ease;
          z-index:10005;
        }
        .side-menu.open{ right:0; }
        .side-menu ul{ list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:12px; }
        .side-menu .nav-link{
          display:block; padding:12px 14px; border-radius:8px; background:rgba(255,255,255,0.04);
          color:#fff; text-decoration:none; font-weight:700;
        }

        /* オーバーレイ */
        .menu-overlay{
          position:fixed; inset:0; background:rgba(0,0,0,0.4); opacity:0; transition:opacity .24s; z-index:10000; pointer-events:none;
        }
        .menu-overlay.show{ opacity:1; pointer-events:auto; }

        /* 小画面調整 */
        @media(max-width:480px){
          .btn{ padding:8px 12px; font-size:14px; border-radius:10px; }
          .side-menu{ width:220px; right:-240px; }
          .site-header{ top:8px; padding:0 10px; }
        }
    
/* ===== 共通UI（戻る・ハンバーガー統一） ===== */
body{ padding-top: 72px; }

.top-ui{
  position: fixed; top: 14px; left: 14px; right: 14px;
  z-index: 10000;
  display:flex; justify-content:space-between; align-items:center;
  pointer-events:none;
}
.ui-back,.ui-menu{ pointer-events:auto; }

.ui-back{
  display:inline-flex; align-items:center; gap:8px;
  padding:10px 14px; border-radius:999px; border:none;
  background:rgba(0,0,0,0.78); color:#fff; font-weight:800;
  box-shadow:0 10px 22px rgba(0,0,0,0.35);
}
.ui-menu{
  width:48px; height:48px; border-radius:12px; border:none;
  background:rgba(0,0,0,0.78);
  box-shadow:0 10px 22px rgba(0,0,0,0.35);
  display:grid; place-items:center;
}
.ui-back:active,.ui-menu:active{ transform:translateY(1px); }

.ui-burger{ display:flex; flex-direction:column; gap:5px; }
.ui-burger span{ width:20px; height:2px; background:#fff; border-radius:2px; }

/* menu */
.menu-overlay{
  position:fixed; inset:0; background:rgba(0,0,0,0.35);
  opacity:0; pointer-events:none; transition:opacity .18s;
  z-index:9998;
}
.menu-panel{
  position:fixed; top:72px; right:14px; width:220px;
  padding:10px; border-radius:14px;
  background:rgba(0,0,0,0.78);
  border:1px solid rgba(255,255,255,0.14);
  opacity:0; transform:translateY(-6px);
  pointer-events:none; transition:opacity .18s, transform .18s;
  z-index:9999;
}
.menu-panel a{
  display:block; padding:10px 12px; border-radius:10px;
  color:#fff; text-decoration:none; font-weight:800;
  background:rgba(255,255,255,0.08);
  border:1px solid rgba(255,255,255,0.12);
  margin-bottom:8px;
}
.menu-panel a:last-child{ margin-bottom:0; }

.menu-overlay.open{ opacity:1; pointer-events:auto; }
.menu-panel.open{ opacity:1; transform:translateY(0); pointer-events:auto; }

</style>
</head>

<body>



    <div class="screen">
        <button class="game_btn" onclick="location.href='BJ.php'">
            <img src="img/BJ.png" alt="BlackJack">
        </button>

        <button class="game_btn" onclick="location.href='Poker_test.php'">
            <img src="img/PK.png" alt="Poker">
        </button>
    </div>

    <button class="ranking_btn" onclick="location.href='ranking.php'">ランキング表示</button>

    <?php // header include (置くだけで HTML に挿入されます) ?>
    <header class="top-ui">
  <button class="ui-back" type="button" onclick="goBack()">
    <span class="ui-back-arrow">◀</span><span class="ui-back-text">戻る</span>
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


    <div class="menu-overlay" id="menuOverlay" onclick="closeMenu()"></div>

    <script>
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
