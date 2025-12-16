<?php
session_start();

/* DB設定などはそのまま */
define('DB_HOST', 'localhost');
define('DB_NAME', 'login_db');
define('DB_USER', 'root');
define('DB_PASS', '');

$login_page = "Login.php";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $input_password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($input_user_id) || empty($input_password)) {
        $error_message = "ユーザーIDとパスワードを入力してください。";
    } else {
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT user_id, user_name, password AS password_hash FROM users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $input_user_id);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($input_password, $user['password_hash'])) {
                session_regenerate_id(true);

                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['password'] = $user['password_hash'];

                header("Location: GameChange.php");
                exit();
            } else {
                $error_message = "ユーザーIDまたはパスワードが間違っています。";
            }
        } catch (PDOException $e) {
            $error_message = "データベース接続エラー: 設定を確認してください。";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>カードログイン画面</title>
  <style>
    *{
      box-sizing:border-box;
      margin:0;
      padding:0;
      font-family:"Segoe UI","Noto Sans JP",sans-serif;
    }

    body{
      min-height:100vh;
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      background: radial-gradient(circle at 20% 20%, #277b3c 0, #16602c 55%, #0e3f1d 100%);
      padding-top: 72px; /* 上UI分 */
    }

    /* ===== 上部UI（画像のやつ） ===== */
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
    .ui-burger span{ width:20px; height:2px; background:#fff; border-radius:2px; display:block; }

    .menu-overlay{
      position:fixed; inset:0;
      background:rgba(0,0,0,0.35);
      opacity:0; pointer-events:none;
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
      backdrop-filter:blur(8px);
      box-shadow:0 20px 50px rgba(0,0,0,0.45);
      opacity:0;
      transform:translateY(-6px);
      pointer-events:none;
      transition:opacity .18s, transform .18s;
      z-index:9999;
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

    /* ===== 既存UI ===== */
    .table-area{
      display:flex;
      flex-direction:column;
      align-items:center;
      gap:35px;
    }

    .cards-row{
      display:flex;
      justify-content:center;
      align-items:flex-start;
      gap:40px;
      white-space:nowrap;
    }

    .card-wrapper{
      display:flex;
      flex-direction:column;
      align-items:center;
      gap:12px;
    }

    .card{
      position:relative;
      width:180px;
      aspect-ratio:63/88;
      cursor:pointer;
    }

    .card img{
      width:100%;
      height:100%;
      border-radius:14px;
      box-shadow:0 12px 25px rgba(0,0,0,0.4);
    }

    .card-inner{
      position:absolute;
      inset:0;
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      padding:18% 12% 14%;
      pointer-events:none;
    }

    .field-label{
      color:#444;
      font-size:20px;
      font-weight:600;
      margin-bottom:14px;
      text-shadow:0 0 3px rgba(255,255,255,0.7);
    }

    .field-input{
      width:100%;
      pointer-events:auto;
      padding:6px 10px;
      border-radius:8px;
      border:3px solid #555;
      background:#dcdcdc;
      font-size:16px;
    }

    .btn{
      min-width:170px;
      padding:12px 20px;
      border-radius:12px;
      border:3px solid #c77707;
      background:#f4b034;
      font-size:18px;
      font-weight:700;
      cursor:pointer;
      box-shadow:0 6px 0 #b06304;
    }
    .btn-secondary{ background:#ffe082; }

    .error-message{
      color:#ffdddd;
      background-color:#a00000;
      padding:10px 20px;
      border-radius:8px;
      margin: 0 12px 20px;
      font-weight:bold;
      text-align:center;
      border:2px solid #ff4444;
      max-width: 940px;
    }

    @media(max-width:900px){
      .cards-row{ gap:18px; flex-wrap:wrap; justify-content:center; }
      .card{ width:160px; }
    }
  </style>
</head>

<body>

  <!-- 上部UI -->
  <header class="top-ui">
    <button class="ui-back" type="button" onclick="goBackUnified()">
      <span>◀</span><span>戻る</span>
    </button>

    <button class="ui-menu" type="button" aria-label="menu" onclick="toggleMenu()">
      <span class="ui-burger" aria-hidden="true">
        <span></span><span></span><span></span>
      </span>
    </button>
  </header>

  <div class="menu-overlay" id="menuOverlay2" onclick="closeMenu()"></div>
  <nav class="menu-panel" id="menuPanel2" aria-hidden="true">
  <a href="Stert_Window.php">Start</a>
  <a href="Login.php">Login</a>
  <a href="GameChange.php">Games</a>
  <a href="ranking.php">Ranking</a>
  <a href="New_User.php">New User</a>
</nav>


  <?php if ($error_message !== ""): ?>
    <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
  <?php endif; ?>

  <div class="table-area">
    <form method="POST" action="">
      <div class="cards-row">

        <div class="card-wrapper">
          <div class="card" onclick="location.href='New_User.php'">
            <img src="https://deckofcardsapi.com/static/img/back.png" alt="back card">
          </div>
          <button class="btn btn-secondary" type="button" onclick="location.href='New_User.php'">新規ユーザー</button>
        </div>

        <div class="card-wrapper">
          <div class="card" onclick="location.href='GameChange.php'">
            <img src="https://deckofcardsapi.com/static/img/X1.png" alt="joker card">
          </div>
          <button class="btn btn-secondary" type="button" onclick="location.href='GameChange.php'">ゲストユーザー</button>
        </div>

        <div class="card-wrapper">
          <div class="card">
            <img src="img/AD.png" alt="AD">
            <div class="card-inner">
              <div class="field-label">ユーザー</div>
              <input class="field-input" type="text" id="userid" name="user_id">
            </div>
          </div>
        </div>

        <div class="card-wrapper">
          <div class="card">
            <img src="img/2C.png" alt="2C">
            <div class="card-inner">
              <div class="field-label">パスワード</div>
              <input class="field-input" type="password" id="password" name="password">
            </div>
          </div>
        </div>

      </div>

      <button class="btn" type="submit">ログイン</button>
    </form>
  </div>

  <script>
    function goBackUnified(){
      if (window.history.length <= 1) { location.href = 'Stert_Window.php'; return; }
      window.history.back();
    }

    function toggleMenu(){
      const p = document.getElementById("menuPanel2");
      const o = document.getElementById("menuOverlay2");
      const open = !p.classList.contains("open");
      p.classList.toggle("open", open);
      o.classList.toggle("open", open);
      p.setAttribute("aria-hidden", String(!open));
    }
    function closeMenu(){
      document.getElementById("menuPanel2").classList.remove("open");
      document.getElementById("menuOverlay2").classList.remove("open");
      document.getElementById("menuPanel2").setAttribute("aria-hidden","true");
    }
    document.addEventListener("keydown",(e)=>{ if(e.key==="Escape") closeMenu(); });
  </script>

</body>
</html>
