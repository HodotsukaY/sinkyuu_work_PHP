<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>カードログイン画面</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", "Noto Sans JP", sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at 20% 20%, #277b3c 0, #16602c 55%, #0e3f1d 100%);
        }

        .table-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 35px;
        }

        .cards-row {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 40px;
            white-space: nowrap;
            flex-wrap: nowrap;          /* 基本は横並び固定 */
            overflow-x: auto;           /* 画面が狭い時は横スクロール */
            padding-bottom: 6px;
        }

        /* ログイン行だけ、ボタンを下に回すため wrap 有効 */
        .cards-row-login {
            flex-wrap: wrap;
        }

        .card-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .card {
            position: relative;
            width: 180px;
            aspect-ratio: 63 / 88;
            cursor: pointer;
            flex-shrink: 0;             /* 横スクロールでも潰れないよう固定 */
        }

        .card img {
            width: 100%;
            height: 100%;
            border-radius: 14px;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.4);
        }

        .card-inner {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 18% 12% 14%;
            pointer-events: none;
        }

        .field-label {
            color: #444;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 14px;
            text-shadow: 0 0 3px rgba(255, 255, 255, 0.7);
        }

        .field-input {
            width: 100%;
            pointer-events: auto;
            padding: 6px 10px;
            border-radius: 8px;
            border: 3px solid #555;
            background: #dcdcdc;
            font-size: 16px;
        }

        /* 共通ボタン */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 170px;
            padding: 10px 16px;
            border-radius: 12px;
            border: 3px solid #c77707;
            font-weight: 700;
            cursor: pointer;
            background: #f4b034;
            color: #111;
            font-size: 16px;
            box-shadow: 0 6px 0 rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: #ffe082;
            color: #111;
            box-shadow: 0 4px 0 #b06304;
        }

        /* 戻るボタン小型化 */
        .btn-back {
            background: #111;
            color: #fff;
            border-color: #111;
            box-shadow: 0 4px 0 rgba(0, 0, 0, 0.6);
            min-width: auto;
            padding: 6px 10px;
            font-size: 14px;
        }

        /* ハンバーガー */
        .btn-hamburger {
            width: 44px;
            height: 44px;
            padding: 6px;
            border-radius: 10px;
            background: #222;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: none;
            box-shadow: 0 4px 0 rgba(0, 0, 0, 0.6);
            gap: 4px;
        }

        .btn-hamburger .bar {
            display: block;
            width: 22px;
            height: 3px;
            background: #fff;
            border-radius: 999px;
        }

        /* ヘッダ */
        .site-header {
            position: fixed;
            left: 0;
            right: 0;
            top: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 0 14px;
            z-index: 10010;
            pointer-events: none;
        }

        .site-header .btn,
        .site-header .btn-hamburger {
            pointer-events: auto;
        }

        /* サイドメニュー */
        .side-menu {
            position: fixed;
            top: 0;
            right: -320px;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, #164f2b, #0f3a1e);
            color: #fff;
            padding: 70px 18px;
            box-shadow: -8px 0 24px rgba(0, 0, 0, 0.5);
            transition: right .32s ease;
            z-index: 10005;
        }

        .side-menu.open {
            right: 0;
        }

        .side-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .side-menu .nav-link {
            display: block;
            padding: 12px 14px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.04);
            color: #fff;
            text-decoration: none;
            font-weight: 700;
        }

        /* オーバーレイ */
        .menu-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            opacity: 0;
            transition: opacity .24s;
            z-index: 10000;
            pointer-events: none;
        }

        .menu-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        /* ログインボタン用：ID/PWカードの中央下に配置 */
        .login-btn-container {
            flex-basis: 100%;
            display: flex;
            justify-content: center;
            margin-top: 12px;
        }

        /* 小画面調整 */
        @media (max-width: 480px) {
            .btn {
                padding: 8px 12px;
                font-size: 14px;
                border-radius: 10px;
                min-width: 130px;
            }

            .btn-back {
                padding: 5px 8px;
                font-size: 13px;
            }

            .side-menu {
                width: 220px;
                right: -240px;
            }

            .site-header {
                top: 8px;
                padding: 0 10px;
            }
        }
    </style>
</head>

<body>

    <?php // 必要ならここでヘッダ共通化の include を入れる ?>
    <header class="site-header">
        <!-- テキストを「戻る」に変更 -->
        <button class="btn btn-back" onclick="location.href='Stert_Window.php'">◀ 戻る</button>

        <button class="btn-hamburger" id="menuToggle" aria-label="menu" onclick="toggleMenu()">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
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

    <div class="table-area">

        <!-- 上段：新規・ゲスト -->
        <div class="cards-row">

            <!-- 新規ユーザー → New_User.php -->
            <div class="card-wrapper">
                <div class="card" onclick="location.href='New_User.php'">
                    <img src="https://deckofcardsapi.com/static/img/back.png" alt="back card">
                </div>
                <button class="btn btn-secondary" type="button" onclick="location.href='New_User.php'">新規ユーザー</button>
            </div>

            <!-- ゲストログイン → GameChange.php -->
            <div class="card-wrapper">
                <div class="card" onclick="location.href='GameChange.php'">
                    <img src="https://deckofcardsapi.com/static/img/X1.png" alt="joker card">
                </div>
                <button class="btn btn-secondary" type="button" onclick="location.href='GameChange.php'">ゲストユーザー</button>
            </div>
        </div>

        <!-- 下段：通常ログイン（user_id / password を auth.php へ POST） -->
        <form id="loginForm" action="auth.php" method="post">
            <div class="cards-row cards-row-login">

                <!-- user_id -->
                <div class="card-wrapper">
                    <div class="card">
                        <img src="img/AD.png" alt="AD">
                        <div class="card-inner">
                            <div class="field-label">user_id</div>
                            <input class="field-input" type="text" id="userid" name="user_id" required>
                        </div>
                    </div>
                </div>

                <!-- password -->
                <div class="card-wrapper">
                    <div class="card">
                        <img src="img/2C.png" alt="2C">
                        <div class="card-inner">
                            <div class="field-label">password</div>
                            <input class="field-input" type="password" id="password" name="password" required>
                        </div>
                    </div>
                </div>

                <!-- ID / PW カードの中央下にログインボタン -->
                <div class="login-btn-container">
                    <button class="btn" type="submit">ログイン</button>
                </div>

            </div>
        </form>

    </div>

    <script>
        function toggleMenu() {
            const m = document.getElementById('sideMenu');
            const o = document.getElementById('menuOverlay');
            const open = m.classList.toggle('open');
            o.classList.toggle('show', open);
            m.setAttribute('aria-hidden', !open);
        }

        function closeMenu() {
            document.getElementById('sideMenu').classList.remove('open');
            document.getElementById('menuOverlay').classList.remove('show');
            document.getElementById('sideMenu').setAttribute('aria-hidden', 'true');
        }

    </script>
</body>
</html>