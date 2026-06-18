<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Jigsaw Puzzle - Hard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
        width: 50%;
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

      .target-panel {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 50%;
        padding-left: 16px;
        border-left: 1px solid rgba(0, 212, 255, 0.22);
      }

      .target-thumb {
        width: 100%;
        max-width: 180px;
        aspect-ratio: 1 / 1;
        border-radius: 12px;
        overflow: hidden;
      }

      .game-area {
        width: 100%;
      }

      #puzzle-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 3px;
        background: rgba(0, 212, 255, 0.12);
        border-radius: 12px;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
        aspect-ratio: 1 / 1;
      }

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
        transform: scale(1.04);
        z-index: 10;
        box-shadow:
          0 0 0 2px var(--accent),
          0 8px 20px #00000060;
      }

      .puzzle-piece.dragging {
        opacity: 0.45;
        transform: scale(0.9);
        cursor: grabbing;
      }

      .puzzle-piece.drag-over {
        transform: scale(1.05);
        box-shadow:
          0 0 0 3px var(--gold),
          0 0 24px #ffd16660;
      }

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
        .header-copy {
          text-align: left;
        }

        .target-panel {
          width: 50%;
          padding-left: 12px;
        }

        .target-thumb {
          width: 100%;
          max-width: 140px;
          height: auto;
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
          width: 50%;
          padding-left: 10px;
        }

        .target-thumb {
          width: 100%;
          max-width: 120px;
          height: auto;
        }
      }
    </style>
  </head>
  <body>
    <div class="page-shell">
      <header class="page-header">
        <div class="header-top">
          <div class="header-copy">
            <h1
              class="glow-cyan"
              style="
                font-size: clamp(1.2rem, 5vw, 1.9rem);
                font-weight: 900;
                letter-spacing: 0.12em;
                text-transform: uppercase;
                color: var(--accent);
              "
            >
              Hard Puzzle
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
              5 x 5 Challenge
            </p>
            <div class="stats-row">
              <div class="stat-badge">
                <div
                  style="
                    font-family: 'Orbitron', monospace;
                    font-size: 0.5rem;
                    letter-spacing: 0.2em;
                    text-transform: uppercase;
                    opacity: 0.5;
                    margin-bottom: 4px;
                  "
                >
                  Time Remaining
                </div>
                <div
                  id="timer"
                  style="
                    font-family: 'Orbitron', monospace;
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
                    font-family: 'Orbitron', monospace;
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
                  id="correct"
                  style="
                    font-family: 'Orbitron', monospace;
                    font-weight: 700;
                    font-size: 1.4rem;
                    color: var(--gold);
                  "
                >
                  0/25
                </div>
              </div>
            </div>
          </div>
          <div class="target-panel">
            <p
              style="
                font-family: 'Orbitron', monospace;
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
                  object-fit: cover;
                  background: rgba(0, 0, 0, 0.18);
                  display: block;
                "
              />
            </div>
          </div>
        </div>
      </header>

      <main class="game-area">
        <div id="puzzle-grid" class="glow-box-lg"></div>
        <button
          class="btn-primary"
          id="submit-btn"
          style="margin-top: 16px; width: 100%"
        >
          SUBMIT RESULT
        </button>
      </main>
    </div>

    <!-- TOURNAMENT SCORE SUBMIT FORM -->
    <form id="gameForm" action="{{ url('round/submit-score') }}" method="POST">
        @csrf
        <input type="hidden" name="tournament_id" value="{{ $tournament->id }}" />
        <input type="hidden" name="game_id" value="{{ $game->id }}" />
        <input type="hidden" name="round_id" value="{{ $round->id }}" />
        <input type="hidden" name="score" value="0" id="scoreInput" />
        <input type="hidden" name="time_taken" value="0" id="timeInput" />
    </form>

    <div id="result-modal">
      <div class="modal-card">
        <div style="font-size: 3rem; margin-bottom: 12px">🎉</div>
        <h2
          id="result-title"
          style="
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 1.5rem;
            color: var(--accent);
            letter-spacing: 0.1em;
            margin-bottom: 4px;
          "
        >
          Hard Cleared
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
          25 piece puzzle solved
        </p>
        <div
          style="
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 24px;
          "
        >
          <span class="star dim" id="star1">&#9733;</span>
          <span class="star dim" id="star2">&#9733;</span>
          <span class="star dim" id="star3">&#9733;</span>
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
                font-family: 'Orbitron', monospace;
                font-size: 0.45rem;
                opacity: 0.5;
                margin-bottom: 4px;
              "
            >
              TIME TAKEN
            </div>
            <div
              id="result-time"
              style="
                font-family: 'Orbitron', monospace;
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
                font-family: 'Orbitron', monospace;
                font-size: 0.45rem;
                opacity: 0.5;
                margin-bottom: 4px;
              "
            >
              CORRECT
            </div>
            <div
              id="result-correct"
              style="
                font-family: 'Orbitron', monospace;
                font-weight: 700;
                font-size: 1.2rem;
                color: var(--gold);
              "
            >
              --
            </div>
          </div>
        </div>
        <a id="continueBtn" href="{{ url('play-tournament', $tournament->id) }}" class="btn-primary" style="display: inline-block; text-decoration: none; text-align: center;">CONTINUE</a>
      </div>
    </div>

    <script>
      const GRID_SIZE = 5;
      const TOTAL_TILES = GRID_SIZE * GRID_SIZE;
      const LOCAL_IMAGE_POOL = [
        "{{ asset('assets/images/img1.jpg') }}",
        "{{ asset('assets/images/img2.jpg') }}",
        "{{ asset('assets/images/img3.jpg') }}",
        "{{ asset('assets/images/img4.jpg') }}",
        "{{ asset('assets/images/img5.jpg') }}",
      ];

      const FALLBACK_IMAGE_POOL = [
        "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=700&q=80",
        "https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=700&q=80",
        "https://images.unsplash.com/photo-1519681393784-d120267933ba?w=700&q=80",
        "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=700&q=80",
      ];

      const IMAGE_POOL = LOCAL_IMAGE_POOL.length
        ? LOCAL_IMAGE_POOL
        : FALLBACK_IMAGE_POOL;

      // ── Tournament synchronization state ───────────────────────
      const serverNow = {{ $serverNow }} * 1000;
      const endTime = {{ $endtime }} * 1000;
      const drift = Date.now() - serverNow;

      // ── State ────────────────────────────────────────────────
      let positions = [];
      let correctCount = 0;
      let startTime = 0;
      let rafId = null;
      let dragSrcSlot = null;
      let currentImage = "";
      let submittedOnce = false;

      const grid = document.getElementById("puzzle-grid");
      const timerEl = document.getElementById("timer");
      const correctEl = document.getElementById("correct");
      const targetImg = document.getElementById("target-img");
      const modal = document.getElementById("result-modal");
      const resultTime = document.getElementById("result-time");
      const resultCorrect = document.getElementById("result-correct");
      const resultTitle = document.getElementById("result-title");
      const resultSubtitle = document.getElementById("result-subtitle");

      function getCorrectCount() {
        return positions.filter((value, index) => value === index).length;
      }

      function updateCorrectDisplay() {
        correctCount = getCorrectCount();
        correctEl.textContent = `${correctCount}/${TOTAL_TILES}`;
        correctEl.classList.remove("count-anim");
        void correctEl.offsetWidth;
        correctEl.classList.add("count-anim");
      }

      let audioCtx;
      function ensureAudio() {
        if (!audioCtx) {
          audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
      }

      function playTone(freq, type = "sine", dur = 0.12, vol = 0.25) {
        try {
          ensureAudio();
          const oscillator = audioCtx.createOscillator();
          const gain = audioCtx.createGain();
          oscillator.type = type;
          oscillator.frequency.setValueAtTime(freq, audioCtx.currentTime);
          oscillator.frequency.exponentialRampToValueAtTime(
            freq * 0.7,
            audioCtx.currentTime + dur,
          );
          gain.gain.setValueAtTime(vol, audioCtx.currentTime);
          gain.gain.exponentialRampToValueAtTime(
            0.001,
            audioCtx.currentTime + dur,
          );
          oscillator.connect(gain);
          gain.connect(audioCtx.destination);
          oscillator.start();
          oscillator.stop(audioCtx.currentTime + dur);
        } catch (error) {}
      }

      const soundPick = () => playTone(660, "square", 0.08, 0.15);
      const soundSwap = () => playTone(420, "sine", 0.14, 0.22);
      const soundSuccess = () =>
        [523, 659, 784, 1047].forEach((freq, index) =>
          setTimeout(() => playTone(freq, "sine", 0.35, 0.3), index * 80),
        );

      // ── Countdown Timer ──────────────────────────────────────
      function updateCountdownTimer() {
        if (submittedOnce) return;

        const correctedNow = Date.now() - drift;
        const remaining = endTime - correctedNow;

        if (remaining <= 0) {
          timerEl.textContent = "00:00";
          submitResult();
          showResult(true);
          return;
        }

        timerEl.textContent = formatTime(Math.max(0, Math.floor(remaining / 1000)));
        rafId = requestAnimationFrame(updateCountdownTimer);
      }

      function startTimer() {
        startTime = Date.now();
        updateCountdownTimer();
      }

      function stopTimer() {
        if (rafId) cancelAnimationFrame(rafId);
      }

      function formatTime(seconds) {
        return `${String(Math.floor(seconds / 60)).padStart(2, "0")}:${String(seconds % 60).padStart(2, "0")}`;
      }

      // ── Solve Check ──
      function isSolved() {
        return positions.every((value, index) => value === index);
      }

      function shufflePositions() {
        positions = Array.from({ length: TOTAL_TILES }, (_, index) => index);
        do {
          for (let i = TOTAL_TILES - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [positions[i], positions[j]] = [positions[j], positions[i]];
          }
        } while (isSolved());
      }

      function initGame() {
        currentImage = IMAGE_POOL[Math.floor(Math.random() * IMAGE_POOL.length)];
        targetImg.src = currentImage;
        shufflePositions();
        updateCorrectDisplay();
        modal.classList.remove("open");

        const img = new Image();
        img.crossOrigin = "anonymous";
        img.onload = renderGrid;
        img.onerror = renderGrid;
        img.src = currentImage;

        startTimer();
      }

      function getBackgroundPosition(partIndex) {
        const row = Math.floor(partIndex / GRID_SIZE);
        const col = partIndex % GRID_SIZE;
        const x = GRID_SIZE === 1 ? 0 : (col / (GRID_SIZE - 1)) * 100;
        const y = GRID_SIZE === 1 ? 0 : (row / (GRID_SIZE - 1)) * 100;
        return `${x}% ${y}%`;
      }

      function renderGrid() {
        grid.innerHTML = "";
        for (let slot = 0; slot < TOTAL_TILES; slot++) {
          const correctIndex = positions[slot];
          const cell = document.createElement("div");
          cell.className = "puzzle-piece";
          cell.dataset.slot = slot;
          cell.draggable = true;
          cell.style.backgroundImage = `url("${currentImage}")`;
          cell.style.backgroundSize = `${GRID_SIZE * 100}% ${GRID_SIZE * 100}%`;
          cell.style.backgroundPosition = getBackgroundPosition(correctIndex);

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

      function swapPieces(a, b) {
        [positions[a], positions[b]] = [positions[b], positions[a]];
        updateCorrectDisplay();
        soundSwap();
        renderGrid();

        if (isSolved()) {
          submitResult();
          setTimeout(() => showResult(false), 400);
        }
      }

      function onDragStart(event) {
        if (submittedOnce) return;
        dragSrcSlot = parseInt(this.dataset.slot, 10);
        this.classList.add("dragging");
        event.dataTransfer.effectAllowed = "move";
        event.dataTransfer.setData("text/plain", dragSrcSlot);
        soundPick();
      }

      // Desktop drag
      function onDragOver(event) {
        if (submittedOnce) return;
        event.preventDefault();
        event.dataTransfer.dropEffect = "move";
        this.classList.add("drag-over");
      }

      function onDragLeave() {
        this.classList.remove("drag-over");
      }

      function onDrop(event) {
        if (submittedOnce) return;
        event.preventDefault();
        this.classList.remove("drag-over");
        const targetSlot = parseInt(this.dataset.slot, 10);
        if (dragSrcSlot === null || dragSrcSlot === targetSlot) {
          return;
        }
        swapPieces(dragSrcSlot, targetSlot);
      }

      function onDragEnd() {
        this.classList.remove("dragging");
        dragSrcSlot = null;
        document
          .querySelectorAll(".puzzle-piece")
          .forEach((piece) => piece.classList.remove("drag-over"));
      }

      let touchSrcSlot = null;
      let touchGhost = null;

      function onTouchStart(event) {
        if (submittedOnce) return;
        event.preventDefault();
        touchSrcSlot = parseInt(this.dataset.slot, 10);
        soundPick();
        this.classList.add("dragging");

        const rect = this.getBoundingClientRect();
        touchGhost = document.createElement("div");
        touchGhost.style.cssText = `position:fixed;width:${rect.width}px;height:${rect.height}px;background-image:url("${currentImage}");background-size:${GRID_SIZE * 100}% ${GRID_SIZE * 100}%;background-position:${this.style.backgroundPosition};opacity:0.8;pointer-events:none;z-index:200;left:${rect.left}px;top:${rect.top}px;border-radius:6px;border:2px solid var(--gold);box-shadow:0 0 20px #ffd16660;`;
        document.body.appendChild(touchGhost);
      }

      function onTouchMove(event) {
        if (submittedOnce) return;
        event.preventDefault();
        if (!touchGhost) {
          return;
        }

        const touch = event.touches[0];
        const width = parseFloat(touchGhost.style.width);
        const height = parseFloat(touchGhost.style.height);
        touchGhost.style.left = `${touch.clientX - width / 2}px`;
        touchGhost.style.top = `${touch.clientY - height / 2}px`;

        document
          .querySelectorAll(".puzzle-piece")
          .forEach((piece) => piece.classList.remove("drag-over"));

        const element = document.elementFromPoint(touch.clientX, touch.clientY);
        const target = element && element.closest(".puzzle-piece");
        if (target) {
          target.classList.add("drag-over");
        }
      }

      function onTouchEnd(event) {
        if (submittedOnce) return;
        if (!touchGhost) {
          return;
        }

        const touch = event.changedTouches[0];
        const element = document.elementFromPoint(touch.clientX, touch.clientY);
        const target = element && element.closest(".puzzle-piece");

        document.querySelectorAll(".puzzle-piece").forEach((piece) => {
          piece.classList.remove("dragging");
          piece.classList.remove("drag-over");
        });

        touchGhost.remove();
        touchGhost = null;

        if (target) {
          const targetSlot = parseInt(target.dataset.slot, 10);
          if (touchSrcSlot !== null && touchSrcSlot !== targetSlot) {
            swapPieces(touchSrcSlot, targetSlot);
          }
        }

        touchSrcSlot = null;
      }

      // ── Score submission ──────────────────────────────────────
      function submitResult() {
        if (submittedOnce) return;
        submittedOnce = true;
        stopTimer();

        const timeTakenSec = Math.floor((Date.now() - startTime) / 1000);

        $("#scoreInput").val(correctCount);
        $("#timeInput").val(timeTakenSec);

        $.ajax({
            url: "{{ url('round/submit-score') }}",
            method: "POST",
            data: $("#gameForm").serialize(),
            success: function(response) {
                console.log("Score submitted successfully");
            },
            error: function() {
                console.error("Error submitting score");
            },
        });
      }

      function showResult(isManualSubmit = false) {
        soundSuccess();
        spawnParticles();
        const solved = correctCount === TOTAL_TILES;
        const timeElapsed = Math.floor((Date.now() - startTime) / 1000);
        resultTime.textContent = formatTime(timeElapsed);
        resultCorrect.textContent = `${correctCount}/${TOTAL_TILES}`;
        resultTitle.textContent = solved
          ? "Hard Cleared"
          : isManualSubmit
            ? "Result Submitted"
            : "Puzzle Result";
        resultSubtitle.textContent = solved
          ? "25 piece puzzle solved"
          : `${correctCount} of ${TOTAL_TILES} pieces are correct`;

        const stars = correctCount === TOTAL_TILES ? 3 : correctCount >= 15 ? 2 : 1;
        ["star1", "star2", "star3"].forEach((id) => {
          document.getElementById(id).className = "star dim";
        });

        for (let i = 0; i < stars; i++) {
          setTimeout(() => {
            document.getElementById(`star${i + 1}`).className = "star lit";
          }, i * 220);
        }

        modal.classList.add("open");
      }

      function spawnParticles() {
        const colors = ["#00d4ff", "#ffd166", "#7b2fff", "#06d6a0", "#ff6b6b", "#fff"];
        for (let i = 0; i < 60; i++) {
          const particle = document.createElement("div");
          particle.className = "particle";
          const angle = Math.random() * 2 * Math.PI;
          const distance = 120 + Math.random() * 200;
          particle.style.setProperty("--tx", `${Math.cos(angle) * distance}px`);
          particle.style.setProperty("--ty", `${Math.sin(angle) * distance}px`);
          particle.style.left = `${Math.random() * 100}vw`;
          particle.style.top = `${20 + Math.random() * 60}vh`;
          particle.style.background =
            colors[Math.floor(Math.random() * colors.length)];
          const size = `${4 + Math.random() * 8}px`;
          particle.style.width = size;
          particle.style.height = size;
          particle.style.animationDuration = `${0.6 + Math.random() * 0.8}s`;
          document.body.appendChild(particle);
          particle.addEventListener("animationend", () => particle.remove());
        }
      }

      document.getElementById("submit-btn").addEventListener("click", () => {
        submitResult();
        showResult(true);
      });

      // ── Start ────────────────────────────────────────────────
      window.addEventListener("load", () => {
        initGame();
      });
    </script>
  </body>
</html>
