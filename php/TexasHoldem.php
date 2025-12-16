<?php // TexasHoldem.php （PHPサーバー上で動く画面） ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>テキサスホールデム（自分 vs NPC）</title>
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
html,body{ height:100%; }
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

body {
            font-family: "Segoe UI", "Noto Sans JP", sans-serif;
            background: #16602c;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            padding: 20px;
        }
        .cards {
            display: flex;
            gap: 8px;
            margin: 8px 0;
        }
        .card {
            width: 50px;
            height: 70px;
            background: #fff;
            color: #000;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        button {
            padding: 8px 14px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 700;
            margin: 0 4px;
        }
        #log {
            white-space: pre-wrap;
            background: rgba(0,0,0,0.2);
            padding: 10px;
            border-radius: 8px;
            width: 100%;
            max-width: 600px;
            font-size: 14px;
        }
    </style>
</head>
<body>


<header class="site-header">
  <div class="header-inner">
    <button class="btn btn-back" type="button" onclick="history.back()">戻る</button>
    <div class="header-title">テキサスホールデム（自分 vs NPC）</div>
    <a class="btn btn-secondary" href="Stert_Window.php">HOME</a>
  </div>
</header>

<div class="page"><h1>テキサスホールデム（自分 vs NPC）</h1>

<div>
    <button onclick="startGame()">ゲーム開始</button>
    <button onclick="sendAction('check')">チェック / コール</button>
    <button onclick="sendAction('fold')">フォールド</button>
</div>

<h2>あなたの手札</h2>
<div id="player-cards" class="cards"></div>

<h2>コミュニティカード</h2>
<div id="community-cards" class="cards"></div>

<h2>状態</h2>
<div id="status"></div>

<h2>ログ</h2>
<div id="log"></div>

<script>
    // カードの文字列 "AS" "TD" をそのまま表示する簡易表示
    function renderCards(containerId, cards) {
        const el = document.getElementById(containerId);
        el.innerHTML = '';
        if (!cards) return;
        cards.forEach(c => {
            const div = document.createElement('div');
            div.className = 'card';
            div.textContent = c;
            el.appendChild(div);
        });
    }

    function updateView(data) {
        if (!data.ok) {
            document.getElementById('status').textContent = 'エラー: ' + (data.error || '');
            return;
        }
        const state = data.state;
        renderCards('player-cards', state.player.hand);
        renderCards('community-cards', state.community);

        document.getElementById('status').textContent =
            'ステージ: ' + state.stage + ' / メッセージ: ' + state.message;

        const logEl = document.getElementById('log');
        logEl.textContent = JSON.stringify(data, null, 2);
    }

    function startGame() {
        fetch('poker_api.php?action=start&npcs=3')
            .then(res => res.json())
            .then(updateView)
            .catch(err => {
                alert('通信エラー: ' + err);
            });
    }

    function sendAction(move) {
        const params = new URLSearchParams();
        params.append('action', 'player_action');
        params.append('move', move);

        fetch('poker_api.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: params.toString()
        })
            .then(res => res.json())
            .then(updateView)
            .catch(err => {
                alert('通信エラー: ' + err);
            });
    }
</script>
</div>
</body>
</html>
