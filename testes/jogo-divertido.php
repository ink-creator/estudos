<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>👾 Alien Clicker — O tédio não tem chance</title>
    <style>
        :root {
            --bg: #0f0f23;
            --panel: #1a1a3a;
            --accent: #00f5d4;
            --accent2: #ff006e;
            --accent3: #fee440;
            --text: #ffffff;
            --muted: #8888aa;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow: hidden;
            user-select: none;
        }

        header {
            width: 100%;
            padding: 1rem 2rem;
            background: var(--panel);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--accent);
            box-shadow: 0 0 20px rgba(0, 245, 212, 0.2);
            z-index: 10;
        }

        h1 {
            font-size: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 10px var(--accent);
        }

        .stats {
            display: flex;
            gap: 1.5rem;
            font-size: 1.1rem;
        }

        .stat {
            text-align: center;
        }

        .stat span {
            display: block;
            font-size: 1.4rem;
            font-weight: bold;
            color: var(--accent);
        }

        #game-area {
            position: relative;
            width: 100%;
            flex: 1;
            overflow: hidden;
            cursor: crosshair;
        }

        .alien {
            position: absolute;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            cursor: pointer;
            transition: transform 0.1s;
            box-shadow: 0 0 20px currentColor;
            animation: pop 0.3s ease-out;
        }

        .alien:hover {
            transform: scale(1.15);
        }

        .alien.normal { color: var(--accent); background: radial-gradient(circle at 30% 30%, #00f5d4, #007f6e); }
        .alien.fast { color: var(--accent3); background: radial-gradient(circle at 30% 30%, #fee440, #b8860b); }
        .alien.boss { color: var(--accent2); background: radial-gradient(circle at 30% 30%, #ff006e, #8b0040); width: 100px; height: 100px; font-size: 3.5rem; }

        @keyframes pop {
            0% { transform: scale(0); }
            70% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        @keyframes floatUp {
            0% { opacity: 1; transform: translateY(0) scale(1); }
            100% { opacity: 0; transform: translateY(-50px) scale(1.5); }
        }

        .score-popup {
            position: absolute;
            color: var(--accent3);
            font-weight: bold;
            font-size: 1.3rem;
            pointer-events: none;
            animation: floatUp 0.8s ease-out forwards;
            text-shadow: 0 0 10px rgba(0,0,0,0.8);
        }

        #overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 15, 35, 0.95);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 100;
            text-align: center;
        }

        #overlay h2 {
            font-size: 3rem;
            margin-bottom: 1rem;
            text-shadow: 0 0 20px var(--accent);
        }

        #overlay p {
            font-size: 1.2rem;
            color: var(--muted);
            margin-bottom: 2rem;
            max-width: 500px;
            line-height: 1.6;
        }

        .btn {
            background: linear-gradient(135deg, var(--accent), #007f6e);
            color: #000;
            border: none;
            padding: 1rem 2.5rem;
            font-size: 1.3rem;
            font-weight: bold;
            border-radius: 50px;
            cursor: pointer;
            box-shadow: 0 0 30px rgba(0, 245, 212, 0.4);
            transition: transform 0.2s, box-shadow 0.2s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0 50px rgba(0, 245, 212, 0.6);
        }

        .combo {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 4rem;
            font-weight: 900;
            color: var(--accent2);
            text-shadow: 0 0 30px var(--accent2);
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.1s;
            z-index: 50;
        }

        .combo.show {
            opacity: 1;
            animation: comboPulse 0.4s ease-out;
        }

        @keyframes comboPulse {
            0% { transform: translate(-50%, -50%) scale(0.5); }
            50% { transform: translate(-50%, -50%) scale(1.3); }
            100% { transform: translate(-50%, -50%) scale(1); }
        }

        .lives {
            color: var(--accent2);
            font-size: 1.5rem;
        }

        footer {
            padding: 0.5rem;
            color: var(--muted);
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

<?php
session_start();
$record = $_SESSION['record'] ?? 0;
?>

<header>
    <h1>👾 Alien Clicker</h1>
    <div class="stats">
        <div class="stat">Pontos<span id="score">0</span></div>
        <div class="stat">Nível<span id="level">1</span></div>
        <div class="stat">Tempo<span id="time">30</span>s</div>
        <div class="stat">Recorde<span id="record"><?php echo $record; ?></span></div>
        <div class="stat lives">Vidas <span id="lives">❤️❤️❤️</span></div>
    </div>
</header>

<div id="game-area"></div>

<div id="overlay">
    <h2>Alien Clicker</h2>
    <p>
        Clique nos aliens antes que eles sumam!<br>
        👽 Normal = +10 pts &nbsp;|&nbsp; ⚡ Rápido = +25 pts &nbsp;|&nbsp; 👹 Chefão = +50 pts<br>
        Não deixe nenhum escapar ou você perde uma vida. <br>
        Acerte vários seguidos para fazer COMBO! 🔥
    </p>
    <button class="btn" onclick="startGame()">Jogar Agora</button>
</div>

<div id="combo" class="combo">COMBO x2!</div>

<footer>Feito pra matar o tédio — salvo via sessão PHP</footer>

<script>
    const gameArea = document.getElementById('game-area');
    const scoreEl = document.getElementById('score');
    const levelEl = document.getElementById('level');
    const timeEl = document.getElementById('time');
    const livesEl = document.getElementById('lives');
    const overlay = document.getElementById('overlay');
    const comboEl = document.getElementById('combo');

    let score = 0;
    let level = 1;
    let timeLeft = 30;
    let lives = 3;
    let gameInterval;
    let spawnInterval;
    let combo = 0;
    let comboTimer;
    let activeAliens = [];
    let isPlaying = false;

    const aliens = [
        { emoji: '👽', type: 'normal', points: 10, duration: 2500 },
        { emoji: '👾', type: 'normal', points: 10, duration: 2300 },
        { emoji: '🛸', type: 'normal', points: 15, duration: 2000 },
        { emoji: '⚡', type: 'fast', points: 25, duration: 1200 },
        { emoji: '🔥', type: 'fast', points: 30, duration: 1000 },
        { emoji: '👹', type: 'boss', points: 50, duration: 1800 }
    ];

    function startGame() {
        score = 0;
        level = 1;
        timeLeft = 30;
        lives = 3;
        combo = 0;
        activeAliens = [];
        isPlaying = true;
        gameArea.innerHTML = '';
        updateUI();
        overlay.style.display = 'none';

        gameInterval = setInterval(() => {
            timeLeft--;
            if (timeLeft <= 0) endGame();
            updateUI();
        }, 1000);

        scheduleSpawn();
    }

    function scheduleSpawn() {
        if (!isPlaying) return;
        spawnAlien();
        const delay = Math.max(400, 1200 - (level * 80));
        spawnInterval = setTimeout(scheduleSpawn, delay);
    }

    function spawnAlien() {
        const maxAliens = 3 + Math.floor(level / 2);
        if (activeAliens.length >= maxAliens) return;

        const alienData = pickAlien();
        const el = document.createElement('div');
        el.className = `alien ${alienData.type}`;
        el.textContent = alienData.emoji;

        const size = alienData.type === 'boss' ? 100 : 70;
        const x = Math.random() * (gameArea.clientWidth - size - 20) + 10;
        const y = Math.random() * (gameArea.clientHeight - size - 20) + 10;

        el.style.left = x + 'px';
        el.style.top = y + 'px';

        const alien = { el, ...alienData, id: Date.now() + Math.random() };
        activeAliens.push(alien);
        gameArea.appendChild(el);

        el.addEventListener('mousedown', (e) => {
            e.stopPropagation();
            hitAlien(alien, e.clientX, e.clientY);
        });

        alien.timeout = setTimeout(() => {
            if (activeAliens.includes(alien)) {
                missAlien(alien);
            }
        }, alienData.duration);
    }

    function pickAlien() {
        const r = Math.random();
        if (r < 0.55) return aliens[Math.floor(Math.random() * 3)];
        if (r < 0.85) return aliens[3 + Math.floor(Math.random() * 2)];
        return aliens[5];
    }

    function hitAlien(alien, x, y) {
        removeAlien(alien);

        combo++;
        clearTimeout(comboTimer);
        comboTimer = setTimeout(() => { combo = 0; }, 1200);

        const multiplier = 1 + Math.floor(combo / 5) * 0.5;
        const gained = Math.round(alien.points * multiplier);
        score += gained;

        showFloatingText(`+${gained}`, x, y);
        if (combo > 1 && combo % 5 === 0) showCombo(Math.floor(combo / 5));

        if (score > level * 150) {
            level++;
            timeLeft += 5;
            showFloatingText('LEVEL UP!', x, y - 30, '#00f5d4');
        }

        updateUI();
    }

    function missAlien(alien) {
        removeAlien(alien);
        combo = 0;
        lives--;
        showFloatingText('-1 ❤️', parseInt(alien.el.style.left) + 20, parseInt(alien.el.style.top), '#ff006e');
        if (lives <= 0) endGame();
        updateUI();
    }

    function removeAlien(alien) {
        clearTimeout(alien.timeout);
        const idx = activeAliens.indexOf(alien);
        if (idx > -1) activeAliens.splice(idx, 1);
        if (alien.el.parentNode) alien.el.parentNode.removeChild(alien.el);
    }

    function showFloatingText(text, x, y, color = null) {
        const pop = document.createElement('div');
        pop.className = 'score-popup';
        pop.textContent = text;
        pop.style.left = x + 'px';
        pop.style.top = y + 'px';
        if (color) pop.style.color = color;
        document.body.appendChild(pop);
        setTimeout(() => pop.remove(), 800);
    }

    function showCombo(multiplier) {
        comboEl.textContent = `COMBO x${multiplier + 1}! 🔥`;
        comboEl.classList.remove('show');
        void comboEl.offsetWidth;
        comboEl.classList.add('show');
        setTimeout(() => comboEl.classList.remove('show'), 600);
    }

    function updateUI() {
        scoreEl.textContent = score;
        levelEl.textContent = level;
        timeEl.textContent = timeLeft;
        livesEl.textContent = '❤️'.repeat(Math.max(0, lives));
    }

    function endGame() {
        isPlaying = false;
        clearInterval(gameInterval);
        clearTimeout(spawnInterval);
        activeAliens.forEach(a => clearTimeout(a.timeout));
        activeAliens = [];

        fetch('?action=save&score=' + score)
            .then(r => r.json())
            .then(data => {
                document.getElementById('record').textContent = data.record;
                overlay.innerHTML = `
                    <h2>Fim de Jogo!</h2>
                    <p>
                        Você fez <strong>${score}</strong> pontos no nível ${level}!<br>
                        Recorde pessoal: <strong>${data.record}</strong><br>
                        ${score >= data.record ? '🎉 NOVO RECORDE! 🎉' : 'Tente bater seu recorde! 💪'}
                    </p>
                    <button class="btn" onclick="startGame()">Jogar de Novo</button>
                `;
                overlay.style.display = 'flex';
            });
    }
</script>

<?php
if (isset($_GET['action']) && $_GET['action'] === 'save' && isset($_GET['score'])) {
    $score = intval($_GET['score']);
    if (!isset($_SESSION['record']) || $score > $_SESSION['record']) {
        $_SESSION['record'] = $score;
    }
    header('Content-Type: application/json');
    echo json_encode(['record' => $_SESSION['record']]);
    exit;
}
?>

</body>
</html>
