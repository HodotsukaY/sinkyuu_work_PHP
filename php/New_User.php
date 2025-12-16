<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>Card Form</title>

<?php
// ----------------------------------------------------
// 1. データベース接続設定
// ----------------------------------------------------
$pdo_dsn = 'mysql:host=localhost;dbname=login_db;charset=utf8mb4;';
$pdo_user = 'root'; //本来であればユーザを作成します
$pdo_pass = ''; //本来であればパスワードを設定します
$pdo_option = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_STRINGIFY_FETCHES => false
);

$message = "ユーザー名とパスワードを入力してください。";
$j = 0;
$pdo = null;

// ----------------------------------------------------
// 2. フォームデータ受信と処理
// ----------------------------------------------------

// フォームが POST メソッドで送信されたかチェック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // データが存在するかチェック
    if (isset( $_POST['userid'], $_POST['username'], $_POST['password'])) {

        $userid = $_POST['userid'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // パスワードのハッシュ化（セキュリティ上必須）
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // データベースに接続
            $pdo = new PDO($pdo_dsn, $pdo_user, $pdo_pass, $pdo_option);

            // SQLインサート文の準備（プリペアドステートメント）
            $sql = "INSERT INTO users (user_id, user_name, password) VALUES (:user_id, :user_name, :password)";
            $stmt = $pdo->prepare($sql);

            // 値のバインド
            $stmt->bindParam(':user_name', $username, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $userid, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);

            // 実行
            $stmt->execute();

            $message = "✅ ユーザー **" . htmlspecialchars($username) . "** の登録が完了しました！";
            $j = 1;

        } catch (\PDOException $e) {
            // エラー処理（開発中は詳細を表示しても良いが、本番環境では一般的なエラーメッセージに）
            $message = "❌ データベースエラーが発生しました: " . $e->getMessage();
        }
    } else {
        $message = "❌ ユーザー名またはパスワードが入力されていません。";
    }
}
?>

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

    .back-btn {
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
      height: 694px;
      /* カード本来の比率に合わせる */
      perspective: 1200px;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0 auto;
    }

    .flip-card>div {
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
      object-fit: contain;
      /* ←画像切れ防止 */
      display: block;
    }


    .flip-card {
      width: 100%;
      height: 100%;
      position: relative;
      transform-style: preserve-3d;
      animation: flip 1s forwards;
    }

    .flip-card>div {
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


    <?php
    if ($j == 0) {
      ?>

   /* フリップアニメーション */
    @keyframes flip {
      0% {
        transform: rotateY(0);
      }

      100% {
        transform: rotateY(180deg);
      }
    }

    <?php
    } else {
    ?>
    @keyframes flip {
      0% {
        transform: rotateY(180deg);
      }

      100% {
        transform: rotateY(360deg);
      }
    }

    <?php
    }
    ?>

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background:
        radial-gradient(circle at 20% 20%, #277b3c 0, #16602c 55%, #0e3f1d 100%);
    }

    /* ===== 上部UI（戻る＋ハンバーガー統一） ===== */
body{ padding-top:72px; } /* 上UIの分 */

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

/* メニュー */
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

  <p style="font-weight: bold;"><?= $message ?></p>
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
          <img src="img\3S.png" alt="3S">

          <form action="" method="post">

            <div class="form-area">
              <label for="userid">ユーザーID</label>
              <input type="text" id="userid" name="userid" required>

              <label for="username">ユーザー名</label>
              <input type="text" id="username" name="username" required>

              <label for="password">パスワード</label>
              <input type="password" id="password" name="password" required>

              <button type="submit">OK</button>

            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
  <script>
function goBackUnified(){
  if (window.history.length > 1) window.history.back();
  else location.href = "Login.php"; // 戻れない時の保険
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
