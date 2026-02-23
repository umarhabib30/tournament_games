<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Color-Word Match Game</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />
    <style>
        :root {
            --ring: 0 0% 100%;
        }

        body {
            font-family: "Inter", system-ui, -apple-system, Segoe UI, Roboto, Arial,
                Noto Sans, "Helvetica Neue", sans-serif;
        }

        .tile-enter {
            transform: scale(0.9);
            opacity: 0;
        }

        .tile-enter-active {
            transform: scale(1);
            opacity: 1;
            transition: all 200ms ease;
        }

        .pop {
            animation: pop 0.2s ease;
        }

        @keyframes pop {
            from {
                transform: scale(0.96);
            }

            to {
                transform: scale(1);
            }
        }

        .modal-show {
            animation: fadeIn 0.18s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body
    class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-950 to-black text-slate-100 selection:bg-indigo-500/30">
    <!-- HIDE UI UNTIL BOARD BUILDS -->
    <div id="gameWrapper" class="opacity-0 transition-opacity duration-300 max-w-6xl mx-auto px-4 py-8 md:py-12">
        <!-- Header -->
        <header class="flex flex-col md:flex-row items-center justify-between gap-6 md:gap-4 mb-6 md:mb-8">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                    Color-Word Match
                </h1>
                <p class="text-slate-300 mt-1">
                    Select only the words where
                    <span class="font-semibold">text</span> equals the
                    <span class="font-semibold">text color</span>.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button id="submitBtn"
                    class="rounded-2xl px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 active:bg-emerald-600 text-white font-semibold shadow-lg shadow-emerald-500/20 focus:outline-none focus:ring-4 focus:ring-emerald-500/40 transition">
                    Submit
                </button>
            </div>
        </header>

        <!-- Info Bar -->
        <section class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-6">
            <div class="rounded-2xl bg-slate-800/60 border border-slate-700 p-3">
                <div class="text-xs text-slate-400">Timer</div>
                <div id="timer" class="text-xl font-bold tabular-nums">01:00</div>
            </div>
            <div class="rounded-2xl bg-slate-800/60 border border-slate-700 p-3">
                <div class="text-xs text-slate-400">Correct Selected</div>
                <div id="liveCorrect" class="text-xl font-bold">0</div>
            </div>
            <div class="rounded-2xl bg-slate-800/60 border border-slate-700 p-3">
                <div class="text-xs text-slate-400">Wrong Selected</div>
                <div id="liveWrong" class="text-xl font-bold">0</div>
            </div>
        </section>

        <!-- Board -->
        <main id="board" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-4">
        </main>
    </div>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="modal-show w-full max-w-lg rounded-3xl bg-slate-900 border border-slate-700 shadow-2xl">
            <div class="p-6 md:p-8">
                <div class="flex items-start justify-between gap-4">
                    <h2 class="text-2xl md:text-3xl font-extrabold">Your Results</h2>
                    <button id="closeModal" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700">
                        ✕
                    </button>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl bg-slate-800/60 border p-4">
                        <div class="text-xs text-slate-400">Correct Selected</div>
                        <div id="rCorrect" class="text-2xl font-bold"></div>
                    </div>
                    <div class="rounded-2xl bg-slate-800/60 border p-4">
                        <div class="text-xs text-slate-400">Wrong Selected</div>
                        <div id="rWrong" class="text-2xl font-bold"></div>
                    </div>
                    <div class="rounded-2xl bg-slate-800/60 border p-4">
                        <div class="text-xs text-slate-400">Total Correct On Board</div>
                        <div id="rTotal" class="text-2xl font-bold"></div>
                    </div>
                    <div class="rounded-2xl bg-slate-800/60 border p-4">
                        <div class="text-xs text-slate-400">Time Taken</div>
                        <div id="rTime" class="text-2xl font-bold"></div>
                    </div>
                </div>

                <div class="mt-6 p-4 rounded-2xl bg-indigo-500/10 border">
                    <p id="rScoreText" class="text-lg font-semibold"></p>
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <button id="closeBtn"
                        class="rounded-2xl px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-semibold border border-slate-700 focus:outline-none focus:ring-4 focus:ring-slate-600/40 transition">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
<script>
    const COLORS = [{
    name: "Red",
    value: "#b91c1c"
    }, // deep red
    {
    name: "Green",
    value: "#22ff88"
    }, // cyber green
    {
    name: "Blue",
    value: "#1e40af"
    },
    {
    name: "Yellow",
    value: "#ffd84d"
    }, // warm glow yellow
    {
    name: "Pink",
    value: "#f472b6"
    },
    {
    name: "Purple",
    value: "#7c3aed"
    } // slightly brighter, still classy
    ];

    const WORD_COUNT = 100;
    const CORRECT_COUNT = 20;

    const board = document.getElementById("board");
    const gameWrapper = document.getElementById("gameWrapper");
    const submitBtn = document.getElementById("submitBtn");
    const timerEl = document.getElementById("timer");
    const liveCorrectEl = document.getElementById("liveCorrect");
    const liveWrongEl = document.getElementById("liveWrong");
    const modal = document.getElementById("modal");
    const closeModal = document.getElementById("closeModal");
    const closeBtn = document.getElementById("closeBtn");
    const rCorrect = document.getElementById("rCorrect");
    const rWrong = document.getElementById("rWrong");
    const rTotal = document.getElementById("rTotal");
    const rTime = document.getElementById("rTime");
    const rScoreText = document.getElementById("rScoreText");

    let gameData = [];
    let selected = new Set();
    let playing = false;
    let startTime = 0;
    let tickInterval = null;

    function playClickSound() {
    try {
    const audioContext = new(window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    oscillator.frequency.value = 800;
    oscillator.type = "sine";
    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.1);
    } catch (e) {}
    }

    function randInt(n) {
    return Math.floor(Math.random() * n);
    }

    function shuffle(arr) {
    for (let i = arr.length - 1; i > 0; i--) {
    const j = randInt(i + 1);
    [arr[i], arr[j]] = [arr[j], arr[i]];
    }
    return arr;
    }

    // Updated Format function to handle counting up
    function fmt(ms) {
    const totalSeconds = Math.floor(ms / 1000);
    const m = String(Math.floor(totalSeconds / 60)).padStart(2, "0");
    const s = String(totalSeconds % 60).padStart(2, "0");
    return `${m}:${s}`;
    }

    function buildRound() {
    gameData = [];
    for (let i = 0; i < CORRECT_COUNT; i++) { const col=COLORS[randInt(COLORS.length)]; gameData.push({ text: col.name,
        ink: col.value, isCorrect: true }); } for (let i=CORRECT_COUNT; i < WORD_COUNT; i++) { const
        txt=COLORS[randInt(COLORS.length)]; let ink; do { ink=COLORS[randInt(COLORS.length)]; } while
        (txt.name===ink.name); gameData.push({ text: txt.name, ink: ink.value, isCorrect: false }); } shuffle(gameData);
        } function renderBoard() { board.innerHTML = ""; gameData.forEach((w, i)=> {
        const btn = document.createElement("button");
        btn.className = "tile-enter group relative w-full aspect-[3/1] rounded-2xl border border-slate-700 bg-slate-800/60 hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-indigo-500/30 transition overflow-hidden";
        btn.setAttribute("data-idx", i);
        btn.innerHTML = `
        <div class="absolute inset-0 pointer-events-none opacity-0 group-[.selected]:opacity-100 transition">
            <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full bg-emerald-500/20 blur-xl"></div>
        </div>
        <div class="flex items-center justify-center h-full px-2 text-xl md:text-2xl font-extrabold select-none"
            style="color:${w.ink}">
            ${w.text}
        </div>
        `;
        btn.onclick = () => toggle(i, btn);
        board.appendChild(btn);
        requestAnimationFrame(() => btn.classList.add("tile-enter-active"));
        });
        }

        function toggle(i, el) {
        if (!playing || selected.has(i)) return;
        playClickSound();
        selected.add(i);
        el.classList.add("selected", "pop", "ring-4");
        if (gameData[i].isCorrect) {
        el.classList.add("ring-emerald-400/40", "border-emerald-400/50");
        } else {
        el.classList.add("ring-red-500/40", "border-red-500/50", "bg-red-500/20");
        }
        el.disabled = true;
        setTimeout(() => el.classList.remove("pop"), 200);

        let c = 0, w = 0;
        selected.forEach((x) => { gameData[x].isCorrect ? c++ : w++; });
        liveCorrectEl.textContent = c;
        liveWrongEl.textContent = w;
        }

        // Updated Timer Logic to count UP
        function startTimer() {
        startTime = Date.now();
        const updateTimer = () => {
        const elapsed = Date.now() - startTime;
        timerEl.textContent = fmt(elapsed);
        };
        updateTimer();
        clearInterval(tickInterval);
        tickInterval = setInterval(updateTimer, 250);
        }

        function startGame() {
        playing = true;
        selected.clear();
        board.classList.remove("pointer-events-none", "opacity-60");
        buildRound();
        renderBoard();
        startTimer();
        liveCorrectEl.textContent = "0";
        liveWrongEl.textContent = "0";
        submitBtn.classList.remove("hidden");
        gameWrapper.classList.remove("opacity-0");
        }

        function endGame() {
        playing = false;
        clearInterval(tickInterval);
        board.classList.add("pointer-events-none", "opacity-60");
        submitBtn.classList.add("hidden");
        }

        function submit() {
        if (!playing) return;
        const finalTime = Date.now() - startTime;
        endGame();

        let correctSel = 0, wrongSel = 0;
        selected.forEach((i) => { gameData[i].isCorrect ? correctSel++ : wrongSel++; });
        const totalCorrect = gameData.filter((x) => x.isCorrect).length;

        rCorrect.textContent = correctSel;
        rWrong.textContent = wrongSel;
        rTotal.textContent = totalCorrect;
        rTime.textContent = fmt(finalTime);
        rScoreText.textContent = `Score: ${correctSel} / ${totalCorrect} correct` + (wrongSel > 0 ? ` • ${wrongSel}
        wrong selections` : "");

        modal.classList.remove("hidden");
        modal.classList.add("flex");
        }

        submitBtn.addEventListener("click", submit);
        closeModal.addEventListener("click", () => {
        modal.classList.add("hidden");
        startGame(); // Restart the game when closed
        });
        closeBtn.addEventListener("click", () => {
        modal.classList.add("hidden");
        startGame(); // Restart the game when closed
        });

        document.addEventListener("keydown", (e) => {
        if (e.key === "Enter" && playing) submit();
        });

        window.addEventListener("load", startGame);
        </script>
</body>

</html>
