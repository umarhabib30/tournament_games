<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Number Sequence Challenge</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");

      body {
        font-family: "Inter", sans-serif;
        background: linear-gradient(135deg, #1a202c, #2d3748);
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 1rem;
      }

      .number-btn {
        transition: all 0.25s ease;
        font-weight: 600;
        aspect-ratio: 1/1;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 0.75rem; /* smaller text */
        padding: 0.3rem;
        background: linear-gradient(145deg, #4a5568, #2d3748);
        border: 1px solid #1a202c;
      }

      .number-btn:hover:not(:disabled) {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.25);
        background: linear-gradient(145deg, #2b6cb0, #2c5282);
      }

      .correct {
        background: #000 !important;
        color: #fff !important;
        transform: scale(0.9);
      }

      .wrong {
        animation: shake 0.5s ease;
      }

      @keyframes shake {
        0%,
        100% {
          transform: translateX(0);
        }
        20%,
        60% {
          transform: translateX(-4px);
        }
        40%,
        80% {
          transform: translateX(4px);
        }
      }

      .pulse {
        animation: pulse 1.5s infinite;
      }

      @keyframes pulse {
        0% {
          transform: scale(1);
        }
        50% {
          transform: scale(1.05);
        }
        100% {
          transform: scale(1);
        }
      }

      .success-popup {
        animation: popIn 0.5s ease-out;
      }

      @keyframes popIn {
        0% {
          transform: scale(0.5);
          opacity: 0;
        }
        100% {
          transform: scale(1);
          opacity: 1;
        }
      }

      /* Responsive adjustments */
      @media (max-width: 640px) {
        .number-btn {
          font-size: 0.65rem;
          padding: 0.2rem;
        }
        .game-grid {
          gap: 0.15rem;
        }
      }

      /* Progress bar */
      .progress-bar {
        height: 6px;
        transition: width 0.3s ease;
      }

      .result-card {
        max-width: 320px;
        padding: 1.5rem;
      }

      .result-icon {
        font-size: 2.5rem;
      }

      .game-grid {
        gap: 0.2rem;
      }

      /* Modal styles */
      .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 50;
      }

      .modal-content {
        background: linear-gradient(135deg, #2d3748, #4a5568);
        border-radius: 0.75rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        width: 90%;
        max-width: 400px;
        padding: 1.5rem;
      }
    </style>
  </head>
  <body class="text-gray-100">
    <div
      class="container max-w-4xl mx-auto p-4 md:p-6 bg-gray-800 rounded-xl shadow-2xl"
    >
      <!-- Header -->
      <header class="text-center mb-6 md:mb-8">
        <h1
          class="text-2xl md:text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500 pulse"
        >
          Number Sequence Challenge
        </h1>
        <p class="text-gray-400 mt-2 text-sm">
          Click numbers in ascending order from 1 to 81
        </p>

        <!-- Progress Bar -->
        <div class="mt-4 bg-gray-700 rounded-full h-2.5">
          <div
            id="progress-bar"
            class="progress-bar bg-blue-600 rounded-full h-2.5"
            style="width: 0%"
          ></div>
        </div>
      </header>

      <!-- Game Stats -->
      <div
        class="flex justify-between items-center mb-6 bg-gray-900 p-4 rounded-lg stats-container"
      >
        <div class="text-center stat-box">
          <div class="text-sm text-gray-400">Numbers Found</div>
          <div id="found-numbers" class="text-2xl font-bold text-blue-400">
            0/81
          </div>
        </div>

        <div class="text-center stat-box">
          <div class="text-sm text-gray-400">Time</div>
          <div id="timer" class="text-2xl font-bold text-green-400">00:00</div>
        </div>

        <div class="text-center stat-box">
          <div class="text-sm text-gray-400">Best Time</div>
          <div id="best-time" class="text-2xl font-bold text-yellow-400">
            --:--
          </div>
        </div>
      </div>

      <!-- Game Board -->
      <div class="grid grid-cols-9 gap-1 mb-6 game-grid" id="game-board">
        <!-- Numbers will be generated here -->
      </div>

      <!-- Controls -->
      <div class="flex justify-center gap-4 mb-6">
        <button
          id="start-btn"
          class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition shadow-md"
        >
          <i class="fas fa-play mr-2"></i>Start Game
        </button>
        <button
          id="submit-btn"
          class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition shadow-md"
        >
          <i class="fas fa-check-circle mr-2"></i>Submit
        </button>
      </div>

      <!-- Instructions -->
      <div class="bg-gray-900 p-4 rounded-lg text-sm">
        <h3 class="font-medium text-gray-300 mb-2">How to Play:</h3>
        <ul class="list-disc list-inside text-gray-400 space-y-1">
          <li>Click numbers in ascending order from 1 to 81</li>
          <li>Correct numbers turn black and are disabled</li>
          <li>Wrong numbers will shake to indicate error</li>
          <li>Submit your result anytime to see your progress</li>
          <li>Try to beat your best time!</li>
        </ul>
      </div>
    </div>

    <!-- Results Modal (initially hidden) -->
    <div id="results-modal" class="modal-overlay hidden">
      <div class="modal-content success-popup">
        <div class="text-center">
          <div class="result-icon mx-auto mb-4">
            <i id="result-icon" class="fas fa-trophy text-yellow-400"></i>
          </div>

          <h2 id="result-title" class="text-2xl font-bold text-white mb-2">Game Progress</h2>
          <p id="result-message" class="text-gray-300 mb-4">Your current progress:</p>

          <div class="bg-gray-800 rounded-lg p-4 mb-4">
            <div class="flex justify-between items-center mb-2">
              <span class="text-gray-400">Numbers Found:</span>
              <span id="result-numbers" class="text-xl font-bold text-blue-400">0/81</span>
            </div>
            <div class="flex justify-between items-center mb-2">
              <span class="text-gray-400">Your Time:</span>
              <span id="final-time" class="text-xl font-bold text-green-400">00:00</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-400">Best Time:</span>
              <span id="modal-best-time" class="text-xl font-bold text-yellow-400">--:--</span>
            </div>
          </div>

          <div class="flex justify-center gap-3">
            <button
              id="continue-btn"
              class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition"
            >
              Continue
            </button>
            <button
              id="new-game-btn"
              class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition"
            >
              New Game
            </button>
          </div>
        </div>
      </div>
    </div>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        // DOM Elements
        const gameBoard = document.getElementById("game-board");
        const startBtn = document.getElementById("start-btn");
        const submitBtn = document.getElementById("submit-btn");
        const timerEl = document.getElementById("timer");
        const bestTimeEl = document.getElementById("best-time");
        const modalBestTimeEl = document.getElementById("modal-best-time");
        const resultsModal = document.getElementById("results-modal");
        const finalTimeEl = document.getElementById("final-time");
        const continueBtn = document.getElementById("continue-btn");
        const newGameBtn = document.getElementById("new-game-btn");
        const foundNumbersEl = document.getElementById("found-numbers");
        const resultNumbersEl = document.getElementById("result-numbers");
        const resultTitleEl = document.getElementById("result-title");
        const resultMessageEl = document.getElementById("result-message");
        const resultIconEl = document.getElementById("result-icon");
        const progressBar = document.getElementById("progress-bar");

        // Game state
        let numbers = [];
        let nextNumber = 1;
        let timerInterval;
        let startTime;
        let elapsedTime = 0;
        let gameStarted = false;
        let bestTime = localStorage.getItem("bestTime") || null;
        let completed = false;

        // Initialize best time display
        if (bestTime) {
          bestTimeEl.textContent = formatTime(bestTime);
          modalBestTimeEl.textContent = formatTime(bestTime);
        }

        // Generate numbers 1-81 in random order
        function generateNumbers() {
          numbers = Array.from({ length: 81 }, (_, i) => i + 1);

          // Fisher-Yates shuffle algorithm
          for (let i = numbers.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [numbers[i], numbers[j]] = [numbers[j], numbers[i]];
          }

          return numbers;
        }

        // Render the game board
        function renderBoard() {
          gameBoard.innerHTML = "";
          numbers.forEach((num, index) => {
            const button = document.createElement("button");
            button.className =
              "number-btn bg-gray-700 text-white rounded text-xs";
            button.textContent = num;
            button.dataset.number = num;

            button.addEventListener("click", () =>
              handleNumberClick(num, button)
            );

            gameBoard.appendChild(button);
          });
        }

        // Handle number button click
        function handleNumberClick(number, button) {
          if (!gameStarted) return;

          if (number === nextNumber) {
            // Correct number
            button.classList.add("correct");
            button.disabled = true;
            nextNumber++;
            updateProgress();

            // Play success sound
            playSound("correct");

            // Check if game is completed
            if (nextNumber > 81) {
              completed = true;
              endGame(true);
            }
          } else {
            // Wrong number
            button.classList.add("wrong");
            playSound("wrong");

            // Remove the wrong class after animation completes
            setTimeout(() => {
              button.classList.remove("wrong");
            }, 500);
          }
        }

        // Update progress
        function updateProgress() {
          const found = nextNumber - 1;
          foundNumbersEl.textContent = `${found}/81`;
          const progress = (found / 81) * 100;
          progressBar.style.width = `${progress}%`;

          // Change progress bar color based on progress
          if (progress < 30) {
            progressBar.classList.remove("bg-blue-600", "bg-green-600");
            progressBar.classList.add("bg-blue-600");
          } else if (progress < 70) {
            progressBar.classList.remove("bg-blue-600", "bg-green-600");
            progressBar.classList.add("bg-blue-600");
          } else {
            progressBar.classList.remove("bg-blue-600");
            progressBar.classList.add("bg-green-600");
          }
        }

        // Start the game
        function startGame() {
          generateNumbers();
          renderBoard();

          nextNumber = 1;
          elapsedTime = 0;
          timerEl.textContent = "00:00";
          foundNumbersEl.textContent = "0/81";
          progressBar.style.width = "0%";
          progressBar.classList.remove("bg-green-600");
          progressBar.classList.add("bg-blue-600");

          startBtn.disabled = true;
          startBtn.classList.add("opacity-50", "cursor-not-allowed");

          // Start timer
          startTime = Date.now() - elapsedTime;
          timerInterval = setInterval(updateTimer, 100);

          gameStarted = true;
          completed = false;
        }

        // Update the timer
        function updateTimer() {
          elapsedTime = Date.now() - startTime;
          timerEl.textContent = formatTime(elapsedTime);

          // Stop at 10 minutes
          if (elapsedTime >= 600000) {
            clearInterval(timerInterval);
            timerEl.textContent = "10:00";
            timerEl.classList.add("text-red-400");
          }
        }

        // Format time in mm:ss
        function formatTime(ms) {
          // If ms is a string (from localStorage), convert to number
          const time = typeof ms === "string" ? parseInt(ms) : ms;

          const totalSeconds = Math.floor(time / 1000);
          const minutes = Math.floor(totalSeconds / 60);
          const seconds = totalSeconds % 60;

          return `${minutes
            .toString()
            .padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
        }

        // End the game and show results
        function endGame(isComplete = false) {
          clearInterval(timerInterval);
          gameStarted = false;

          // Update results modal
          if (isComplete) {
            resultTitleEl.textContent = "Congratulations!";
            resultMessageEl.textContent = "You completed the challenge!";
            resultIconEl.className = "fas fa-trophy text-yellow-400";

            // Update best time if applicable
            if (!bestTime || elapsedTime < bestTime) {
              bestTime = elapsedTime;
              localStorage.setItem("bestTime", bestTime);
              bestTimeEl.textContent = formatTime(bestTime);
            }
          } else {
            resultTitleEl.textContent = "Game Progress";
            resultMessageEl.textContent = "Your current progress:";
            resultIconEl.className = "fas fa-chart-simple text-blue-400";
          }

          resultNumbersEl.textContent = `${nextNumber - 1}/81 numbers`;
          finalTimeEl.textContent = formatTime(elapsedTime);
          modalBestTimeEl.textContent = bestTime
            ? formatTime(bestTime)
            : "--:--";

          // Show results modal
          resultsModal.classList.remove("hidden");
        }

        // Play sound effects
        function playSound(type) {
          try {
            // Create audio context
            const audioContext = new (window.AudioContext ||
              window.webkitAudioContext)();

            if (type === "correct") {
              // Correct sound - pleasant chime
              const oscillator = audioContext.createOscillator();
              oscillator.type = "sine";
              oscillator.frequency.setValueAtTime(
                523.25,
                audioContext.currentTime
              ); // C5
              oscillator.frequency.setValueAtTime(
                659.25,
                audioContext.currentTime + 0.1
              ); // E5

              const gainNode = audioContext.createGain();
              gainNode.gain.setValueAtTime(0.5, audioContext.currentTime);
              gainNode.gain.exponentialRampToValueAtTime(
                0.01,
                audioContext.currentTime + 0.3
              );

              oscillator.connect(gainNode);
              gainNode.connect(audioContext.destination);

              oscillator.start();
              oscillator.stop(audioContext.currentTime + 0.3);
            } else if (type === "wrong") {
              // Wrong sound - low buzz
              const oscillator = audioContext.createOscillator();
              oscillator.type = "sawtooth";
              oscillator.frequency.setValueAtTime(
                150,
                audioContext.currentTime
              );

              const gainNode = audioContext.createGain();
              gainNode.gain.setValueAtTime(0.5, audioContext.currentTime);
              gainNode.gain.exponentialRampToValueAtTime(
                0.01,
                audioContext.currentTime + 0.5
              );

              oscillator.connect(gainNode);
              gainNode.connect(audioContext.destination);

              oscillator.start();
              oscillator.stop(audioContext.currentTime + 0.5);
            }
          } catch (e) {
            console.log("Audio context not supported");
          }
        }

        // Event Listeners
        startBtn.addEventListener("click", startGame);

        submitBtn.addEventListener("click", function () {
          if (gameStarted) {
            endGame(completed);
          } else {
            // If game hasn't started, show a message
            resultTitleEl.textContent = "Game Not Started";
            resultMessageEl.textContent = "Please start the game first!";
            resultIconEl.className = "fas fa-info-circle text-blue-400";
            resultNumbersEl.textContent = "0/81 numbers";
            finalTimeEl.textContent = "00:00";
            modalBestTimeEl.textContent = bestTime
              ? formatTime(bestTime)
              : "--:--";
            resultsModal.classList.remove("hidden");
          }
        });

        continueBtn.addEventListener("click", function () {
          resultsModal.classList.add("hidden");

          // Resume timer if game isn't completed
          if (!completed) {
            startTime = Date.now() - elapsedTime;
            timerInterval = setInterval(updateTimer, 100);
            gameStarted = true;
          }
        });

        newGameBtn.addEventListener("click", function () {
          resultsModal.classList.add("hidden");
          startBtn.disabled = false;
          startBtn.classList.remove("opacity-50", "cursor-not-allowed");
          gameStarted = false;

          // Reset the board
          generateNumbers();
          renderBoard();
          nextNumber = 1;
          foundNumbersEl.textContent = "0/81";
          progressBar.style.width = "0%";

          timerEl.textContent = "00:00";
          elapsedTime = 0;
        });

        // Initial render
        generateNumbers();
        renderBoard();
      });
    </script>
  </body>
</html>
