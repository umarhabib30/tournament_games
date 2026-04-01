<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Number Memory Game</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;700;800;900&family=DM+Sans:wght@300;400;500;600&display=swap"
      rel="stylesheet"
    />
    <style>
      :root {
        --c1: #f97316;
        --c2: #fb923c;
        --c3: #fde68a;
        --bg: #0d0d1a;
        --card-back: #1e1e3a;
      }
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }
      html {
        font-family: "DM Sans", sans-serif;
      }
      body {
        background: var(--bg);
        min-height: 100vh;
        overflow-x: hidden;
      }
      body::before {
        content: "";
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        background:
          radial-gradient(
            ellipse 60% 40% at 20% 10%,
            rgba(249, 115, 22, 0.12) 0%,
            transparent 70%
          ),
          radial-gradient(
            ellipse 50% 50% at 80% 90%,
            rgba(251, 146, 60, 0.08) 0%,
            transparent 60%
          ),
          radial-gradient(
            ellipse 40% 40% at 60% 40%,
            rgba(253, 230, 138, 0.04) 0%,
            transparent 60%
          );
      }

      /* ── stat pills ── */
      .stat-pill {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 14px;
        padding: 10px 18px;
        text-align: center;
        min-width: 76px;
      }
      .stat-pill .lbl {
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.08em;
        color: rgba(255, 255, 255, 0.38);
        text-transform: uppercase;
      }
      .stat-pill .val {
        font-family: "Baloo 2", sans-serif;
        font-size: 22px;
        font-weight: 900;
        color: #fff;
        line-height: 1.1;
      }

      /* ── cards ── */
      .card {
        perspective: 900px;
        cursor: pointer;
      }
      .card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        transform-style: preserve-3d;
        transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1);
      }
      .card.flipped .card-inner,
      .card.matched .card-inner {
        transform: rotateY(180deg);
      }
      .card:not(.flipped):not(.matched):hover .card-inner {
        transform: rotateY(12deg) scale(1.06);
      }
      .card-face {
        position: absolute;
        inset: 0;
        border-radius: 14px;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .card-back {
        background: var(--card-back);
        border: 1.5px solid rgba(249, 115, 22, 0.25);
        box-shadow:
          0 2px 12px rgba(0, 0, 0, 0.4),
          inset 0 1px 0 rgba(255, 255, 255, 0.06);
      }
      .card-back::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: 14px;
        background-image: radial-gradient(
          circle,
          rgba(249, 115, 22, 0.18) 1px,
          transparent 1px
        );
        background-size: 10px 10px;
      }
      .card-back .q-mark {
        font-family: "Baloo 2", sans-serif;
        font-size: clamp(18px, 4vw, 28px);
        font-weight: 900;
        color: rgba(249, 115, 22, 0.55);
        position: relative;
        z-index: 1;
      }
      .card-front {
        background: linear-gradient(135deg, #1a2a4a, #1e3560);
        border: 1.5px solid rgba(96, 165, 250, 0.4);
        box-shadow: 0 4px 20px rgba(59, 130, 246, 0.2);
        transform: rotateY(180deg);
      }
      .card-front .num {
        font-family: "Baloo 2", sans-serif;
        font-size: clamp(20px, 4vw, 36px);
        font-weight: 900;
        color: #fff;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
      }
      .card.matched .card-front {
        background: linear-gradient(135deg, #052e16, #064e3b);
        border-color: rgba(52, 211, 153, 0.6);
        box-shadow:
          0 0 18px rgba(52, 211, 153, 0.35),
          0 4px 20px rgba(16, 185, 129, 0.25);
        animation: popMatch 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
      }
      .card.matched .card-front .num {
        color: #6ee7b7;
      }
      @keyframes popMatch {
        0% {
          transform: rotateY(180deg) scale(1);
        }
        50% {
          transform: rotateY(180deg) scale(1.13);
        }
        100% {
          transform: rotateY(180deg) scale(1);
        }
      }
      .card.wrong .card-inner {
        animation: shake 0.35s ease;
      }
      @keyframes shake {
        0%,
        100% {
          transform: rotateY(180deg);
        }
        25% {
          transform: rotateY(180deg) translateX(-5px) rotate(-2deg);
        }
        75% {
          transform: rotateY(180deg) translateX(5px) rotate(2deg);
        }
      }

      /* ── grid ── */
      #grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: clamp(6px, 1.5vw, 12px);
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
      }
      .card {
        width: 100%;
        aspect-ratio: 1;
      }
      @media (max-width: 420px) {
        #grid {
          grid-template-columns: repeat(4, 1fr);
        }
      }

      /* ── buttons ── */
      .btn {
        font-family: "Baloo 2", sans-serif;
        font-weight: 800;
        font-size: 14px;
        border: none;
        cursor: pointer;
        border-radius: 12px;
        padding: 11px 22px;
        transition: all 0.2s;
        letter-spacing: 0.03em;
      }
      .btn-orange {
        background: linear-gradient(135deg, var(--c1), var(--c2));
        color: #fff;
        box-shadow: 0 4px 18px rgba(249, 115, 22, 0.4);
      }
      .btn-orange:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(249, 115, 22, 0.55);
      }
      .btn-orange:active {
        transform: scale(0.97);
      }
      .btn-ghost {
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: rgba(255, 255, 255, 0.7);
      }
      .btn-ghost:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
      }
      .btn-ghost:active {
        transform: scale(0.97);
      }

      /* ── progress ── */
      .prog-track {
        height: 6px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 999px;
        overflow: hidden;
      }
      .prog-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--c1), var(--c3));
        border-radius: 999px;
        transition: width 0.5s ease;
        box-shadow: 0 0 8px rgba(249, 115, 22, 0.5);
      }

      /* ── win overlay ── */
      .overlay {
        position: fixed;
        inset: 0;
        z-index: 100;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.35s;
        backdrop-filter: blur(6px);
      }
      .overlay.show {
        opacity: 1;
        pointer-events: all;
      }
      .win-box {
        background: linear-gradient(160deg, #1a1a30, #0f0f20);
        border: 1px solid rgba(249, 115, 22, 0.35);
        border-radius: 24px;
        padding: 36px 40px;
        text-align: center;
        max-width: 360px;
        width: 90%;
        transform: scale(0.85);
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow:
          0 24px 60px rgba(0, 0, 0, 0.6),
          0 0 40px rgba(249, 115, 22, 0.15);
      }
      .overlay.show .win-box {
        transform: scale(1);
      }
      .trophy-anim {
        font-size: 52px;
        display: block;
        margin-bottom: 10px;
        animation: tb 1s ease infinite alternate;
      }
      @keyframes tb {
        from {
          transform: translateY(0) rotate(-5deg);
        }
        to {
          transform: translateY(-12px) rotate(5deg);
        }
      }

      /* ── result card (submit) ── */
      .result-overlay {
        position: fixed;
        inset: 0;
        z-index: 90;
        background: rgba(0, 0, 0, 0.75);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
        backdrop-filter: blur(5px);
      }
      .result-overlay.show {
        opacity: 1;
        pointer-events: all;
      }
      .result-box {
        background: #13132a;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 0;
        max-width: 340px;
        width: 92%;
        transform: translateY(24px);
        transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        overflow: hidden;
        box-shadow: 0 28px 60px rgba(0, 0, 0, 0.7);
      }
      .result-overlay.show .result-box {
        transform: translateY(0);
      }
      .result-header {
        background: linear-gradient(135deg, var(--c1), #c2410c);
        padding: 22px 24px 20px;
        text-align: center;
        position: relative;
      }
      .result-header .icon {
        font-size: 40px;
        display: block;
        margin-bottom: 6px;
      }
      .result-header h2 {
        font-family: "Baloo 2", sans-serif;
        font-weight: 900;
        font-size: 22px;
        color: #fff;
        margin-bottom: 2px;
      }
      .result-header p {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.65);
        font-weight: 500;
      }
      .result-body {
        padding: 20px 24px 24px;
      }
      .res-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 18px;
      }
      .res-stat {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        padding: 14px 10px;
        text-align: center;
      }
      .res-stat .rs-lbl {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.08em;
        color: rgba(255, 255, 255, 0.35);
        text-transform: uppercase;
        margin-bottom: 4px;
      }
      .res-stat .rs-val {
        font-family: "Baloo 2", sans-serif;
        font-size: 26px;
        font-weight: 900;
        color: #fff;
        line-height: 1;
      }
      .res-stat .rs-unit {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.4);
        margin-top: 2px;
      }
      .rating-row {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(249, 115, 22, 0.08);
        border: 1px solid rgba(249, 115, 22, 0.2);
        border-radius: 14px;
        padding: 12px 16px;
        margin-bottom: 18px;
      }
      .rating-emoji {
        font-size: 28px;
        flex-shrink: 0;
      }
      .rating-text .rt-title {
        font-family: "Baloo 2", sans-serif;
        font-weight: 800;
        font-size: 15px;
        color: #fff;
      }
      .rating-text .rt-sub {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.45);
        margin-top: 1px;
      }
      .stars {
        display: flex;
        gap: 3px;
        margin-top: 4px;
      }
      .star {
        font-size: 14px;
        opacity: 0.25;
        transition: opacity 0.2s;
      }
      .star.lit {
        opacity: 1;
      }
      .res-actions {
        display: flex;
        gap: 10px;
      }

      /* ── game title ── */
      .game-title {
        font-family: "Baloo 2", sans-serif;
        font-weight: 900;
        font-size: clamp(26px, 6vw, 38px);
        background: linear-gradient(135deg, #fff 30%, var(--c2));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }

      /* ── toast ── */
      .toast {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(60px);
        background: #1e1e3a;
        border: 1px solid rgba(249, 115, 22, 0.4);
        color: #fff;
        border-radius: 12px;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 600;
        z-index: 200;
        transition:
          transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1),
          opacity 0.35s;
        opacity: 0;
        white-space: nowrap;
      }
      .toast.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
      }
    </style>
  </head>
  <body class="relative z-10 flex flex-col items-center py-6 px-3 min-h-screen">
    <!-- TITLE -->
    <div class="text-center mb-5 relative z-10">
      <h1 class="game-title">🧠 Number Memory</h1>
      <p
        style="
          color: rgba(255, 255, 255, 0.35);
          font-size: 12px;
          font-weight: 500;
          letter-spacing: 0.06em;
          text-transform: uppercase;
          margin-top: 3px;
        "
      >
        flip · match · win
      </p>
    </div>

    <!-- STATS -->
    <div
      style="
        display: flex;
        gap: 10px;
        margin-bottom: 14px;
        flex-wrap: wrap;
        justify-content: center;
      "
      class="relative z-10"
    >
      <div class="stat-pill">
        <div class="lbl">Moves</div>
        <div class="val" id="moves-val">0</div>
      </div>
      <div class="stat-pill">
        <div class="lbl">Pairs</div>
        <div class="val" id="pairs-val">0/20</div>
      </div>

      <div class="stat-pill">
        <div class="lbl">Time</div>
        <div class="val" id="timer-val">0s</div>
      </div>
    </div>

    <!-- PROGRESS -->
    <div
      style="width: 100%; max-width: 480px; margin-bottom: 14px"
      class="relative z-10"
    >
      <div class="prog-track">
        <div class="prog-fill" id="prog-fill" style="width: 0%"></div>
      </div>
      <div
        style="display: flex; justify-content: space-between; margin-top: 4px"
      >
        <span
          style="
            color: rgba(255, 255, 255, 0.3);
            font-size: 11px;
            font-weight: 600;
          "
          >Progress</span
        >
        <span
          style="
            color: rgba(249, 115, 22, 0.8);
            font-size: 11px;
            font-weight: 700;
          "
          id="prog-text"
          >0%</span
        >
      </div>
    </div>

    <!-- GRID -->
    <div
      id="grid"
      style="width: 100%; max-width: 480px; margin-bottom: 16px"
      class="relative z-10"
    ></div>

    <!-- ACTION BUTTONS -->
    <div
      style="display: flex; gap: 12px; flex-wrap: wrap; justify-content: center"
      class="relative z-10"
    >
      <button
        class="btn btn-orange"
        onclick="
          playSound('click');
          initGame();
        "
      >
        ↺ New Game
      </button>
      <button
        class="btn btn-ghost"
        onclick="
          playSound('submit');
          openResult();
        "
      >
        📤 Submit Score
      </button>
    </div>

    <!-- ══ WIN OVERLAY ══ -->
    <div class="overlay" id="win-overlay">
      <div class="win-box">
        <span class="trophy-anim">🏆</span>
        <h2
          style="
            font-family: &quot;Baloo 2&quot;, sans-serif;
            font-weight: 900;
            font-size: 26px;
            color: #fff;
            margin-bottom: 6px;
          "
        >
          You Won!
        </h2>
        <p
          style="
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            margin-bottom: 4px;
          "
          id="win-moves-txt"
        ></p>
        <p
          style="
            color: var(--c2);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 22px;
          "
          id="win-best-txt"
        ></p>
        <div
          style="
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
          "
        >
          <button
            class="btn btn-orange"
            onclick="
              playSound('click');
              closeWin();
              initGame();
            "
          >
            Play Again
          </button>
          <button
            class="btn btn-ghost"
            onclick="
              playSound('submit');
              closeWin();
              openResult();
            "
          >
            📤 View Result
          </button>
        </div>
      </div>
    </div>

    <!-- ══ RESULT CARD ══ -->
    <div class="result-overlay" id="result-overlay">
      <div class="result-box">
        <!-- colourful header -->
        <div class="result-header">
          <span class="icon" id="res-icon">📊</span>
          <h2 id="res-title">Your Result</h2>
          <p id="res-subtitle">Here's how you did this round</p>
        </div>
        <!-- stat grid -->
        <div class="result-body">
          <div class="res-grid">
            <div class="res-stat">
              <div class="rs-lbl">Moves</div>
              <div class="rs-val" id="rs-moves">0</div>
              <div class="rs-unit">total flips</div>
            </div>
            <div class="res-stat">
              <div class="rs-lbl">Time</div>
              <div class="rs-val" id="rs-time">0</div>
              <div class="rs-unit">seconds</div>
            </div>
            <div class="res-stat">
              <div class="rs-lbl">Pairs Found</div>
              <div class="rs-val" id="rs-pairs">0</div>
              <div class="rs-unit">out of 20</div>
            </div>
            <div class="res-stat">
              <div class="rs-lbl">Accuracy</div>
              <div class="rs-val" id="rs-acc">0%</div>
              <div class="rs-unit">match rate</div>
            </div>
          </div>
          <!-- rating row -->
          <div class="rating-row">
            <div class="rating-emoji" id="res-emoji">🙂</div>
            <div class="rating-text">
              <div class="rt-title" id="res-rating">Good effort!</div>
              <div class="rt-sub" id="res-rating-sub">Keep practising</div>
              <div class="stars" id="res-stars"></div>
            </div>
          </div>
          <!-- actions -->
          <div class="res-actions">
            <button
              class="btn btn-orange"
              style="flex: 1"
              onclick="
                playSound('click');
                closeResult();
                initGame();
              "
            >
              ↺ Play Again
            </button>
            <button
              class="btn btn-ghost"
              onclick="
                playSound('click');
                closeResult();
              "
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- TOAST -->
    <div class="toast" id="toast"></div>

    <script>
      const AC = new (window.AudioContext || window.webkitAudioContext)();
      function playSound(type) {
        if (AC.state === "suspended") AC.resume();
        const g = AC.createGain();
        g.connect(AC.destination);
        const cfg = {
          click: { t: "sine", f: 520, d: 0.08, v: 0.18 },
          flip: { t: "sine", f: 660, d: 0.12, v: 0.22 },
          match: { t: "triangle", f: 880, d: 0.22, v: 0.28 },
          wrong: { t: "sawtooth", f: 200, d: 0.18, v: 0.15 },
          win: { t: "sine", f: 1047, d: 0.6, v: 0.3 },
          tab: { t: "sine", f: 440, d: 0.07, v: 0.14 },
          submit: { t: "sine", f: 740, d: 0.18, v: 0.22 },
        };
        const c = cfg[type] || cfg.click;
        const o = AC.createOscillator();
        o.type = c.t;
        o.frequency.setValueAtTime(c.f, AC.currentTime);
        if (type === "win") {
          o.frequency.setValueAtTime(1047, AC.currentTime);
          o.frequency.setValueAtTime(1319, AC.currentTime + 0.15);
          o.frequency.setValueAtTime(1568, AC.currentTime + 0.32);
        }
        if (type === "match") {
          o.frequency.setValueAtTime(880, AC.currentTime);
          o.frequency.setValueAtTime(1047, AC.currentTime + 0.1);
        }
        g.gain.setValueAtTime(c.v, AC.currentTime);
        g.gain.exponentialRampToValueAtTime(0.001, AC.currentTime + c.d);
        o.connect(g);
        o.start(AC.currentTime);
        o.stop(AC.currentTime + c.d + 0.02);
      }

      let flipped = [],
        moves = 0,
        matched = 0,
        lock = false,
        best = null,
        timerInt = null,
        seconds = 0,
        gameStarted = false;

      function shuffle(arr) {
        for (let i = arr.length - 1; i > 0; i--) {
          const j = Math.floor(Math.random() * (i + 1));
          [arr[i], arr[j]] = [arr[j], arr[i]];
        }
        return arr;
      }

      function initGame() {
        clearInterval(timerInt);
        seconds = 0;
        gameStarted = false;
        moves = 0;
        matched = 0;
        lock = false;
        flipped = [];
        document.getElementById("moves-val").textContent = "0";
        document.getElementById("pairs-val").textContent = "0/20";
        document.getElementById("timer-val").textContent = "0s";
        document.getElementById("prog-fill").style.width = "0%";
        document.getElementById("prog-text").textContent = "0%";
        document.getElementById("win-overlay").classList.remove("show");
        const grid = document.getElementById("grid");
        grid.innerHTML = "";
        const nums = shuffle([
          1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20,
        ]);
        nums.forEach((n) => {
          const c = document.createElement("div");
          c.className = "card";
          c.dataset.val = n;
          c.innerHTML = `<div class="card-inner"><div class="card-face card-back"><div class="q-mark">?</div></div><div class="card-face card-front"><span class="num">${n}</span></div></div>`;
          c.addEventListener("click", () => onCard(c));
          grid.appendChild(c);
        });
      }

      function startTimer() {
        timerInt = setInterval(() => {
          seconds++;
          document.getElementById("timer-val").textContent = seconds + "s";
        }, 1000);
      }

      function onCard(card) {
        if (
          lock ||
          card.classList.contains("flipped") ||
          card.classList.contains("matched")
        )
          return;
        if (!gameStarted) {
          gameStarted = true;
          startTimer();
        }
        playSound("flip");
        card.classList.add("flipped");
        flipped.push(card);
        if (flipped.length === 2) {
          lock = true;
          moves++;
          document.getElementById("moves-val").textContent = moves;
          const [a, b] = flipped;
          if (a.dataset.val === b.dataset.val) {
            setTimeout(() => {
              playSound("match");
              a.classList.add("matched");
              b.classList.add("matched");
              matched++;
              const pct = Math.round((matched / 20) * 100);
              document.getElementById("pairs-val").textContent =
                matched + "/20";
              document.getElementById("prog-fill").style.width = pct + "%";
              document.getElementById("prog-text").textContent = pct + "%";
              flipped = [];
              lock = false;
              if (matched === 20) setTimeout(showWin, 400);
            }, 250);
          } else {
            setTimeout(() => {
              playSound("wrong");
              a.classList.add("wrong");
              b.classList.add("wrong");
              setTimeout(() => {
                a.classList.remove("flipped", "wrong");
                b.classList.remove("flipped", "wrong");
                flipped = [];
                lock = false;
              }, 380);
            }, 700);
          }
        }
      }

      function showWin() {
        clearInterval(timerInt);
        playSound("win");
        if (best === null || moves < best) best = moves;
        document.getElementById("best-val").textContent = best;
        document.getElementById("win-moves-txt").textContent =
          "Completed in " + moves + " moves · " + seconds + "s";
        document.getElementById("win-best-txt").textContent =
          moves === best ? "🌟 New best score!" : "Best: " + best + " moves";
        document.getElementById("win-overlay").classList.add("show");
      }
      function closeWin() {
        document.getElementById("win-overlay").classList.remove("show");
      }

      /* ── result card logic ── */
      function getRating() {
        const acc = moves > 0 ? Math.round((matched / moves) * 100) : 0;
        const done = matched === 20;
        // score: lower moves + lower time = better
        let stars = 1,
          emoji = "😅",
          title = "Keep going!",
          sub = "Practice makes perfect";
        if (done) {
          if (moves <= 24) {
            stars = 5;
            emoji = "🤩";
            title = "Perfect Memory!";
            sub = "Flawless round — you nailed it!";
          } else if (moves <= 32) {
            stars = 4;
            emoji = "😎";
            title = "Excellent!";
            sub = "Really sharp memory skills!";
          } else if (moves <= 44) {
            stars = 3;
            emoji = "😊";
            title = "Great Job!";
            sub = "Solid performance overall!";
          } else {
            stars = 2;
            emoji = "🙂";
            title = "You Did It!";
            sub = "All pairs found — well done!";
          }
        } else {
          if (matched >= 14) {
            stars = 3;
            emoji = "🔥";
            title = "Almost There!";
            sub = matched + "/20 pairs found so far";
          } else if (matched >= 8) {
            stars = 2;
            emoji = "💪";
            title = "Getting Warmer!";
            sub = matched + "/20 pairs matched";
          } else {
            stars = 1;
            emoji = "🌱";
            title = "Just Started!";
            sub = "Keep flipping to find more pairs";
          }
        }
        return { stars, emoji, title, sub, acc };
      }

      function openResult() {
        const { stars, emoji, title, sub, acc } = getRating();
        const done = matched === 20;
        document.getElementById("res-icon").textContent = done ? "🏆" : "📊";
        document.getElementById("res-title").textContent = done
          ? "Game Complete!"
          : "Mid-Game Snapshot";
        document.getElementById("res-subtitle").textContent = done
          ? "Here's your final score"
          : "Submitted at " + matched + "/20 pairs";
        document.getElementById("rs-moves").textContent = moves;
        document.getElementById("rs-time").textContent = seconds;
        document.getElementById("rs-pairs").textContent = matched;
        document.getElementById("rs-acc").textContent = acc + "%";
        document.getElementById("res-emoji").textContent = emoji;
        document.getElementById("res-rating").textContent = title;
        document.getElementById("res-rating-sub").textContent = sub;
        // stars
        const starsEl = document.getElementById("res-stars");
        starsEl.innerHTML = "";
        for (let i = 1; i <= 5; i++) {
          const s = document.createElement("span");
          s.className = "star" + (i <= stars ? " lit" : "");
          s.textContent = "★";
          s.style.color = i <= stars ? "#fbbf24" : "rgba(255,255,255,.2)";
          starsEl.appendChild(s);
        }
        document.getElementById("result-overlay").classList.add("show");
        showToast("📊 Result submitted!");
      }
      function closeResult() {
        document.getElementById("result-overlay").classList.remove("show");
      }

      function showToast(msg) {
        const t = document.getElementById("toast");
        t.textContent = msg;
        t.classList.add("show");
        setTimeout(() => t.classList.remove("show"), 2500);
      }

      document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
          closeResult();
          closeWin();
        }
      });

      initGame();
    </script>
  </body>
</html>
