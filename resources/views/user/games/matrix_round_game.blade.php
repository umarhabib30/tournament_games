<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Number Sequence Challenge</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
            font-size: 0.75rem;
            /* smaller text */
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
    <div class="container max-w-4xl mx-auto p-4 md:p-6 bg-gray-800 rounded-xl shadow-2xl">
        <!-- Header -->
        <header class="text-center mb-6 md:mb-8">
            <h1
                class="text-2xl md:text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500 pulse">
                Number Sequence Challenge
            </h1>
            <p class="text-gray-400 mt-2 text-sm">
                Click numbers in ascending order from 1 to 81
            </p>

            <!-- Progress Bar -->
            <div class="mt-4 bg-gray-700 rounded-full h-2.5">
                <div id="progress-bar" class="progress-bar bg-blue-600 rounded-full h-2.5" style="width: 0%"></div>
            </div>
        </header>

        <!-- Game Stats -->
        <div class="flex justify-between items-center mb-6 bg-gray-900 p-4 rounded-lg stats-container">
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
                <div class="text-sm text-gray-400">Time Remaining</div>
                <div id="best-time" class="text-2xl font-bold text-yellow-400">
                   <div id="countdown"></div>
                </div>
            </div>
        </div>

        <!-- Game Board -->
        <div class="grid grid-cols-9 gap-1 mb-6 game-grid" id="game-board">
            <!-- Numbers will be generated here -->
        </div>

        <!-- Controls -->
        <div class="flex justify-center gap-4 mb-6">
            {{-- <button id="start-btn"
                class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition shadow-md">
                <i class="fas fa-play mr-2"></i>Start Game
            </button> --}}
            <button id="submit-btn"
                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition shadow-md">
                <i class="fas fa-check-circle mr-2"></i>Submit
            </button>
        </div>
        <form id="gameForm" action="{{ url('round/submit-score') }}" method="POST">
            @csrf
            <!-- your game inputs go here -->
            <input type="hidden" name="tournament_id" value="{{ $tournament->id }}" id="">
            <input type="hidden" name="game_id" value="{{ $game->id }}" id="">
            <input type="hidden" name="round_id" value="{{ $round->id }}" id="">
            <input type="hidden" name="score" value="0" id="scoreInput">
            <input type="hidden" name="time_taken" value="0" id="timeInput">

        </form>

        <!-- Instructions -->
        {{-- <div class="bg-gray-900 p-4 rounded-lg text-sm">
            <h3 class="font-medium text-gray-300 mb-2">How to Play:</h3>
            <ul class="list-disc list-inside text-gray-400 space-y-1">
                <li>Click numbers in ascending order from 1 to 81</li>
                <li>Correct numbers turn black and are disabled</li>
                <li>Wrong numbers will shake to indicate error</li>
                <li>Submit your result anytime to see your progress</li>
                <li>Try to beat your best time!</li>
            </ul>
        </div> --}}
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
                </div>

                <div class="flex justify-center gap-3">
                    <a id="continue-btn" href="{{ url('play-tournament', $tournament->id) }}"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        Continue
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // Game state
            let numbers = [];
            let nextNumber = 1;
            let timerInterval;
            let startTime;
            let elapsedTime = 0;
            let gameStarted = false;
            let completed = false;
            let bestTime = localStorage.getItem("bestTime") || null;

            // Initialize best time display
            if (bestTime) {
                $("#best-time").text(formatTime(bestTime));
                $("#modal-best-time").text(formatTime(bestTime));
            }

            function generateNumbers() {
                numbers = Array.from({
                    length: 81
                }, (_, i) => i + 1);
                for (let i = numbers.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [numbers[i], numbers[j]] = [numbers[j], numbers[i]];
                }
            }

            function renderBoard() {
                $("#game-board").empty();
                $.each(numbers, function(_, num) {
                    $("#game-board").append(
                        `<button class="number-btn bg-gray-700 text-white rounded text-xs" data-number="${num}">${num}</button>`
                    );
                });
            }

            function handleNumberClick(number, $button) {
                if (!gameStarted) return;
                if (number === nextNumber) {
                    $button.addClass("correct").prop("disabled", true);
                    nextNumber++;
                    updateProgress();
                    playSound("correct");
                    if (nextNumber > 81) {
                        completed = true;
                        endGame(true);
                    }
                } else {
                    $button.addClass("wrong");
                    playSound("wrong");
                    setTimeout(() => $button.removeClass("wrong"), 500);
                }
            }

            function updateProgress() {
                const found = nextNumber - 1;
                $("#found-numbers").text(`${found}/81`);

                const progress = (found / 81) * 100;
                $("#progress-bar").css("width", `${progress}%`);

                if (progress < 70) {
                    $("#progress-bar").removeClass("bg-green-600").addClass("bg-blue-600");
                } else {
                    $("#progress-bar").removeClass("bg-blue-600").addClass("bg-green-600");
                }
            }

            function startGame() {
                generateNumbers();
                renderBoard();
                nextNumber = 1;
                elapsedTime = 0;
                $("#timer").text("00:00");
                $("#found-numbers").text("0/81");
                $("#progress-bar").css("width", "0%").removeClass("bg-green-600").addClass("bg-blue-600");
                $("#start-btn").prop("disabled", true).addClass("opacity-50 cursor-not-allowed");
                startTime = Date.now() - elapsedTime;
                timerInterval = setInterval(updateTimer, 100);
                gameStarted = true;
                completed = false;
            }

            function updateTimer() {
                elapsedTime = Date.now() - startTime;
                $("#timer").text(formatTime(elapsedTime));
                if (elapsedTime >= 600000) {
                    clearInterval(timerInterval);
                    $("#timer").text("10:00").addClass("text-red-400");
                }
            }

            function formatTime(ms) {
                const time = typeof ms === "string" ? parseInt(ms) : ms;
                const totalSeconds = Math.floor(time / 1000);
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;
                return `${minutes.toString().padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
            }

            function endGame(isComplete = false) {
                clearInterval(timerInterval);
                gameStarted = false;

                if (isComplete) {
                    $("#result-title").text("Congratulations!");
                    $("#result-message").text("You completed the challenge!");
                    $("#result-icon").attr("class", "fas fa-trophy text-yellow-400");
                } else {
                    $("#result-title").text("Game Progress");
                    $("#result-message").text("Your current progress:");
                    $("#result-icon").attr("class", "fas fa-chart-simple text-blue-400");
                }

                $("#result-numbers").text(`${nextNumber - 1}/81 numbers`);
                $("#final-time").text(formatTime(elapsedTime));

                $('#scoreInput').val(nextNumber - 1);
                $('#timeInput').val(Math.floor(elapsedTime / 1000));

                $.ajax({
                    url: "{{ url('round/submit-score') }}",
                    method: "POST",
                    data: $("#gameForm").serialize(),
                    success: function(response) {
                        console.log("Score submitted successfully");
                    },
                    error: function() {
                        console.error("Error submitting score");
                    }
                });
                $("#results-modal").removeClass("hidden");
            }

            function playSound(type) {
                try {
                    const audioContext = new(window.AudioContext || window.webkitAudioContext)();
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();

                    if (type === "correct") {
                        oscillator.type = "sine";
                        oscillator.frequency.setValueAtTime(523.25, audioContext.currentTime);
                        oscillator.frequency.setValueAtTime(659.25, audioContext.currentTime + 0.1);
                        gainNode.gain.setValueAtTime(0.5, audioContext.currentTime);
                        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
                    } else {
                        oscillator.type = "sawtooth";
                        oscillator.frequency.setValueAtTime(150, audioContext.currentTime);
                        gainNode.gain.setValueAtTime(0.5, audioContext.currentTime);
                        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
                    }
                    oscillator.connect(gainNode).connect(audioContext.destination);
                    oscillator.start();
                    oscillator.stop(audioContext.currentTime + (type === "correct" ? 0.3 : 0.5));
                } catch (e) {}
            }

            // Delegated Events
            $('body').on('click', '#start-btn', startGame);
            $('body').on('click', '#submit-btn', () => gameStarted ? endGame(completed) : showStartWarning());
            $('body').on('click', '#continue-btn', continueGame);
            $('body').on('click', '#new-game-btn', newGame);
            $('body').on('click', '.number-btn', function() {
                handleNumberClick(parseInt($(this).data('number')), $(this));
            });

            function showStartWarning() {
                $("#result-title").text("Game Not Started");
                $("#result-message").text("Please start the game first!");
                $("#result-icon").attr("class", "fas fa-info-circle text-blue-400");
                $("#result-numbers").text("0/81 numbers");
                $("#final-time").text("00:00");
                $("#results-modal").removeClass("hidden");
            }

            function continueGame() {
                $("#results-modal").addClass("hidden");
                if (!completed) {
                    startTime = Date.now() - elapsedTime;
                    timerInterval = setInterval(updateTimer, 100);
                    gameStarted = true;
                }
            }

            function newGame() {
                $("#results-modal").addClass("hidden");
                $("#start-btn").prop("disabled", false).removeClass("opacity-50 cursor-not-allowed");
                gameStarted = false;
                generateNumbers();
                renderBoard();
                nextNumber = 1;
                $("#found-numbers").text("0/81");
                $("#progress-bar").css("width", "0%");
                $("#timer").text("00:00");
                elapsedTime = 0;
            }


            const serverNow = {{ $serverNow }} * 1000;
            const endTime = {{ $endtime }} * 1000;
            const form = document.getElementById('gameForm');
            const countdownEl = document.getElementById('countdown');

            console.log("Server Now (raw):", {{ $serverNow }});
            console.log("End Time (raw):", {{ $endtime }});
            console.log("Server Now (ms):", {{ $serverNow }} * 1000);
            console.log("End Time (ms):", {{ $endtime }} * 1000);

            // 1️⃣ Capture initial time drift between client and server
            const drift = Date.now() - serverNow;

            function updateCountdownTimer() {
                const currentClientTime = Date.now();
                const correctedTime = currentClientTime - drift; // keeps ticking correctly

                const remaining = endTime - correctedTime;

                if (remaining <= 0) {
                    countdownEl.innerText = "00:00";
                    endGame(completed);
                    // form.submit();
                } else {
                    const minutes = Math.floor((remaining / 1000) / 60);
                    const seconds = Math.floor((remaining / 1000) % 60);
                    countdownEl.innerText = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                    requestAnimationFrame(updateCountdownTimer);
                }
            }

            updateCountdownTimer();


            // Auto start
            generateNumbers();
            renderBoard();
            startGame();
        });
    </script>


</body>

</html>
