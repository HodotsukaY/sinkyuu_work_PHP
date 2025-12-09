<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ランキング</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", "Noto Sans JP", sans-serif;
        }

        body {
            min-height: 100vh;
            background: #1d7a33;
            background-image:
                radial-gradient(circle at 0 0, rgba(255, 255, 255, 0.08) 0, transparent 55%),
                radial-gradient(circle at 100% 100%, rgba(0, 0, 0, 0.25) 0, transparent 60%);
            color: #fff;
        }

        .page {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
            position: relative;
        }

        /* 上部ボタン ------------------------------------------------*/

        .top-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .btn-pill {
            background: #111;
            color: #fff;
            border-radius: 999px;
            padding: 8px 18px;
            font-size: 14px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 0 rgba(0, 0, 0, 0.5);
        }

        .menu-btn {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .menu-icon {
            display: inline-flex;
            flex-direction: column;
            gap: 3px;
        }

        .menu-icon span {
            display: block;
            width: 16px;
            height: 2px;
            background: #fff;
        }

        .menu-label {
            font-size: 12px;
            letter-spacing: 1px;
        }

        /* 検索バー --------------------------------------------------*/

        .search-bar {
            display: flex;
            align-items: stretch;
            margin: 0 auto 25px;
            width: 100%;
            max-width: 650px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.35);
        }

        .search-tab-label {
            background: #f5f5f5;
            color: #222;
            padding: 10px 20px;
            font-weight: 700;
            border-top-left-radius: 4px;
        }

        .search-input-wrap {
            flex: 1;
            background: #6b7a4b;
            display: flex;
            align-items: center;
            padding: 0 10px;
        }

        .search-input-wrap input {
            width: 100%;
            padding: 6px 8px;
            border-radius: 4px;
            border: none;
            outline: none;
            font-size: 14px;
        }

        .search-button {
            background: #d0d6db;
            color: #222;
            padding: 10px 25px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }

        /* ランキング表 ----------------------------------------------*/

        .ranking-box {
            margin: 0 auto;
            max-width: 650px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(0, 0, 0, 0.15);
            padding: 12px 18px 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            color: #e8ffe8;
            font-size: 14px;
        }

        thead th {
            text-align: left;
            padding: 6px 4px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        tbody td {
            padding: 6px 4px;
        }

        tbody tr:nth-child(odd) {
            background: rgba(255, 255, 255, 0.03);
        }

        .col-rank {
            width: 50px;
        }

        .col-username {
            width: 40%;
            font-style: italic;
        }

        .col-point {
            width: 100px;
        }

        .col-id {
            width: 120px;
        }

        /* 共通ボタン / ヘッダスタイル */
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

        .site-header .btn {
            pointer-events: auto;
        }

        /* 統一ボタン */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 12px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 0 rgba(0, 0, 0, 0.2);
            background: #f4b034;
            color: #111;
            font-size: 16px;
        }

        .btn-secondary {
            background: #ffe082;
            color: #111;
            box-shadow: 0 4px 0 #b06304;
        }

        .btn-back {
            background: #111;
            color: #fff;
            box-shadow: 0 4px 0 rgba(0, 0, 0, 0.6);
        }

        /* ハンバーガー */
        .btn-hamburger {
            width: 48px;
            height: 48px;
            padding: 8px;
            border-radius: 10px;
            background: #222;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-hamburger .bar {
            display: block;
            width: 20px;
            height: 2px;
            background: #fff;
            margin: 3px 0;
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

        /* 小画面調整 */
        @media (max-width: 480px) {
            .btn {
                padding: 8px 12px;
                font-size: 14px;
                border-radius: 10px;
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

    <div class="page">

        <!-- 戻る / MENU -->
        <div class="top-buttons">
            <button class="btn-pill" onclick="history.back()">◀ 戻る</button>

            <button class="btn-pill menu-btn">
                <span class="menu-icon">
                    <span></span><span></span><span></span>
                </span>
                <span class="menu-label">MENU</span>
            </button>
        </div>

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
                    <!-- ここはサーバから出してもOK。いまはダミー -->
                    <tr>
                        <td>1</td>
                        <td>USER_NAME_01</td>
                        <td>9,500 Pt</td>
                        <td>ID001</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>USER_NAME_02</td>
                        <td>9,000 Pt</td>
                        <td>ID002</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>USER_NAME_03</td>
                        <td>8,500 Pt</td>
                        <td>ID003</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>USER_NAME_04</td>
                        <td>8,000 Pt</td>
                        <td>ID004</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>USER_NAME_05</td>
                        <td>7,500 Pt</td>
                        <td>ID005</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <script>
        // 簡単なクライアント側の検索（Username / User ID に部分一致）
        const input = document.getElementById('search-input');
        const btn = document.getElementById('search-btn');
        const table = document.getElementById('ranking-table').tBodies[0];

        function doSearch() {
            const q = input.value.trim().toLowerCase();
            Array.from(table.rows).forEach(row => {
                const username = row.cells[1].textContent.toLowerCase();
                const userId = row.cells[3].textContent.toLowerCase();
                const hit = !q || username.includes(q) || userId.includes(q);
                row.style.display = hit ? '' : 'none';
            });
        }

        btn.addEventListener('click', doSearch);
        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') doSearch();
        });

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

</body>

</html>