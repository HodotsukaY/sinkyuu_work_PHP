<?php
// poker_api.php
session_start();
header('Content-Type: application/json; charset=utf-8');

// --------- ユーティリティ ---------
function json_response($data) {
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// ランク・スート定義
function create_deck() {
    $ranks = ['2','3','4','5','6','7','8','9','T','J','Q','K','A'];
    $suits = ['S','H','D','C']; // S:♠ H:♥ D:♦ C:♣
    $deck = [];
    foreach ($ranks as $r) {
        foreach ($suits as $s) {
            $deck[] = $r.$s; // 例: "AS", "TD"
        }
    }
    shuffle($deck);
    return $deck;
}

function draw_cards(&$deck, $n) {
    $cards = [];
    for ($i=0; $i<$n; $i++) {
        $cards[] = array_pop($deck);
    }
    return $cards;
}

// ランク → 数値
function rank_value($r) {
    switch ($r) {
        case '2': return 2;
        case '3': return 3;
        case '4': return 4;
        case '5': return 5;
        case '6': return 6;
        case '7': return 7;
        case '8': return 8;
        case '9': return 9;
        case 'T': return 10;
        case 'J': return 11;
        case 'Q': return 12;
        case 'K': return 13;
        case 'A': return 14;
    }
    return 0;
}

// 7枚からテキサスホールデムの役評価
// 戻り値: [category, kicker1, kicker2, ...] 大きい方が強い
// category: 8=SF, 7=4K, 6=FH, 5=Flush, 4=Straight, 3=Trips, 2=TwoPair, 1=OnePair, 0=High
function evaluate_7cards($cards7) {
    // $cards7: ["AS","KD",...]
    $ranks = [];
    $suits = [];
    foreach ($cards7 as $c) {
        $r = $c[0];
        $s = $c[1];
        $rv = rank_value($r);
        $ranks[] = $rv;
        $suits[] = $s;
    }

    // カウント
    $rankCount = [];
    $suitCount = [];
    foreach ($ranks as $rv) {
        if (!isset($rankCount[$rv])) $rankCount[$rv] = 0;
        $rankCount[$rv]++;
    }
    foreach ($suits as $s) {
        if (!isset($suitCount[$s])) $suitCount[$s] = 0;
        $suitCount[$s]++;
    }

    // フラッシュ判定
    $flushSuit = null;
    foreach ($suitCount as $s => $cnt) {
        if ($cnt >= 5) {
            $flushSuit = $s;
            break;
        }
    }

    // ストレート判定用にランク重複除去してソート
    $uniqRanks = array_values(array_unique($ranks));
    rsort($uniqRanks);
    // A-5ストレート用にAを1としても追加
    $hasA = in_array(14, $uniqRanks, true);
    $uniqRanksA5 = $uniqRanks;
    if ($hasA && !in_array(1, $uniqRanksA5, true)) {
        $uniqRanksA5[] = 1;
        rsort($uniqRanksA5);
    }

    $straightHigh = detect_straight_high($uniqRanksA5);

    // フラッシュのカードだけ抜き出し
    $flushCards = [];
    if ($flushSuit !== null) {
        for ($i=0; $i<count($cards7); $i++) {
            if ($suits[$i] === $flushSuit) {
                $flushCards[] = $cards7[$i];
            }
        }
        usort($flushCards, function($a, $b) {
            return rank_value($b[0]) <=> rank_value($a[0]);
        });
    }

    // ストレートフラッシュ判定
    $straightFlushHigh = null;
    if ($flushSuit !== null && count($flushCards) >= 5) {
        $fr = [];
        foreach ($flushCards as $c) {
            $fr[] = rank_value($c[0]);
        }
        $fr = array_values(array_unique($fr));
        rsort($fr);
        if (in_array(14, $fr, true) && !in_array(1, $fr, true)) {
            $fr[] = 1;
            rsort($fr);
        }
        $straightFlushHigh = detect_straight_high($fr);
    }

    // ランク出現数で並べ替え（4oak, 3oak, 2,1…）
    // ソートキー: (count desc, rank desc)
    $groups = [];
    foreach ($rankCount as $rv => $cnt) {
        $groups[] = ['rank' => $rv, 'count' => $cnt];
    }
    usort($groups, function($a, $b) {
        if ($a['count'] === $b['count']) {
            return $b['rank'] <=> $a['rank'];
        }
        return $b['count'] <=> $a['count'];
    });

    // ---- 役判定 ----

    // Straight Flush
    if ($straightFlushHigh !== null) {
        return [8, $straightFlushHigh];
    }

    // Four of a Kind
    if ($groups[0]['count'] === 4) {
        $fourRank = $groups[0]['rank'];
        $kickers = [];
        foreach ($ranks as $rv) {
            if ($rv !== $fourRank) $kickers[] = $rv;
        }
        rsort($kickers);
        return [7, $fourRank, $kickers[0]];
    }

    // Full House
    if ($groups[0]['count'] === 3) {
        $tripleRank = $groups[0]['rank'];
        $pairRank = null;
        for ($i=1; $i<count($groups); $i++) {
            if ($groups[$i]['count'] >= 2) {
                $pairRank = $groups[$i]['rank'];
                break;
            }
        }
        if ($pairRank !== null) {
            return [6, $tripleRank, $pairRank];
        }
    }

    // Flush
    if ($flushSuit !== null) {
        $vals = [];
        foreach ($flushCards as $c) {
            $vals[] = rank_value($c[0]);
        }
        rsort($vals);
        $vals = array_slice($vals, 0, 5);
        return [5, ...$vals];
    }

    // Straight
    if ($straightHigh !== null) {
        return [4, $straightHigh];
    }

    // Three of a Kind
    if ($groups[0]['count'] === 3) {
        $tripleRank = $groups[0]['rank'];
        $kickers = [];
        foreach ($ranks as $rv) {
            if ($rv !== $tripleRank) $kickers[] = $rv;
        }
        rsort($kickers);
        $kickers = array_values(array_unique($kickers));
        return [3, $tripleRank, $kickers[0], $kickers[1]];
    }

    // Two Pair
    if ($groups[0]['count'] === 2 && isset($groups[1]) && $groups[1]['count'] === 2) {
        $pair1 = max($groups[0]['rank'], $groups[1]['rank']);
        $pair2 = min($groups[0]['rank'], $groups[1]['rank']);
        $kickers = [];
        foreach ($ranks as $rv) {
            if ($rv !== $pair1 && $rv !== $pair2) $kickers[] = $rv;
        }
        rsort($kickers);
        $kickers = array_values(array_unique($kickers));
        return [2, $pair1, $pair2, $kickers[0]];
    }

    // One Pair
    if ($groups[0]['count'] === 2) {
        $pair = $groups[0]['rank'];
        $kickers = [];
        foreach ($ranks as $rv) {
            if ($rv !== $pair) $kickers[] = $rv;
        }
        rsort($kickers);
        $kickers = array_values(array_unique($kickers));
        return [1, $pair, $kickers[0], $kickers[1], $kickers[2]];
    }

    // High Card
    rsort($ranks);
    $ranks = array_values(array_unique($ranks));
    $top = array_slice($ranks, 0, 5);
    return [0, ...$top];
}

function detect_straight_high($sortedRanksDesc) {
    // 例: [14, 13, 12, 11, 10, 9] など (重複無し・降順)
    $prev = null;
    $run = 1;
    $bestHigh = null;
    for ($i=0; $i<count($sortedRanksDesc); $i++) {
        $rv = $sortedRanksDesc[$i];
        if ($prev === null) {
            $prev = $rv;
            $run = 1;
            continue;
        }
        if ($rv === $prev - 1) {
            $run++;
            if ($run >= 5) {
                // 連番5枚目の「一番上のランク」を返す
                if ($bestHigh === null || $prev+4 > $bestHigh) {
                    $bestHigh = $prev + 4; // prev+4 は最初の高いランク
                }
            }
        } elseif ($rv === $prev) {
            // 同じランクは無視（すでに uniq 済みなら来ないはず）
        } else {
            $run = 1;
        }
        $prev = $rv;
    }
    // A-5ストレートの場合は high=5 という形で入ってくる前提
    return $bestHigh;
}

// handPower を比較
function compare_power($a, $b) {
    $len = max(count($a), count($b));
    for ($i = 0; $i < $len; $i++) {
        $va = $a[$i] ?? 0;
        $vb = $b[$i] ?? 0;
        if ($va === $vb) continue;
        return $va <=> $vb;
    }
    return 0;
}

// --------- NPC アクション（超シンプルAI） ---------
function npc_take_action(&$npc, $stage) {
    if (!$npc['active']) return 'fold';
    // 20%くらいフォールド、80%コール・チェック
    $r = mt_rand(1, 100);
    if ($r <= 20) {
        $npc['active'] = false;
        return 'fold';
    }
    return ($stage === 'preflop') ? 'call' : 'check';
}

// --------- ゲーム初期化 ---------
function start_game($numNpc = 3) {
    if ($numNpc < 1) $numNpc = 1;
    if ($numNpc > 5) $numNpc = 5;

    $deck = create_deck();

    $playerHand = draw_cards($deck, 2);
    $npcs = [];
    for ($i=0; $i<$numNpc; $i++) {
        $npcs[] = [
            'id' => $i + 1,
            'hand' => draw_cards($deck, 2),
            'active' => true,
        ];
    }

    $_SESSION['poker'] = [
        'deck'       => $deck,
        'player'     => ['hand' => $playerHand, 'active' => true],
        'npcs'       => $npcs,
        'community'  => [],
        'stage'      => 'preflop', // preflop, flop, turn, river, showdown, finished
        'street'     => 0,         // 0:preflop,1:flop,2:turn,3:river,4:showdown
        'message'    => 'ゲーム開始',
    ];
    return $_SESSION['poker'];
}

// ステージを進める（カードをめくる）
function advance_stage() {
    $state = &$_SESSION['poker'];
    if (!isset($state)) return;

    if ($state['street'] === 0) {
        // flop
        $state['community'] = array_merge($state['community'], draw_cards($state['deck'], 3));
        $state['stage'] = 'flop';
        $state['street'] = 1;
        $state['message'] = 'フロップが公開されました';
    } elseif ($state['street'] === 1) {
        // turn
        $state['community'] = array_merge($state['community'], draw_cards($state['deck'], 1));
        $state['stage'] = 'turn';
        $state['street'] = 2;
        $state['message'] = 'ターンカードが公開されました';
    } elseif ($state['street'] === 2) {
        // river
        $state['community'] = array_merge($state['community'], draw_cards($state['deck'], 1));
        $state['stage'] = 'river';
        $state['street'] = 3;
        $state['message'] = 'リバーカードが公開されました';
    } elseif ($state['street'] === 3) {
        // showdown
        $state['stage'] = 'showdown';
        $state['street'] = 4;
        showdown();
    }
}

// ショーダウン処理
function showdown() {
    $state = &$_SESSION['poker'];
    $community = $state['community'];

    $results = [];

    if ($state['player']['active']) {
        $cards7 = array_merge($state['player']['hand'], $community);
        $power = evaluate_7cards($cards7);
        $results['player'] = [
            'label' => 'あなた',
            'active' => true,
            'hand' => $state['player']['hand'],
            'power' => $power,
        ];
    } else {
        $results['player'] = [
            'label' => 'あなた',
            'active' => false,
            'hand' => $state['player']['hand'],
            'power' => null,
        ];
    }

    foreach ($state['npcs'] as $npc) {
        $key = 'npc'.$npc['id'];
        if ($npc['active']) {
            $cards7 = array_merge($npc['hand'], $community);
            $power = evaluate_7cards($cards7);
            $results[$key] = [
                'label' => 'NPC'.$npc['id'],
                'active' => true,
                'hand' => $npc['hand'],
                'power' => $power,
            ];
        } else {
            $results[$key] = [
                'label' => 'NPC'.$npc['id'],
                'active' => false,
                'hand' => $npc['hand'],
                'power' => null,
            ];
        }
    }

    // 勝者判定
    $winnerKeys = [];
    $bestPower = null;
    foreach ($results as $key => $info) {
        if (!$info['active'] || $info['power'] === null) continue;
        if ($bestPower === null) {
            $bestPower = $info['power'];
            $winnerKeys = [$key];
        } else {
            $cmp = compare_power($info['power'], $bestPower);
            if ($cmp > 0) {
                $bestPower = $info['power'];
                $winnerKeys = [$key];
            } elseif ($cmp === 0) {
                $winnerKeys[] = $key;
            }
        }
    }

    $state['showdown'] = [
        'results' => $results,
        'winners' => $winnerKeys,
    ];

    // メッセージ
    if (empty($winnerKeys)) {
        $state['message'] = '全員フォールドか、勝者なし';
    } else {
        $names = [];
        foreach ($winnerKeys as $wk) {
            $names[] = $results[$wk]['label'];
        }
        $state['message'] = '勝者: '.implode(' & ', $names);
    }
    $state['stage'] = 'finished';
}

// プレイヤー行動
function handle_player_action($move) {
    $state = &$_SESSION['poker'];
    if (!isset($state)) {
        return ['error' => 'ゲームが開始されていません'];
    }
    if ($state['stage'] === 'finished') {
        return ['error' => 'すでに終了したゲームです'];
    }

    if ($move === 'fold') {
        $state['player']['active'] = false;
        $state['message'] = 'あなたはフォールドしました';
        // 残っているNPCの中で一番強い人が勝ち扱い（簡略化）
        $state['stage'] = 'showdown';
        $state['street'] = 4;
        showdown();
        return $state;
    } elseif ($move === 'check' || $move === 'call') {
        // NPC たちのアクションを実行
        foreach ($state['npcs'] as &$npc) {
            npc_take_action($npc, $state['stage']);
        }
        unset($npc);

        // アクティブプレイヤー数チェック
        $activeCount = $state['player']['active'] ? 1 : 0;
        foreach ($state['npcs'] as $npc) {
            if ($npc['active']) $activeCount++;
        }

        if ($activeCount <= 1) {
            // 誰か以外全員フォールドしたので即ショーダウン
            $state['stage'] = 'showdown';
            $state['street'] = 4;
            showdown();
        } else {
            // 次のステージへ
            advance_stage();
        }
        return $state;
    } else {
        return ['error' => '不正なアクションです'];
    }
}

// --------- ルーティング ---------
$action = $_GET['action'] ?? $_POST['action'] ?? null;

if ($action === 'start') {
    $numNpc = isset($_GET['npcs']) ? intval($_GET['npcs']) :
              (isset($_POST['npcs']) ? intval($_POST['npcs']) : 3);
    $state = start_game($numNpc);
    json_response([
        'ok' => true,
        'state' => [
            'player'    => ['hand' => $state['player']['hand'], 'active' => $state['player']['active']],
            'npcs'      => array_map(fn($n)=>['id'=>$n['id'], 'active'=>$n['active']], $state['npcs']),
            'community' => $state['community'],
            'stage'     => $state['stage'],
            'message'   => $state['message'],
        ]
    ]);
} elseif ($action === 'player_action') {
    $move = $_GET['move'] ?? $_POST['move'] ?? null;
    if (!$move) {
        json_response(['ok' => false, 'error' => 'move パラメータが必要です (check/call/fold)']);
    }
    $state = handle_player_action($move);
    if (isset($state['error'])) {
        json_response(['ok' => false, 'error' => $state['error']]);
    }

    $response = [
        'ok' => true,
        'state' => [
            'player'    => ['hand' => $state['player']['hand'], 'active' => $state['player']['active']],
            'npcs'      => array_map(fn($n)=>['id'=>$n['id'], 'active'=>$n['active']], $state['npcs']),
            'community' => $state['community'],
            'stage'     => $state['stage'],
            'message'   => $state['message'],
        ]
    ];

    if (isset($state['showdown'])) {
        $response['showdown'] = $state['showdown'];
    }

    json_response($response);
} elseif ($action === 'state') {
    $state = $_SESSION['poker'] ?? null;
    if (!$state) {
        json_response(['ok' => false, 'error' => 'ゲームが開始されていません']);
    }
    $res = [
        'ok' => true,
        'state' => [
            'player'    => ['hand' => $state['player']['hand'], 'active' => $state['player']['active']],
            'npcs'      => array_map(fn($n)=>['id'=>$n['id'], 'active'=>$n['active']], $state['npcs']),
            'community' => $state['community'],
            'stage'     => $state['stage'],
            'message'   => $state['message'],
        ]
    ];
    if (isset($state['showdown'])) {
        $res['showdown'] = $state['showdown'];
    }
    json_response($res);
} else {
    json_response(['ok' => false, 'error' => 'action パラメータが必要です (start / player_action / state)']);
}
