<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>Card Form</title>

<style>
  body {
    margin: 0;
    padding: 0;
    background-image: url("sibahu.png");
    background-size: cover;
    height: 100vh;
    font-family: sans-serif;

    /* 画面中央配置 */
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .back-btn{
    position: fixed;
    top: 20px;
    left: 20px;
    padding: 20px 70px;
    font-size: 40px;
    background: black;
    color: white;
    border: 2px solid black;
    border-radius: 100px;
    cursor: pointer;
  }

  /* ▼▼ 裏→表フリップ全体 ▼▼ */
  
  .flip-container {
  width: 500px;
  height: 694px; /* カード本来の比率に合わせる */
  perspective: 1200px;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 0 auto;
}

.flip-card > div {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  backface-visibility: hidden;
}

/* カード画像（切れない設定） */
.card-back img,
.card-box img {
  width: 100%;
  height: 100%;
  object-fit: contain;  /* ←画像切れ防止 */
  display: block;
}


  .flip-card {
    width: 100%;
    height: 100%;
    position: relative;
    transform-style: preserve-3d;
    animation: flip 1s forwards;
  }

  .flip-card > div {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
  }

  /* 裏面 */
  .card-back img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  /* 表面（入力画面） */
  .card-front {
    transform: rotateY(180deg);
  }

  /* カード画像 */
  .card-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
/* test */
  /* フォーム位置調整 */
  .form-area {
    position: absolute;
    top: 22%;
    left: 15%;
    width: 70%;
    font-size: 20px;
    text-align: center;
  }

  .form-area input {
    width: 100%;
    margin-bottom: 50px;
    padding: 15px;
    font-size: 18px;
    background-color: rgb(227, 223, 223);
    box-sizing: border-box;
  }

  .form-area button {
    padding: 6px 16px;
    font-size: 18px;
  }

  /* フリップアニメーション */
  @keyframes flip {
    0%   { transform: rotateY(0); }
    100% { transform: rotateY(180deg); }
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
</style>
</head>

<body>

<header class="site-header">
  <button class="btn btn-back" onclick="location.href='Stert_Window.php'">◀ すべてに戻る</button>
  <button class="btn btn-hamburger" id="menuToggle" aria-label="menu" onclick="toggleMenu()">
    <span class="bar"></span><span class="bar"></span><span class="bar"></span>
  </button>
</header>

<nav class="side-menu" id="sideMenu" aria-hidden="true">
  <ul>
    <li><a class="nav-link" href="Stert_Window.php">Start</a></li>
    <li><a class="nav-link" href="Login.php">Login</a></li>
    <li><a class="nav-link" href="GameChange.php">Games</a></li>
    <li><a class="nav-link" href="ranking.php">Ranking</a></li>
    <li><a class="nav-link" href="New_User.php">New User</a></li>
  </ul>
</nav>
<div class="menu-overlay" id="menuOverlay" onclick="closeMenu()"></div>

<!-- ▼ 裏→表の flip コンテナ ▼ -->
<div class="flip-container">
  <div class="flip-card">

    <!-- 裏面 -->
    <div class="card-back">
      <img src="https://deckofcardsapi.com/static/img/back.png" alt="back">
    </div>

    <!-- 表面（フォーム付カード） -->
    <div class="card-front">
      <div class="card-box">
        <img src="img/3S.png" alt="3S">

        <div class="form-area">
          <div>User_name</div>
          <input type="text">

          <div>User_id</div>
          <input type="text">

          <div>Password</div>
          <input type="password">

          <button class="ok">OK</button>
        </div>

      </div>
    </div>

  </div>
</div>
<script>
  
  const ok = document.querySelector('.ok');
  ok.addEventListener('click', () => {
    window.location.href = 'Login.php';
  });

   const back = document.querySelector('.back-btn');
  back.addEventListener('click', () => {
    window.location.href = 'Login.php';
  });

function toggleMenu(){
  const m = document.getElementById('sideMenu');
  const o = document.getElementById('menuOverlay');
  const open = m.classList.toggle('open');
  o.classList.toggle('show', open);
  m.setAttribute('aria-hidden', !open);
}
function closeMenu(){
  document.getElementById('sideMenu').classList.remove('open');
  document.getElementById('menuOverlay').classList.remove('show');
  document.getElementById('sideMenu').setAttribute('aria-hidden', 'true');
}
</script>
</body>
</html>