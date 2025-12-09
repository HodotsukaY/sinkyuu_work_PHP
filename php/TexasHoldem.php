<?php // TexasHoldem.php （PHPサーバー上で動く画面） ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>テキサスホールデム（自分 vs NPC）</title>
    <style>
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

<h1>テキサスホールデム（自分 vs NPC）</h1>

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

</body>
</html>
