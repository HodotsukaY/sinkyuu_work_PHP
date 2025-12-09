<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
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
    </style>
</head>

<body>
    <button class="return_btn" onclick="location.href='Login.php'">◀戻る</button>

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

    <script>
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
