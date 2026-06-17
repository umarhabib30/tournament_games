<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>jigsaw puzzle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <style>
      :root {
        --navy-950: #03060f;
        --accent: #00d4ff;
        --accent2: #7b2fff;
        --gold: #ffd166;
      }
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }
      body {
        font-family: "Rajdhani", sans-serif;
        background:
          radial-gradient(ellipse at 20% 10%, #0d1f3c 0%, var(--navy-950) 60%),
          radial-gradient(ellipse at 80% 90%, #130829 0%, transparent 60%);
        background-color: var(--navy-950);
        min-height: 100vh;
        color: #e2e8f0;
        overflow-x: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 24px 16px;
      }
      img {
        max-width: 100%;
        display: block;
      }
      h1 {
        font-family: "Orbitron", monospace;
      }
      .glow-cyan {
        text-shadow:
          0 0 12px #00d4ff88,
          0 0 30px #00d4ff44;
      }
      .glow-box {
        box-shadow:
          0 0 0 1px #00d4ff33,
          0 0 20px #00d4ff18,
          inset 0 1px 0 #ffffff18;
      }
      .glow-box-lg {
        box-shadow:
          0 0 0 1px #00d4ff55,
          0 0 40px #00d4ff22,
          0 8px 32px #00000080;
      }

      /* ── Grid ── */
      #puzzle-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 3px;
        background: rgba(0, 212, 255, 0.12);
        border-radius: 12px;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
        aspect-ratio: 1 / 1;
      }

      .page-shell {
        width: min(100%, 560px);
      }

      .page-header {
        width: 100%;
        margin-bottom: 20px;
      }

      .header-top {
        display: flex;
        align-items: stretch;
        justify-content: space-between;
        gap: 16px;
        padding: 14px;
        border-radius: 16px;
        background: linear-gradient(
          135deg,
          rgba(8, 18, 39, 0.92),
          rgba(10, 16, 34, 0.78)
        );
        border: 1px solid rgba(0, 212, 255, 0.16);
        box-shadow:
          0 0 0 1px rgba(255, 255, 255, 0.03) inset,
          0 18px 42px rgba(0, 0, 0, 0.28);
      }

      .header-copy {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
      }

      .stats-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        width: 100%;
        margin-top: 14px;
      }

      .game-area {
        width: 100%;
        display: block;
      }

      .board-wrap {
        min-width: 0;
      }

      .target-panel {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 150px;
        padding-left: 16px;
        border-left: 1px solid rgba(0, 212, 255, 0.22);
      }

      .target-thumb {
        width: 130px;
        height: 130px;
        border-radius: 12px;
        overflow: hidden;
      }

      /* ── Pieces use background-image ── */
      .puzzle-piece {
        cursor: grab;
        transition:
          transform 0.18s cubic-bezier(0.34, 1.56, 0.64, 1),
          box-shadow 0.18s ease;
        overflow: hidden;
        user-select: none;
        background-repeat: no-repeat;
      }
      .puzzle-piece:hover {
        transform: scale(1.05);
        z-index: 10;
        box-shadow:
          0 0 0 2px var(--accent),
          0 8px 20px #00000060;
      }
      .puzzle-piece.dragging {
        opacity: 0.45;
        transform: scale(0.88);
        cursor: grabbing;
      }
      .puzzle-piece.drag-over {
        transform: scale(1.07);
        box-shadow:
          0 0 0 3px var(--gold),
          0 0 24px #ffd16660;
      }

      /* ── Stat badge ── */
      .stat-badge {
        background: linear-gradient(
          135deg,
          rgba(0, 212, 255, 0.12),
          rgba(123, 47, 255, 0.08)
        );
        border: 1px solid rgba(0, 212, 255, 0.2);
        border-radius: 10px;
        padding: 8px 20px;
        text-align: center;
        min-width: 100px;
      }

      /* ── Modal ── */
      #result-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 100;
        align-items: center;
        justify-content: center;
        background: rgba(3, 6, 15, 0.88);
        backdrop-filter: blur(8px);
      }
      #result-modal.open {
        display: flex;
      }
      .modal-card {
        background: linear-gradient(145deg, #0b1730, #060d1f);
        border: 1px solid rgba(0, 212, 255, 0.25);
        box-shadow:
          0 0 60px rgba(0, 212, 255, 0.15),
          0 32px 80px rgba(0, 0, 0, 0.7);
        border-radius: 24px;
        padding: 40px 32px;
        text-align: center;
        max-width: 380px;
        width: 92vw;
        animation: modalPop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
      }
      @keyframes modalPop {
        from {
          transform: scale(0.6) translateY(40px);
          opacity: 0;
        }
        to {
          transform: scale(1) translateY(0);
          opacity: 1;
        }
      }
      .star {
        font-size: 2.2rem;
        display: inline-block;
        transition: transform 0.3s;
      }
      .star.lit {
        color: var(--gold);
        filter: drop-shadow(0 0 6px #ffd166aa);
        transform: scale(1.2);
      }
      .star.dim {
        color: #2a3550;
      }

      .btn-primary {
        background: linear-gradient(135deg, var(--accent), var(--accent2));
        color: #fff;
        font-family: "Orbitron", monospace;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        padding: 12px 32px;
        border-radius: 50px;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(0, 212, 255, 0.35);
        transition:
          transform 0.15s,
          box-shadow 0.15s;
      }
      .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(0, 212, 255, 0.5);
      }

      @keyframes countUp {
        from {
          opacity: 0;
          transform: translateY(6px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
      .count-anim {
        animation: countUp 0.3s ease;
      }

      .particle {
        position: fixed;
        border-radius: 50%;
        pointer-events: none;
        animation: burst 1s ease-out forwards;
      }
      @keyframes burst {
        0% {
          transform: translate(0, 0) scale(1);
          opacity: 1;
        }
        100% {
          transform: translate(var(--tx), var(--ty)) scale(0);
          opacity: 0;
        }
      }
      body::after {
        content: "";
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: 999;
        background: repeating-linear-gradient(
          0deg,
          transparent,
          transparent 2px,
          rgba(0, 0, 0, 0.03) 2px,
          rgba(0, 0, 0, 0.03) 4px
        );
      }

      @media (max-width: 640px) {
        body {
          padding: 18px 12px 24px;
        }

        .header-top {
          gap: 12px;
          padding: 12px;
        }

        .stat-badge {
          min-width: 0;
          padding: 8px 10px;
        }

        .modal-card {
          width: min(92vw, 380px);
          padding: 28px 18px;
          border-radius: 20px;
        }
      }

      @media (max-width: 520px) {
        .header-top {
          align-items: stretch;
        }

        .header-copy {
          text-align: left;
        }

        .target-panel {
          width: 132px;
          padding-left: 12px;
        }

        .target-thumb {
          width: 112px;
          height: 112px;
        }
      }

      @media (max-width: 420px) {
        .stats-row {
          gap: 8px;
        }

        .header-top {
          gap: 10px;
          padding: 10px;
        }

        .target-panel {
          width: 120px;
          padding-left: 10px;
        }

        .target-thumb {
          width: 100px;
          height: 100px;
        }
      }
    </style>
  </head>
  <body>
    <div class="page-shell">
      <!-- HEADER -->
      <header class="page-header">
        <div class="header-top">
          <div class="header-copy">
            <h1
              class="glow-cyan"
              style="
                font-size: clamp(1.4rem, 5vw, 2rem);
                font-weight: 900;
                letter-spacing: 0.15em;
                text-transform: uppercase;
                color: var(--accent);
              "
            >
              jigsaw puzzle
            </h1>
            <p
              style="
                font-size: 0.6rem;
                letter-spacing: 0.35em;
                text-transform: uppercase;
                opacity: 0.35;
                margin-top: 4px;
              "
            >
              Puzzle Challenge
            </p>
            <div class="stats-row">
              <div class="stat-badge">
                <div
                  style="
                    font-family: &quot;Orbitron&quot;, monospace;
                    font-size: 0.5rem;
                    letter-spacing: 0.2em;
                    text-transform: uppercase;
                    opacity: 0.5;
                    margin-bottom: 4px;
                  "
                >
                  Time
                </div>
                <div
                  id="timer"
                  style="
                    font-family: &quot;Orbitron&quot;, monospace;
                    font-weight: 700;
                    font-size: 1.4rem;
                    color: var(--accent);
                  "
                >
                  00:00
                </div>
              </div>
              <div class="stat-badge">
                <div
                  style="
                    font-family: &quot;Orbitron&quot;, monospace;
                    font-size: 0.5rem;
                    letter-spacing: 0.2em;
                    text-transform: uppercase;
                    opacity: 0.5;
                    margin-bottom: 4px;
                  "
                >
                  Correct
                </div>
                <div
                  id="moves"
                  style="
                    font-family: &quot;Orbitron&quot;, monospace;
                    font-weight: 700;
                    font-size: 1.4rem;
                    color: var(--gold);
                  "
                >
                  0/9
                </div>
              </div>
            </div>
          </div>
          <div class="target-panel">
            <p
              style="
                font-family: &quot;Orbitron&quot;, monospace;
                font-size: 0.45rem;
                letter-spacing: 0.2em;
                text-transform: uppercase;
                opacity: 0.45;
              "
            >
              Target
            </p>
            <div class="glow-box target-thumb">
              <img
                id="target-img"
                src=""
                alt="Target"
                style="
                  width: 100%;
                  height: 100%;
                  object-fit: contain;
                  background: rgba(0, 0, 0, 0.18);
                  display: block;
                "
              />
            </div>
          </div>
        </div>
      </header>

      <!-- GAME AREA -->
      <div class="game-area">
        <div class="board-wrap">
          <div id="puzzle-grid" class="glow-box-lg"></div>
        </div>
        <button
          class="btn-primary"
          id="submit-btn"
          style="margin-top: 16px; width: 100%"
        >
          SUBMIT
        </button>
      </div>
    </div>

    <!-- RESULT MODAL -->
    <div id="result-modal">
      <div class="modal-card">
        <div style="font-size: 3rem; margin-bottom: 12px">🎉</div>
        <h2
          id="result-title"
          style="
            font-family: &quot;Orbitron&quot;, monospace;
            font-weight: 900;
            font-size: 1.5rem;
            color: var(--accent);
            letter-spacing: 0.1em;
            margin-bottom: 4px;
          "
        >
          Puzzle Solved!
        </h2>
        <p
          id="result-subtitle"
          style="
            font-size: 0.7rem;
            opacity: 0.4;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            margin-bottom: 24px;
          "
        >
          Well played, champion
        </p>
        <div
          style="
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 24px;
          "
        >
          <span class="star dim" id="star1">★</span>
          <span class="star dim" id="star2">★</span>
          <span class="star dim" id="star3">★</span>
        </div>
        <div
          style="
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 32px;
          "
        >
          <div class="stat-badge">
            <div
              style="
                font-family: &quot;Orbitron&quot;, monospace;
                font-size: 0.45rem;
                opacity: 0.5;
                margin-bottom: 4px;
              "
            >
              TIME
            </div>
            <div
              id="result-time"
              style="
                font-family: &quot;Orbitron&quot;, monospace;
                font-weight: 700;
                font-size: 1.2rem;
                color: var(--accent);
              "
            >
              --
            </div>
          </div>
          <div class="stat-badge">
            <div
              style="
                font-family: &quot;Orbitron&quot;, monospace;
                font-size: 0.45rem;
                opacity: 0.5;
                margin-bottom: 4px;
              "
            >
              CORRECT
            </div>
            <div
              id="result-moves"
              style="
                font-family: &quot;Orbitron&quot;, monospace;
                font-weight: 700;
                font-size: 1.2rem;
                color: var(--gold);
              "
            >
              --
            </div>
          </div>
        </div>
        <button class="btn-primary" id="play-again-btn">▶ PLAY AGAIN</button>
      </div>
    </div>

    <script>
      // ── Image pool ──────────────────────────────────────────
      const LOCAL_IMAGE_POOL = [
        "{{ asset('games/img1.jpg') }}",
        "{{ asset('games/img2.jpg') }}",
        "{{ asset('games/img3.jpg') }}",
        "{{ asset('games/img4.jpg') }}",
        "{{ asset('games/img5.jpg') }}",
      ];

      const FALLBACK_IMAGE_POOL = [
        "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&q=80",
        "https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=600&q=80",
        "https://images.unsplash.com/photo-1519681393784-d120267933ba?w=600&q=80",
        "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=600&q=80",
        "https://images.unsplash.com/photo-1531366936337-7c912a4589a7?w=600&q=80",
        "https://images.unsplash.com/photo-1499678329028-101435549a4e?w=600&q=80",
      ];

      const IMAGE_POOL = LOCAL_IMAGE_POOL.length
        ? LOCAL_IMAGE_POOL
        : FALLBACK_IMAGE_POOL;

      // ── State ────────────────────────────────────────────────
      const TOTAL_TILES = 9;
      let positions = [];
      let correctCount = 0;
      let timerSec = 0;
      let timerHandle = null;
      let dragSrcSlot = null;
      let currentImage = "";

      // ── DOM ──────────────────────────────────────────────────
      const grid = document.getElementById("puzzle-grid");
      const timerEl = document.getElementById("timer");
      const movesEl = document.getElementById("moves");
      const targetImg = document.getElementById("target-img");
      const modal = document.getElementById("result-modal");
      const resultTime = document.getElementById("result-time");
      const resultMoves = document.getElementById("result-moves");
      const resultTitle = document.getElementById("result-title");
      const resultSubtitle = document.getElementById("result-subtitle");

      function getCorrectCount() {
        return positions.filter((value, index) => value === index).length;
      }

      function updateCorrectDisplay() {
        correctCount = getCorrectCount();
        movesEl.textContent = `${correctCount}/${TOTAL_TILES}`;
        movesEl.classList.remove("count-anim");
        void movesEl.offsetWidth;
        movesEl.classList.add("count-anim");
      }

      // ── Audio ────────────────────────────────────────────────
      let audioCtx;
      function ensureAudio() {
        if (!audioCtx)
          audioCtx = new (window.AudioContext || window.webkitAudioContext)();
      }
      function playTone(freq, type = "sine", dur = 0.12, vol = 0.25) {
        try {
          ensureAudio();
          const o = audioCtx.createOscillator(),
            g = audioCtx.createGain();
          o.type = type;
          o.frequency.setValueAtTime(freq, audioCtx.currentTime);
          o.frequency.exponentialRampToValueAtTime(
            freq * 0.7,
            audioCtx.currentTime + dur,
          );
          g.gain.setValueAtTime(vol, audioCtx.currentTime);
          g.gain.exponentialRampToValueAtTime(
            0.001,
            audioCtx.currentTime + dur,
          );
          o.connect(g);
          g.connect(audioCtx.destination);
          o.start();
          o.stop(audioCtx.currentTime + dur);
        } catch (e) {}
      }
      const soundPick = () => playTone(660, "square", 0.08, 0.15);
      const soundSwap = () => playTone(420, "sine", 0.14, 0.22);
      const soundSuccess = () =>
        [523, 659, 784, 1047].forEach((f, i) =>
          setTimeout(() => playTone(f, "sine", 0.35, 0.3), i * 80),
        );

      // ── Timer ────────────────────────────────────────────────
      function startTimer() {
        clearInterval(timerHandle);
        timerSec = 0;
        timerEl.textContent = "00:00";
        timerHandle = setInterval(() => {
          timerSec++;
          timerEl.textContent = `${String(Math.floor(timerSec / 60)).padStart(2, "0")}:${String(timerSec % 60).padStart(2, "0")}`;
        }, 1000);
      }
      function stopTimer() {
        clearInterval(timerHandle);
      }
      function formatTime(s) {
        return `${String(Math.floor(s / 60)).padStart(2, "0")}:${String(s % 60).padStart(2, "0")}`;
      }

      // ── Init ─────────────────────────────────────────────────
      function initGame() {
        currentImage =
          IMAGE_POOL[Math.floor(Math.random() * IMAGE_POOL.length)];
        targetImg.src = currentImage;
        positions = Array.from({ length: 9 }, (_, i) => i);
        do {
          for (let i = 8; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [positions[i], positions[j]] = [positions[j], positions[i]];
          }
        } while (isSolved());
        updateCorrectDisplay();
        modal.classList.remove("open");
        // Wait for image to load so background renders correctly
        const img = new Image();
        img.crossOrigin = "anonymous";
        img.onload = renderGrid;
        img.onerror = renderGrid;
        img.src = currentImage;
        startTimer();
      }

      // ── Render ───────────────────────────────────────────────
      // KEY FIX: use background-size + background-position
      // For a 3x3 grid, each cell shows 1/3 of the image.
      // background-size: 300% 300% means the full image spans 3x the cell size.
      // background-position uses percentages:
      //   col 0 → 0%, col 1 → 50%, col 2 → 100%
      //   row 0 → 0%, row 1 → 50%, row 2 → 100%
      function renderGrid() {
        grid.innerHTML = "";
        for (let slot = 0; slot < 9; slot++) {
          const correctIdx = positions[slot];
          const row = Math.floor(correctIdx / 3);
          const col = correctIdx % 3;
          const bpx = ["0%", "50%", "100%"][col];
          const bpy = ["0%", "50%", "100%"][row];

          const cell = document.createElement("div");
          cell.className = "puzzle-piece";
          cell.dataset.slot = slot;
          cell.draggable = true;
          cell.style.backgroundImage = `url("${currentImage}")`;
          cell.style.backgroundSize = "300% 300%";
          cell.style.backgroundPosition = `${bpx} ${bpy}`;

          cell.addEventListener("dragstart", onDragStart);
          cell.addEventListener("dragover", onDragOver);
          cell.addEventListener("dragleave", onDragLeave);
          cell.addEventListener("drop", onDrop);
          cell.addEventListener("dragend", onDragEnd);
          cell.addEventListener("touchstart", onTouchStart, { passive: false });
          cell.addEventListener("touchmove", onTouchMove, { passive: false });
          cell.addEventListener("touchend", onTouchEnd);

          grid.appendChild(cell);
        }
      }

      // ── Desktop drag ─────────────────────────────────────────
      function onDragStart(e) {
        dragSrcSlot = parseInt(this.dataset.slot);
        this.classList.add("dragging");
        e.dataTransfer.effectAllowed = "move";
        e.dataTransfer.setData("text/plain", dragSrcSlot);
        soundPick();
      }
      function onDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = "move";
        this.classList.add("drag-over");
      }
      function onDragLeave() {
        this.classList.remove("drag-over");
      }
      function onDrop(e) {
        e.preventDefault();
        this.classList.remove("drag-over");
        const t = parseInt(this.dataset.slot);
        if (dragSrcSlot === null || dragSrcSlot === t) return;
        swapPieces(dragSrcSlot, t);
      }
      function onDragEnd() {
        this.classList.remove("dragging");
        dragSrcSlot = null;
        document
          .querySelectorAll(".puzzle-piece")
          .forEach((p) => p.classList.remove("drag-over"));
      }

      // ── Touch drag ───────────────────────────────────────────
      let touchSrcSlot = null,
        touchGhost = null;
      function onTouchStart(e) {
        e.preventDefault();
        touchSrcSlot = parseInt(this.dataset.slot);
        soundPick();
        this.classList.add("dragging");
        const r = this.getBoundingClientRect();
        touchGhost = document.createElement("div");
        touchGhost.style.cssText = `position:fixed;width:${r.width}px;height:${r.height}px;
        background-image:url("${currentImage}");background-size:300% 300%;
        background-position:${this.style.backgroundPosition};
        opacity:0.8;pointer-events:none;z-index:200;
        left:${r.left}px;top:${r.top}px;
        border-radius:6px;border:2px solid var(--gold);box-shadow:0 0 20px #ffd16660;`;
        document.body.appendChild(touchGhost);
      }
      function onTouchMove(e) {
        e.preventDefault();
        if (!touchGhost) return;
        const t = e.touches[0],
          w = parseFloat(touchGhost.style.width),
          h = parseFloat(touchGhost.style.height);
        touchGhost.style.left = `${t.clientX - w / 2}px`;
        touchGhost.style.top = `${t.clientY - h / 2}px`;
        document
          .querySelectorAll(".puzzle-piece")
          .forEach((p) => p.classList.remove("drag-over"));
        const el = document.elementFromPoint(t.clientX, t.clientY);
        const tgt = el && el.closest(".puzzle-piece");
        if (tgt) tgt.classList.add("drag-over");
      }
      function onTouchEnd(e) {
        if (!touchGhost) return;
        const t = e.changedTouches[0];
        const el = document.elementFromPoint(t.clientX, t.clientY);
        const tgt = el && el.closest(".puzzle-piece");
        document.querySelectorAll(".puzzle-piece").forEach((p) => {
          p.classList.remove("dragging");
          p.classList.remove("drag-over");
        });
        touchGhost.remove();
        touchGhost = null;
        if (tgt) {
          const ts = parseInt(tgt.dataset.slot);
          if (touchSrcSlot !== null && touchSrcSlot !== ts)
            swapPieces(touchSrcSlot, ts);
        }
        touchSrcSlot = null;
      }

      // ── Swap ─────────────────────────────────────────────────
      function swapPieces(a, b) {
        [positions[a], positions[b]] = [positions[b], positions[a]];
        updateCorrectDisplay();
        soundSwap();
        renderGrid();
        if (isSolved()) {
          stopTimer();
          setTimeout(showResult, 400);
        }
      }

      // ── Solve check ──────────────────────────────────────────
      function isSolved() {
        return positions.every((v, i) => v === i);
      }

      // ── Result ───────────────────────────────────────────────
      function showResult(isManualSubmit = false) {
        soundSuccess();
        spawnParticles();
        const solved = correctCount === TOTAL_TILES;
        resultTime.textContent = formatTime(timerSec);
        resultMoves.textContent = `${correctCount}/${TOTAL_TILES}`;
        resultTitle.textContent = solved
          ? "Puzzle Solved!"
          : isManualSubmit
            ? "Result Submitted"
            : "Puzzle Result";
        resultSubtitle.textContent = solved
          ? "Well played, champion"
          : `${correctCount} of ${TOTAL_TILES} pieces are correct`;
        const stars = correctCount === TOTAL_TILES ? 3 : correctCount >= 6 ? 2 : 1;
        ["star1", "star2", "star3"].forEach(
          (id) => (document.getElementById(id).className = "star dim"),
        );
        for (let i = 0; i < stars; i++)
          setTimeout(
            () =>
              (document.getElementById(`star${i + 1}`).className = "star lit"),
            i * 220,
          );
        modal.classList.add("open");
      }

      // ── Particles ────────────────────────────────────────────
      function spawnParticles() {
        const colors = [
          "#00d4ff",
          "#ffd166",
          "#7b2fff",
          "#06d6a0",
          "#ff6b6b",
          "#fff",
        ];
        for (let i = 0; i < 60; i++) {
          const p = document.createElement("div");
          p.className = "particle";
          const angle = Math.random() * 2 * Math.PI,
            dist = 120 + Math.random() * 200;
          p.style.setProperty("--tx", `${Math.cos(angle) * dist}px`);
          p.style.setProperty("--ty", `${Math.sin(angle) * dist}px`);
          p.style.left = `${Math.random() * 100}vw`;
          p.style.top = `${20 + Math.random() * 60}vh`;
          p.style.background =
            colors[Math.floor(Math.random() * colors.length)];
          const sz = `${4 + Math.random() * 8}px`;
          p.style.width = sz;
          p.style.height = sz;
          p.style.animationDuration = `${0.6 + Math.random() * 0.8}s`;
          document.body.appendChild(p);
          p.addEventListener("animationend", () => p.remove());
        }
      }

      // ── Play again ───────────────────────────────────────────
      document
        .getElementById("play-again-btn")
        .addEventListener("click", () => {
          ensureAudio();
          initGame();
        });
      document.getElementById("submit-btn").addEventListener("click", () => {
        stopTimer();
        showResult(true);
      });

      // ── Start ────────────────────────────────────────────────
      initGame();
    </script>
  </body>
</html>
