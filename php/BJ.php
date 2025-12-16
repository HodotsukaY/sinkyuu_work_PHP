<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>ブラックジャック（配りアニメ + NEW GAME）</title>
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

/* http://localhost/BJ.php */
/* ---------- 背景 ---------- */
html, body {
  margin: 0;
  padding: 0;
  height: 100%;
  font-family: sans-serif;
  color: #fff;
  background: radial-gradient(circle, #1d6b33 0%, #0f4720 70%, #0a2f14 100%);
  background-size: cover;
  background-attachment: fixed;
}

/* ---------- レイアウト ---------- */
.table-area {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 18px 12px;
  box-sizing: border-box;
  position: relative;
}

.opponent-area, .player-area { text-align: center; width:100%; max-width:900px; }
.opponent-cards, .player-cards {
  display: flex;
  gap: 20px;
  justify-content: center;
  margin-top: 8px;
  min-height: 170px;
}

/* スロット（カードが入る領域） */
.card-slot { width:110px; height:160px; }

/* 実際のカード画像（固定） */
.slot-img {
  width:110px;
  height:160px;
  border-radius:8px;
  box-shadow: 0 0 10px rgba(0,0,0,0.6);
  display:block;
  backface-visibility: hidden;
}

/* 浮遊カード（アニメ用） */
.floating-card {
  position: fixed;
  width:110px;
  height:160px;
  border-radius:8px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.6);
  transform-origin: center;
  will-change: transform, left, top;
  z-index: 9998;
}

/* ---------- ボタン ---------- */
.action-buttons {
  display:flex;
  gap:14px;
  justify-content:center;
  margin-top: 20px;
}
.btn {
  background: #f7c843;
  color: #000;
  padding: 12px 18px;
  font-size: 16px;
  border-radius: 10px;
  border: none;
  cursor: pointer;
  font-weight: bold;
}
.btn:disabled {
  background: #777 !important;
  color: #ccc !important;
  cursor: default;
  opacity: 0.6;
}

/* ---------- チップバー ---------- */
.chip-bar {
  position: fixed;
  left: 10px;
  bottom: 10px;
  display: flex;
  flex-direction: column;
  gap: 8px;
  z-index: 9999;
}
.chip-row { display:flex; gap:10px; }
.chip-row.centered { margin-left:40px; }
.chip-bar img { width:70px; cursor:pointer; transition: transform .15s; }
.chip-bar img:hover { transform: scale(1.1); }

/* ---------- 中央メッセージゾーン ---------- */
#center-message {
  height: 110px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 40px;
  font-weight: 800;
  color: #fff;
  text-shadow: 0 0 10px rgba(0,0,0,0.7);
  opacity: 0;
  transform: scale(0.98);
  transition: opacity .45s ease, transform .35s ease;
  pointer-events: none;
  margin: 12px 0;
}
#center-message.show {
  opacity: 1;
  transform: scale(1);
}

/* 思考中のドット（横並び） */
#thinking-dots {
  display: flex;
  gap: 6px;
  margin-left: 8px;
  align-items: center;
}
@keyframes blinkDots {
  0% { opacity: 0; transform: translateY(0); }
  30% { opacity: 1; transform: translateY(0); }
  60% { opacity: 0.4; transform: translateY(-2px); }
  100% { opacity: 0; transform: translateY(0); }
}
#thinking-dots span {
  width:8px;
  height:8px;
  background: #fff;
  border-radius:50%;
  display:inline-block;
  animation: blinkDots 1.2s infinite;
}
#thinking-dots span:nth-child(2){ animation-delay: .15s; }
#thinking-dots span:nth-child(3){ animation-delay: .30s; }

/* デッキ表示位置 */
#deck-pos {
  position: fixed;
  right: 110px;
  top: 30px;
  width: 110px;
  height: 160px;
  z-index: 9997;
  pointer-events: none;
  opacity: 0.95;
}
#deck-pos img { width:110px; height:160px; border-radius:8px; box-shadow: 0 0 12px rgba(0,0,0,0.4); }

/* レスポンシブ */
@media (max-width:600px) {
  #center-message { font-size: 26px; height: 76px; }
  .chip-bar img { width: 56px; }
  .card-slot, .slot-img, .floating-card { width: 86px; height:126px; }
  #deck-pos { right:60px; top:14px; width:86px; height:126px; }
}
/* ===== 画像のUIに合わせた戻る＋ハンバーガー（統一） ===== */
body{ padding-top: 72px; } /* 92pxでもOK。BJは72で十分 */

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

/* ===== ゲームボタンを .btn から分離（競合防止） ===== */
.game-btn{
  background:#f7c843;
  color:#000;
  padding:12px 18px;
  font-size:16px;
  border-radius:10px;
  border:none;
  cursor:pointer;
  font-weight:bold;
}
.game-btn:disabled{
  background:#777 !important;
  color:#ccc !important;
  cursor:default;
  opacity:.6;
}

/* デッキ位置を少し下げてヘッダと被らないように */
#deck-pos{ top: 86px; right: 14px; } /* ←ここ重要 */
@media (max-width:600px){
  #deck-pos{ top: 74px; right: 14px; }
}

</style>
</head>
<body>

<?php // header include (置くだけで HTML に挿入されます) ?>
<header class="top-ui">
  <button class="ui-back" type="button" onclick="goBackBJ()">
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

<!-- デッキの見た目 -->
<div id="deck-pos"><img src="https://deckofcardsapi.com/static/img/back.png" alt="deck" /></div>

<div class="table-area">

  <!-- 相手 -->
  <div class="opponent-area">
    <div id="opponent-name">ディーラー</div>
    <div id="opponent-total">合計: ?</div>
    <div class="opponent-cards" id="opponent-cards"></div>
  </div>

  <!-- 中央メッセージゾーン -->
  <div id="center-message"></div>

  <!-- プレイヤー -->
  <div class="player-area">
    <div style="height:10px;"></div>
    <div id="player-total">合計: ?</div>
    <div class="player-cards" id="player-cards"></div>

    <div class="action-buttons">
      <button class="game-btn" id="hit-btn">HIT</button>
      <button class="game-btn" id="stand-btn">STAND</button>
      <button class="game-btn" id="newgame-btn" style="display:none;">NEW GAME</button>


    </div>
  </div>

</div>

<!-- チップバー（そのまま） -->
<div class="chip-bar">
  <div class="chip-row">
    <img src="img/chip1.png" data-value="1" />
    <img src="img/chip5.png" data-value="5" />
    <img src="img/chip10.png" data-value="10" />
    <img src="img/chip25.png" data-value="25" />
  </div>
  <div class="chip-row centered">
    <img src="img/chip50.png" data-value="50" />
    <img src="img/chip100.png" data-value="100" />
    <img src="img/chip1000.png" data-value="1000" />
  </div>
</div>

<script>
/* -------------------------
  変数 / 設定
   ------------------------- */
const CARD_BACK = "https://deckofcardsapi.com/static/img/back.png";
const centerBox = document.getElementById("center-message");

let deckId = "";
let playerCards = [];
let opponentCards = [];
let showOpponentSecondCard = false;

const dealingDelay = 480; // ms between deals
const flipDuration = 380;
const flyDuration = 420;

/* ---------- ユーティリティ ---------- */
function wait(ms){ return new Promise(r => setTimeout(r, ms)); }

function getSlotRect(container) {
  const placeholder = document.createElement("div");
  placeholder.className = "card-slot";
  placeholder.style.visibility = "hidden";
  container.appendChild(placeholder);
  const rect = placeholder.getBoundingClientRect();
  container.removeChild(placeholder);
  return rect;
}

function createFloatingCard(src) {
  const f = document.createElement("img");
  f.src = src;
  f.className = "floating-card";
  f.style.left = "0px";
  f.style.top = "0px";
  f.style.transition = `transform ${flyDuration}ms cubic-bezier(.2,.9,.2,1), left ${flyDuration}ms, top ${flyDuration}ms`;
  document.body.appendChild(f);
  return f;
}

function flyTo(floating, targetRect, revealFlip=false, finalSrc=null) {
  return new Promise(resolve => {
    const deckEl = document.getElementById("deck-pos");
    const dRect = deckEl.getBoundingClientRect();

    floating.style.left = `${dRect.left}px`;
    floating.style.top = `${dRect.top}px`;
    floating.style.transform = `translate3d(0px,0px,0) rotateY(0deg)`;

    void floating.offsetWidth;

    const dx = targetRect.left - dRect.left;
    const dy = targetRect.top - dRect.top;

    floating.style.transform = `translate3d(${dx}px, ${dy}px, 0px) rotateY(0deg)`;

    setTimeout(async () => {
      if (revealFlip) {
        floating.style.transition = `transform ${flipDuration/1000}s ease-in`;
        floating.style.transform += " rotateY(90deg)";
        await wait(flipDuration/2);
        if (finalSrc) floating.src = finalSrc;
        floating.style.transform = `translate3d(${dx}px, ${dy}px, 0px) rotateY(0deg)`;
        await wait(flipDuration/2 + 30);
      } else {
        await wait(90);
      }
      resolve();
    }, flyDuration + 10);
  });
}

/* ---------- スロットにカードを追加 ---------- */
function appendCardToSlot(container, cardSrc, isBack=false, dataFrontSrc=null) {
  const img = document.createElement("img");
  img.className = "slot-img";
  img.src = isBack ? CARD_BACK : cardSrc;
  if (dataFrontSrc) img.dataset.front = dataFrontSrc;
  container.appendChild(img);
  return img;
}

/* ---------- Deck API ---------- */
async function newDeck() {
  const res = await fetch("https://deckofcardsapi.com/api/deck/new/shuffle/?deck_count=6");
  const data = await res.json();
  deckId = data.deck_id;
}
async function drawCard(count=1) {
  const res = await fetch(`https://deckofcardsapi.com/api/deck/${deckId}/draw/?count=${count}`);
  return await res.json();
}

/* ---------- メッセージ制御 ---------- */
function showMessage(text) {
  centerBox.classList.remove("show");
  setTimeout(() => { centerBox.innerHTML = text; centerBox.classList.add("show"); }, 30);
}
function showThinking() {
  centerBox.classList.remove("show");
  setTimeout(() => {
    centerBox.innerHTML = `思考中<span id="thinking-dots"><span></span><span></span><span></span></span>`;
    centerBox.classList.add("show");
  }, 30);
}
function clearMessage() {
  centerBox.classList.remove("show");
  setTimeout(()=> centerBox.textContent = "", 300);
}

/* ---------- 更新 ---------- */
function updateTotals() {
  const pEl = document.getElementById("player-total");
  const oEl = document.getElementById("opponent-total");
  pEl.textContent = `合計: ${calcTotal(playerCards)}`;
  oEl.textContent = `合計: ${showOpponentSecondCard ? calcTotal(opponentCards) : "?"}`;
}

/* ---------- 合計計算 ---------- */
function calcTotal(cards) {
  let total = 0;
  let aces = 0;
  cards.forEach(c => {
    let v = c.value;
    if (["KING","QUEEN","JACK"].includes(v)) v = 10;
    else if (v === "ACE") { v = 11; aces++; }
    else v = Number(v);
    total += v;
  });
  while (total > 21 && aces > 0) { total -= 10; aces--; }
  return total;
}

/* ---------- 配り（アニメ） ---------- */
async function dealInitialFour() {
  document.getElementById("player-cards").innerHTML = "";
  document.getElementById("opponent-cards").innerHTML = "";
  playerCards = []; opponentCards = [];
  showOpponentSecondCard = false;
  // NOTE: intentionally NOT clearing centerBox here — keep previous result until NEW GAME pressed
  document.getElementById("newgame-btn").style.display = "none";

  const seqTargets = [
    { target: "player-cards", reveal: true },
    { target: "opponent-cards", reveal: false },
    { target: "player-cards", reveal: true },
    { target: "opponent-cards", reveal: false }
  ];

  for (let i=0;i<4;i++){
    const resp = await drawCard(1);
    const card = resp.cards[0];
    const container = document.getElementById(seqTargets[i].target);
    const targetRect = getSlotRect(container);
    const floating = createFloatingCard(CARD_BACK);
    await flyTo(floating, targetRect, seqTargets[i].reveal, card.image);
    document.body.removeChild(floating);

    if (seqTargets[i].target === "player-cards") {
      appendCardToSlot(container, card.image, false, null);
      playerCards.push(card);
    } else {
      const isSecond = (i===3);
      if (isSecond) {
        appendCardToSlot(container, null, true, card.image);
      } else {
        appendCardToSlot(container, card.image, false, null);
      }
      opponentCards.push(card);
    }

    updateTotals();
    await wait(dealingDelay);
  }

  // enable buttons after deal
  document.getElementById("hit-btn").disabled = false;
  document.getElementById("stand-btn").disabled = false;
}

/* ---------- player HIT ---------- */
async function playerHit() {
  document.getElementById("hit-btn").disabled = true;
  const resp = await drawCard(1);
  const card = resp.cards[0];
  const container = document.getElementById("player-cards");
  const targetRect = getSlotRect(container);
  const floating = createFloatingCard(CARD_BACK);
  await flyTo(floating, targetRect, true, card.image);
  document.body.removeChild(floating);
  appendCardToSlot(container, card.image, false, null);
  playerCards.push(card);
  updateTotals();
  document.getElementById("hit-btn").disabled = false;
}

/* ---------- reveal dealer second ---------- */
async function revealDealerSecond() {
  showOpponentSecondCard = true;
  const opp = document.getElementById("opponent-cards");
  const imgs = opp.querySelectorAll(".slot-img");
  if (imgs.length >= 2) {
    const secondImg = imgs[1];
    const front = secondImg.dataset.front;
    if (front) {
      secondImg.style.transition = `transform ${flipDuration/1000}s ease-in`;
      secondImg.style.transform = `rotateY(90deg)`;
      await wait(flipDuration/2);
      secondImg.src = front;
      secondImg.style.transform = `rotateY(0deg)`;
      await wait(flipDuration/2 + 30);
      secondImg.style.transition = "";
      delete secondImg.dataset.front;
    }
  }
  updateTotals();
}

/* ---------- dealer turn ---------- */
async function dealerTurn() {
  document.getElementById("hit-btn").disabled = true;
  document.getElementById("stand-btn").disabled = true;

  showThinking();
  await wait(600 + Math.random()*900);
  await revealDealerSecond();

  await wait(700 + Math.random()*900);

  while (calcTotal(opponentCards) < 17) {
    showThinking();
    await wait(900 + Math.random()*900);
    const resp = await drawCard(1);
    const card = resp.cards[0];
    opponentCards.push(card);

    const container = document.getElementById("opponent-cards");
    const targetRect = getSlotRect(container);
    const floating = createFloatingCard(CARD_BACK);
    await flyTo(floating, targetRect, true, card.image);
    document.body.removeChild(floating);
    appendCardToSlot(container, card.image, false, null);

    updateTotals();
  }

  // do not clear the center message here — we want final result to remain
  await wait(200);
  showResult();
}

/* ---------- 結果表示 ---------- */
function showResult() {
  const p = calcTotal(playerCards);
  const o = calcTotal(opponentCards);
  let msg = "";
  if (p > 21) msg = "あなたの負け";
  else if (o > 21) msg = "あなたの勝ち！";
  else if (p > o) msg = "あなたの勝ち！";
  else if (p < o) msg = "あなたの負け";
  else msg = "引き分け";

  // 常時表示 until NEW GAME pressed
  centerBox.innerHTML = msg;
  centerBox.classList.add("show");

  // disable action buttons
  document.getElementById("hit-btn").disabled = true;
  document.getElementById("stand-btn").disabled = true;

  // show NEW GAME
  document.getElementById("newgame-btn").style.display = "inline-block";
}

/* ---------- NEW GAME ---------- */
document.getElementById("newgame-btn").addEventListener("click", async () => {
  // hide newgame
  document.getElementById("newgame-btn").style.display = "none";

  // clear slots and message (only here we clear the persistent message)
  document.getElementById("player-cards").innerHTML = "";
  document.getElementById("opponent-cards").innerHTML = "";
  centerBox.classList.remove("show");
  centerBox.textContent = "";

  // reset arrays
  playerCards = [];
  opponentCards = [];
  showOpponentSecondCard = false;

  // start fresh
  await newDeck();
  await dealInitialFour();
});
function goBackBJ(){
  if(history.length > 1) history.back();
  else location.href = "GameChange.php";
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
