<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>15 Puzzle - Genius Arena</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Orbitron:wght:400;700;900&family=Rajdhani:wght@300;400;600;700&display=swap"
      rel="stylesheet"
    />
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: "Rajdhani", sans-serif;
        overflow-x: hidden;
        color: #eaf2ff;
      }

      .orbitron {
        font-family: "Orbitron", sans-serif;
      }

      /* ======= NEW COLOR SCHEME (dark navy / neon teal like your screenshot) ======= */
      .animated-bg {
        background:
          radial-gradient(
            900px 500px at 20% 12%,
            rgba(20, 164, 255, 0.18),
            transparent 60%
          ),
          radial-gradient(
            700px 420px at 80% 30%,
            rgba(0, 255, 209, 0.14),
            transparent 55%
          ),
          radial-gradient(
            900px 520px at 60% 90%,
            rgba(136, 87, 255, 0.12),
            transparent 60%
          ),
          linear-gradient(135deg, #050814 0%, #070b1a 45%, #050913 100%);
        background-size: 200% 200%;
        animation: gradientShift 16s ease infinite;
      }

      @keyframes gradientShift {
        0% {
          background-position: 0% 50%;
        }
        50% {
          background-position: 100% 50%;
        }
        100% {
          background-position: 0% 50%;
        }
      }

      .glow-text {
        text-shadow:
          0 0 18px rgba(0, 255, 209, 0.35),
          0 0 36px rgba(20, 164, 255, 0.22);
      }

      .tile {
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
      }

      .tile:before {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
          45deg,
          transparent,
          rgba(255, 255, 255, 0.12),
          transparent
        );
        transform: rotate(45deg);
        transition: all 0.5s;
      }

      .tile:hover:before {
        left: 110%;
      }

      .tile:hover {
        transform: scale(1.04);
        box-shadow:
          0 0 18px rgba(0, 255, 209, 0.35),
          0 0 38px rgba(20, 164, 255, 0.22);
      }

      .tile:active {
        transform: scale(0.96);
      }

      .glass-overlay {
        background: rgba(3, 6, 18, 0.82);
        backdrop-filter: blur(12px);
      }

      .result-card {
        background: linear-gradient(
          135deg,
          rgba(10, 16, 34, 0.78),
          rgba(8, 12, 26, 0.72)
        );
        backdrop-filter: blur(22px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow:
          0 0 0 1px rgba(0, 255, 209, 0.1),
          0 18px 60px rgba(0, 0, 0, 0.55);
        animation: slideDown 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
      }

      @keyframes slideDown {
        from {
          transform: translateY(-100%);
          opacity: 0;
        }
        to {
          transform: translateY(0);
          opacity: 1;
        }
      }

      .checkmark {
        animation: checkmarkPop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
      }

      @keyframes checkmarkPop {
        0% {
          transform: scale(0);
          opacity: 0;
        }
        50% {
          transform: scale(1.2);
        }
        100% {
          transform: scale(1);
          opacity: 1;
        }
      }

      .pulse-glow {
        animation: pulseGlow 2s ease-in-out infinite;
      }

      @keyframes pulseGlow {
        0%,
        100% {
          box-shadow:
            0 0 0 1px rgba(0, 255, 209, 0.18),
            0 0 22px rgba(0, 255, 209, 0.22);
        }
        50% {
          box-shadow:
            0 0 0 1px rgba(20, 164, 255, 0.18),
            0 0 36px rgba(20, 164, 255, 0.28),
            0 0 54px rgba(0, 255, 209, 0.18);
        }
      }

      .number-item {
        animation: fadeInUp 0.5s ease-out forwards;
        opacity: 0;
      }

      @keyframes fadeInUp {
        from {
          opacity: 0;
          transform: translateY(20px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      /* Buttons like screenshot: dark + neon border + teal/green highlight */
      .btn-gradient {
        background: linear-gradient(
          180deg,
          rgba(14, 22, 48, 0.9),
          rgba(9, 14, 30, 0.9)
        );
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow:
          0 0 0 1px rgba(0, 255, 209, 0.12),
          inset 0 0 0 1px rgba(0, 255, 209, 0.1);
        transition: all 0.25s ease;
      }

      .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow:
          0 0 0 1px rgba(0, 255, 209, 0.18),
          0 10px 30px rgba(0, 0, 0, 0.45),
          0 0 26px rgba(0, 255, 209, 0.18);
      }

      .nav-link {
        position: relative;
        transition: all 0.3s ease;
      }

      .nav-link:after {
        content: "";
        position: absolute;
        bottom: -2px;
        left: 50%;
        width: 0;
        height: 2px;
        background: #00ffd1;
        transition: all 0.3s ease;
        transform: translateX(-50%);
      }

      .nav-link:hover:after {
        width: 100%;
      }

      /* Stat cards like your screenshot */
      .stats-box {
        background: linear-gradient(
          135deg,
          rgba(12, 18, 40, 0.78),
          rgba(9, 13, 28, 0.7)
        );
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow:
          0 0 0 1px rgba(0, 255, 209, 0.1),
          inset 0 0 0 1px rgba(20, 164, 255, 0.06);
        backdrop-filter: blur(10px);
      }

      /* Board container */
      .board-shell {
        background: linear-gradient(
          135deg,
          rgba(10, 16, 34, 0.62),
          rgba(7, 10, 22, 0.56)
        );
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow:
          0 0 0 1px rgba(20, 164, 255, 0.06),
          0 18px 60px rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(14px);
      }

      /* Prevent body scroll when overlay is open */
      body.overlay-open {
        overflow: hidden;
      }

      @media (max-width: 360px) {
        .orbitron {
          letter-spacing: -0.02em;
        }
      }
    </style>
  </head>
  <body class="animated-bg min-h-screen">
    <!-- Main Game Container -->
    <main class="max-w-2xl mx-auto px-3 sm:px-4 md:px-6 py-4 sm:py-8 md:py-12">
      <!-- Game Title -->
      <div class="text-center mb-4 sm:mb-6 md:mb-8">
        <h2
          class="orbitron text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-white mb-2 sm:mb-3 glow-text"
        >
          15 Tile PUZZLE
        </h2>
        <p
          class="text-white/75 text-xs sm:text-sm md:text-base lg:text-lg px-2 sm:px-4"
        >
          " Arrange numbers 1 to 15 in order with empty box at bottom right."
        </p>
      </div>

      <!-- Stats Bar -->
      <div class="flex gap-2 sm:gap-3 md:gap-4 mb-4 sm:mb-6 md:mb-8">
        <div
          class="stats-box flex-1 rounded-xl sm:rounded-2xl px-3 sm:px-4 md:px-6 py-2 sm:py-3 md:py-4"
        >
          <div
            class="text-emerald-200/90 text-[10px] sm:text-xs font-semibold mb-0.5 sm:mb-1"
          >
            MOVES
          </div>
          <div
            class="text-white text-xl sm:text-2xl md:text-3xl font-bold orbitron"
            id="moves"
          >
            0
          </div>
        </div>

        <div
          class="stats-box flex-1 rounded-xl sm:rounded-2xl px-3 sm:px-4 md:px-6 py-2 sm:py-3 md:py-4"
        >
          <div
            class="text-emerald-200/90 text-[10px] sm:text-xs font-semibold mb-0.5 sm:mb-1"
          >
            TIME
          </div>
          <div
            class="text-white text-xl sm:text-2xl md:text-3xl font-bold orbitron"
            id="timer"
          >
            00:00
          </div>
        </div>
      </div>

      <!-- Puzzle Grid -->
      <div
        class="board-shell rounded-xl sm:rounded-2xl md:rounded-3xl p-3 sm:p-4 md:p-6 mb-3 sm:mb-4 md:mb-6"
      >
        <div
          id="puzzle-grid"
          class="grid grid-cols-4 gap-1.5 sm:gap-2 md:gap-3"
        >
          <!-- Tiles will be generated here -->
        </div>
      </div>

      <!-- Buttons -->
      <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 md:gap-4">
        <button
          id="restart-btn"
          class="btn-gradient flex-1 text-white font-bold py-3 sm:py-3.5 md:py-4 px-4 sm:px-6 md:px-8 rounded-full text-sm sm:text-base md:text-lg orbitron"
        >
          RESTART
        </button>
        <button
          id="submit-btn"
          class="btn-gradient flex-1 text-white font-bold py-3 sm:py-3.5 md:py-4 px-4 sm:px-6 md:px-8 rounded-full text-sm sm:text-base md:text-lg orbitron"
        >
          SUBMIT RESULT
        </button>
      </div>
    </main>

    <!-- Result Overlay -->
    <div
      id="result-overlay"
      class="glass-overlay fixed inset-0 hidden z-50 overflow-y-auto"
    >
      <div class="min-h-screen flex items-center justify-center p-3 sm:p-4">
        <div
          class="result-card rounded-2xl sm:rounded-3xl p-4 sm:p-6 md:p-8 w-full max-w-[95%] sm:max-w-md md:max-w-lg"
        >
          <div class="text-center mb-4 sm:mb-6">
            <div
              class="orbitron text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-emerald-200 mb-2 sm:mb-3 glow-text leading-tight"
            >
              LEVEL COMPLETE!
            </div>
            <div class="text-white/75 text-sm sm:text-base md:text-lg">
              Outstanding performance!
            </div>
          </div>

          <!-- Final Stats -->
          <div class="grid grid-cols-2 gap-2 sm:gap-3 mb-4 sm:mb-6">
            <div
              class="rounded-xl p-3 sm:p-4 md:p-5 border border-white/10"
              style="
                background: linear-gradient(
                  135deg,
                  rgba(12, 18, 40, 0.7),
                  rgba(9, 13, 28, 0.65)
                );
                box-shadow:
                  0 0 0 1px rgba(0, 255, 209, 0.1),
                  inset 0 0 0 1px rgba(20, 164, 255, 0.06);
                backdrop-filter: blur(10px);
              "
            >
              <div
                class="text-emerald-200/90 text-[10px] sm:text-xs font-semibold mb-1"
              >
                FINAL TIME
              </div>
              <div
                class="text-white text-lg sm:text-xl md:text-2xl font-bold orbitron"
                id="final-time"
              >
                00:00
              </div>
            </div>

            <div
              class="rounded-xl p-3 sm:p-4 md:p-5 border border-white/10"
              style="
                background: linear-gradient(
                  135deg,
                  rgba(12, 18, 40, 0.7),
                  rgba(9, 13, 28, 0.65)
                );
                box-shadow:
                  0 0 0 1px rgba(0, 255, 209, 0.1),
                  inset 0 0 0 1px rgba(20, 164, 255, 0.06);
                backdrop-filter: blur(10px);
              "
            >
              <div
                class="text-emerald-200/90 text-[10px] sm:text-xs font-semibold mb-1"
              >
                TOTAL MOVES
              </div>
              <div
                class="text-white text-lg sm:text-xl md:text-2xl font-bold orbitron"
                id="final-moves"
              >
                0
              </div>
            </div>
          </div>

          <!-- Number List -->
          <div class="mb-4 sm:mb-6">
            <div
              class="text-white/85 text-sm sm:text-base font-semibold mb-2 sm:mb-3"
            >
              Puzzle State:
            </div>
            <div id="number-list" class="grid grid-cols-4 gap-1.5 sm:gap-2">
              <!-- Numbers will be generated here in 4x4 grid -->
            </div>
          </div>

          <button
            id="close-result"
            class="btn-gradient w-full text-white font-bold py-3 sm:py-4 px-4 sm:px-6 rounded-full text-sm sm:text-base md:text-lg orbitron"
          >
            PLAY AGAIN
          </button>
        </div>
      </div>
    </div>

    <script>
      // Sound generation functions
      const audioContext = new (
        window.AudioContext || window.webkitAudioContext
      )();

      function playTileSound() {
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
        oscillator.frequency.exponentialRampToValueAtTime(
          600,
          audioContext.currentTime + 0.1,
        );

        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(
          0.01,
          audioContext.currentTime + 0.1,
        );

        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.1);
      }

      function playSuccessSound() {
        const times = [0, 0.1, 0.2];
        const frequencies = [523.25, 659.25, 783.99]; // C, E, G chord

        times.forEach((time, index) => {
          const oscillator = audioContext.createOscillator();
          const gainNode = audioContext.createGain();

          oscillator.connect(gainNode);
          gainNode.connect(audioContext.destination);

          oscillator.frequency.setValueAtTime(
            frequencies[index],
            audioContext.currentTime + time,
          );

          gainNode.gain.setValueAtTime(0.2, audioContext.currentTime + time);
          gainNode.gain.exponentialRampToValueAtTime(
            0.01,
            audioContext.currentTime + time + 0.3,
          );

          oscillator.start(audioContext.currentTime + time);
          oscillator.stop(audioContext.currentTime + time + 0.3);
        });
      }

      function playButtonSound() {
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        oscillator.frequency.setValueAtTime(400, audioContext.currentTime);
        oscillator.frequency.exponentialRampToValueAtTime(
          300,
          audioContext.currentTime + 0.08,
        );

        gainNode.gain.setValueAtTime(0.25, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(
          0.01,
          audioContext.currentTime + 0.08,
        );

        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.08);
      }

      class PuzzleGame {
        constructor() {
          this.grid = [];
          this.emptyPos = { row: 3, col: 3 };
          this.moves = 0;
          this.seconds = 0;
          this.timer = null;
          this.isSolved = false;

          this.puzzleGrid = document.getElementById("puzzle-grid");
          this.movesDisplay = document.getElementById("moves");
          this.timerDisplay = document.getElementById("timer");
          this.submitBtn = document.getElementById("submit-btn");
          this.restartBtn = document.getElementById("restart-btn");
          this.resultOverlay = document.getElementById("result-overlay");
          this.closeResultBtn = document.getElementById("close-result");

          this.init();
        }

        init() {
          this.initializeGrid();
          this.shuffleGrid();
          this.render();
          this.startTimer();

          this.restartBtn.addEventListener("click", () => this.restart());
          this.submitBtn.addEventListener("click", () => this.showResult());
          this.closeResultBtn.addEventListener("click", () =>
            this.closeResult(),
          );
        }

        initializeGrid() {
          // Initialize grid with numbers 1-15 and one empty space
          let num = 1;
          for (let i = 0; i < 4; i++) {
            this.grid[i] = [];
            for (let j = 0; j < 4; j++) {
              if (i === 3 && j === 3) {
                this.grid[i][j] = 0; // Empty space at bottom-right
              } else {
                this.grid[i][j] = num++;
              }
            }
          }
        }

        shuffleGrid() {
          // Shuffle ensuring solvability and empty space ALWAYS stays at bottom-right (position 15)
          // We only shuffle the first 15 tiles, never the empty space
          const tiles = [];
          for (let i = 0; i < 4; i++) {
            for (let j = 0; j < 4; j++) {
              if (!(i === 3 && j === 3)) {
                // Exclude the last position
                tiles.push(this.grid[i][j]);
              }
            }
          }

          // Fisher-Yates shuffle for the 15 tiles
          for (let i = tiles.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [tiles[i], tiles[j]] = [tiles[j], tiles[i]];
          }

          // Check if shuffle is solvable, if not swap first two tiles
          if (!this.isSolvable(tiles)) {
            [tiles[0], tiles[1]] = [tiles[1], tiles[0]];
          }

          // Place shuffled tiles back into grid
          let tileIndex = 0;
          for (let i = 0; i < 4; i++) {
            for (let j = 0; j < 4; j++) {
              if (i === 3 && j === 3) {
                this.grid[i][j] = 0; // Keep empty at bottom-right
              } else {
                this.grid[i][j] = tiles[tileIndex++];
              }
            }
          }

          // Empty position always stays at bottom-right
          this.emptyPos = { row: 3, col: 3 };

          // Reset moves counter after shuffle
          this.moves = 0;
          this.movesDisplay.textContent = "0";
        }

        isSolvable(tiles) {
          // Count inversions in the tile array
          let inversions = 0;
          for (let i = 0; i < tiles.length - 1; i++) {
            for (let j = i + 1; j < tiles.length; j++) {
              if (tiles[i] > tiles[j]) {
                inversions++;
              }
            }
          }

          // For 4x4 puzzle with blank in bottom-right,
          // puzzle is solvable if inversions is even
          return inversions % 2 === 0;
        }

        getValidNeighbors(row, col) {
          const neighbors = [];
          const directions = [
            { row: row - 1, col: col },
            { row: row + 1, col: col },
            { row: row, col: col - 1 },
            { row: row, col: col + 1 },
          ];

          for (const dir of directions) {
            if (dir.row >= 0 && dir.row < 4 && dir.col >= 0 && dir.col < 4) {
              neighbors.push(dir);
            }
          }

          return neighbors;
        }

        swapTiles(row1, col1, row2, col2, countMove = true) {
          const temp = this.grid[row1][col1];
          this.grid[row1][col1] = this.grid[row2][col2];
          this.grid[row2][col2] = temp;

          if (countMove) {
            this.moves++;
            this.movesDisplay.textContent = this.moves;
          }
        }

        isAdjacent(row, col) {
          const rowDiff = Math.abs(row - this.emptyPos.row);
          const colDiff = Math.abs(col - this.emptyPos.col);
          return (
            (rowDiff === 1 && colDiff === 0) || (rowDiff === 0 && colDiff === 1)
          );
        }

        handleTileClick(row, col) {
          if (this.isSolved) return;

          if (this.isAdjacent(row, col)) {
            playTileSound();
            this.swapTiles(row, col, this.emptyPos.row, this.emptyPos.col);
            this.emptyPos = { row, col };
            this.render();

            if (this.checkWin()) {
              this.isSolved = true;
              this.stopTimer();
              this.enableSubmit();
              playSuccessSound();
            }
          }
        }

        checkWin() {
          let expectedNum = 1;
          for (let i = 0; i < 4; i++) {
            for (let j = 0; j < 4; j++) {
              if (i === 3 && j === 3) {
                return this.grid[i][j] === 0;
              }
              if (this.grid[i][j] !== expectedNum) {
                return false;
              }
              expectedNum++;
            }
          }
          return true;
        }

        render() {
          this.puzzleGrid.innerHTML = "";

          for (let i = 0; i < 4; i++) {
            for (let j = 0; j < 4; j++) {
              const tile = document.createElement("div");

              if (this.grid[i][j] === 0) {
                tile.className =
                  "aspect-square rounded-lg sm:rounded-xl md:rounded-2xl bg-transparent border border-dashed border-white/15";
              } else {
                // UI ONLY changed here (colors), logic untouched
                tile.className =
                  "tile aspect-square rounded-lg sm:rounded-xl md:rounded-2xl flex items-center justify-center shadow-lg";
                tile.style.background =
                  "linear-gradient(135deg, rgba(14,22,48,0.95), rgba(8,12,26,0.92))";
                tile.style.border = "1px solid rgba(255,255,255,0.10)";
                tile.style.boxShadow =
                  "0 0 0 1px rgba(0,255,209,0.10), inset 0 0 0 1px rgba(20,164,255,0.06)";

                tile.innerHTML = `<span class="orbitron text-xl sm:text-2xl md:text-3xl lg:text-4xl font-black text-white" style="text-shadow: 0 0 14px rgba(0,255,209,0.20);">${this.grid[i][j]}</span>`;
                tile.addEventListener("click", () =>
                  this.handleTileClick(i, j),
                );
              }

              this.puzzleGrid.appendChild(tile);
            }
          }
        }

        startTimer() {
          this.timer = setInterval(() => {
            this.seconds++;
            const mins = Math.floor(this.seconds / 60)
              .toString()
              .padStart(2, "0");
            const secs = (this.seconds % 60).toString().padStart(2, "0");
            this.timerDisplay.textContent = `${mins}:${secs}`;
          }, 1000);
        }

        stopTimer() {
          if (this.timer) {
            clearInterval(this.timer);
            this.timer = null;
          }
        }

        enableSubmit() {
          this.submitBtn.classList.add("pulse-glow");
        }

        showResult() {
          playButtonSound();

          document.getElementById("final-time").textContent =
            this.timerDisplay.textContent;
          document.getElementById("final-moves").textContent = this.moves;

          // Check actual puzzle state
          const isComplete = this.checkWin();
          const completionPercentage = this.getCompletionPercentage();

          // Update result title and message based on performance
          const resultTitle = document.querySelector(
            ".result-card .orbitron.text-2xl",
          );
          const resultMessage = document.querySelector(
            ".result-card .text-white\\/75, .result-card .text-white\\/80",
          );

          if (isComplete) {
            resultTitle.textContent = "LEVEL COMPLETE!";
            resultTitle.className =
              "orbitron text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-emerald-200 mb-2 sm:mb-3 glow-text leading-tight";

            // Performance-based messages
            if (this.moves < 80) {
              resultMessage.textContent =
                "🏆 PERFECT! Outstanding performance!";
            } else if (this.moves < 150) {
              resultMessage.textContent = "⭐ EXCELLENT! Great work!";
            } else if (this.moves < 250) {
              resultMessage.textContent = "✓ GOOD! Well done!";
            } else {
              resultMessage.textContent = "✓ COMPLETED! Keep practicing!";
            }
          } else {
            resultTitle.textContent = "PUZZLE INCOMPLETE";
            resultTitle.className =
              "orbitron text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-orange-300 mb-2 sm:mb-3 leading-tight";
            resultMessage.textContent = `${completionPercentage}% Complete - Keep trying!`;
          }

          const numberList = document.getElementById("number-list");
          numberList.innerHTML = "";

          // Display in 4x4 grid format matching the game
          let expectedNum = 1;
          let tileIndex = 0;

          for (let i = 0; i < 4; i++) {
            for (let j = 0; j < 4; j++) {
              setTimeout(() => {
                const item = document.createElement("div");

                // Check if this is the empty space position (bottom-right)
                if (i === 3 && j === 3) {
                  item.className =
                    "number-item aspect-square rounded-md sm:rounded-lg flex items-center justify-center border border-dashed border-white/15";
                  item.style.background =
                    "linear-gradient(135deg, rgba(10,16,34,0.55), rgba(7,10,22,0.50))";
                  item.style.animationDelay = `${tileIndex * 0.03}s`;
                  item.innerHTML = `<span class="text-white/40 text-[10px] sm:text-xs font-bold">EMPTY</span>`;
                } else {
                  const currentValue = this.grid[i][j];
                  const isCorrect = currentValue === expectedNum;

                  item.className = `number-item aspect-square rounded-md sm:rounded-lg flex flex-col items-center justify-center gap-0.5 sm:gap-1 border`;
                  item.style.animationDelay = `${tileIndex * 0.03}s`;

                  if (isCorrect) {
                    item.style.background =
                      "linear-gradient(135deg, rgba(0,255,209,0.10), rgba(20,164,255,0.08))";
                    item.style.borderColor = "rgba(0,255,209,0.28)";
                    item.innerHTML = `
                      <span class="text-white font-black text-base sm:text-lg md:text-xl orbitron">${currentValue}</span>
                      <svg class="checkmark w-2.5 h-2.5 sm:w-3 sm:h-3 md:w-4 md:h-4 text-emerald-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                    `;
                  } else {
                    item.style.background =
                      "linear-gradient(135deg, rgba(255,88,116,0.12), rgba(255,122,64,0.08))";
                    item.style.borderColor = "rgba(255,88,116,0.30)";
                    item.innerHTML = `
                      <span class="text-white font-black text-base sm:text-lg md:text-xl orbitron">${currentValue}</span>
                      <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 md:w-4 md:h-4 text-rose-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                      </svg>
                    `;
                  }
                  expectedNum++;
                }

                numberList.appendChild(item);
              }, tileIndex * 30);

              tileIndex++;
            }
          }

          // Prevent body scroll
          document.body.classList.add("overlay-open");
          this.resultOverlay.classList.remove("hidden");
        }

        getCurrentTilePositions() {
          // Get current positions of tiles 1-15 in order
          const positions = [];
          for (let i = 0; i < 4; i++) {
            for (let j = 0; j < 4; j++) {
              if (this.grid[i][j] !== 0) {
                positions.push(this.grid[i][j]);
              }
            }
          }
          return positions;
        }

        getCompletionPercentage() {
          let correctCount = 0;
          let expectedNum = 1;

          for (let i = 0; i < 4; i++) {
            for (let j = 0; j < 4; j++) {
              if (i === 3 && j === 3) continue; // Skip empty space
              if (this.grid[i][j] === expectedNum) {
                correctCount++;
              }
              expectedNum++;
            }
          }

          return Math.round((correctCount / 15) * 100);
        }

        closeResult() {
          playButtonSound();
          document.body.classList.remove("overlay-open");
          this.resultOverlay.classList.add("hidden");
          this.restart();
        }

        restart() {
          playButtonSound();
          this.stopTimer();
          this.seconds = 0;
          this.moves = 0;
          this.isSolved = false;

          this.timerDisplay.textContent = "00:00";
          this.movesDisplay.textContent = "0";

          this.submitBtn.classList.remove("pulse-glow");

          this.emptyPos = { row: 3, col: 3 };
          this.initializeGrid();
          this.shuffleGrid();
          this.render();
          this.startTimer();
        }
      }

      // Initialize game when page loads
      window.addEventListener("DOMContentLoaded", () => {
        new PuzzleGame();
      });
    </script>
  </body>
</html>
