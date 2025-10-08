<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournament Waiting Room</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'game-blue': '#1e40af',
                        'game-purple': '#7c3aed',
                        'neon-green': '#10b981',
                        'neon-blue': '#3b82f6',
                    },
                    animation: {
                        'pulse-glow': 'pulse-glow 2s ease-in-out infinite alternate',
                        'float': 'float 3s ease-in-out infinite',
                        'gradient': 'gradient 3s ease infinite',
                    },
                    keyframes: {
                        'pulse-glow': {
                            '0%': {
                                boxShadow: '0 0 5px #3b82f6'
                            },
                            '100%': {
                                boxShadow: '0 0 20px #3b82f6, 0 0 30px #3b82f6'
                            }
                        },
                        'float': {
                            '0%, 100%': {
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-10px)'
                            }
                        },
                        'gradient': {
                            '0%, 100%': {
                                backgroundPosition: '0% 50%'
                            },
                            '50%': {
                                backgroundPosition: '100% 50%'
                            }
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-900 min-h-screen text-white ">
    <!-- Animated Background -->
    <div
        class="absolute inset-0 bg-gradient-to-br from-blue-900 via-purple-900 to-gray-900 animate-gradient bg-[length:400%_400%]">
    </div>

    <!-- Floating Particles -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="particle absolute w-2 h-2 bg-blue-400 rounded-full animate-float"
            style="top: 20%; left: 10%; animation-delay: 0s;"></div>
        <div class="particle absolute w-1 h-1 bg-purple-400 rounded-full animate-float"
            style="top: 60%; left: 20%; animation-delay: 1s;"></div>
        <div class="particle absolute w-3 h-3 bg-green-400 rounded-full animate-float"
            style="top: 40%; left: 80%; animation-delay: 2s;"></div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
        <div class="max-w-4xl w-full">
            <!-- Tournament Header -->
            <div class="text-center mb-8 ">
                <h1
                    class="text-2xl md:text-6xl font-bold mb-4 bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400 bg-clip-text text-transparent animate-pulse-glow p-8 rounded-3xl">
                    Welcome {{ Auth::user()->username }}
                </h1>
                {{-- <p class="text-xl md:text-2xl text-gray-300">Get ready for the ultimate gaming experience!</p> --}}
            </div>

            <!-- Countdown Timer -->
            <div
                class="bg-gray-800 bg-opacity-50 backdrop-blur-lg rounded-3xl p-8 mb-8 border border-gray-700 shadow-2xl animate-pulse-glow">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-6 text-yellow-400">Tournament Starts In</h2>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="text-center">
                        <div
                            class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl p-4 border border-blue-500 shadow-lg">
                            <div id="days" class="text-3xl md:text-4xl font-bold text-white">00</div>
                            <div class="text-sm text-blue-200">DAYS</div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div
                            class="bg-gradient-to-br from-purple-600 to-purple-800 rounded-xl p-4 border border-purple-500 shadow-lg">
                            <div id="hours" class="text-3xl md:text-4xl font-bold text-white">00</div>
                            <div class="text-sm text-purple-200">HOURS</div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div
                            class="bg-gradient-to-br from-green-600 to-green-800 rounded-xl p-4 border border-green-500 shadow-lg">
                            <div id="minutes" class="text-3xl md:text-4xl font-bold text-white">00</div>
                            <div class="text-sm text-green-200">MINUTES</div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div
                            class="bg-gradient-to-br from-red-600 to-red-800 rounded-xl p-4 border border-red-500 shadow-lg">
                            <div id="seconds" class="text-3xl md:text-4xl font-bold text-white">00</div>
                            <div class="text-sm text-red-200">SECONDS</div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <div id="status-message" class="text-lg text-gray-300 mb-4">Please wait while we prepare the
                        tournament...</div>
                    <div class="w-full bg-gray-700 rounded-full h-3 mb-4">
                        <div id="progress-bar"
                            class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full transition-all duration-1000 ease-out"
                            style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Tournament Details -->
            <div class="bg-gray-800 bg-opacity-50 backdrop-blur-lg rounded-2xl p-6 border border-gray-700">
                <h3 class="text-xl font-bold mb-4 text-blue-400">Tournament Details</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Rounds:</span>
                        <span class="text-white font-medium">{{ $tournament->rounds }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Type:</span>
                        <span class="text-white font-medium">
                            @if ($tournament->elimination_type == 'all')
                                You can Play till end
                            @else
                                Eliminated by {{ $tournament->elimination_percent }} %
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Duration:</span>
                        <span class="text-white font-medium">
                            {{ Carbon\Carbon::parse($tournament->start_time)->format('h:i A') }} -
                            {{ Carbon\Carbon::parse($tournament->end_time)->format('h:i A') }}
                        </span>

                    </div>
                    {{-- <div class="flex justify-between">
                        <span class="text-gray-400">Format:</span>
                        <span class="text-white font-medium"></span>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <script>
        // Get server-provided strings safely (Blade)
        const dateStr = @json($tournament->date); // e.g. "2025-09-16"
        const timeStr = @json($tournament->start_time); // e.g. "11:00:00" or "11:00"

        // Elements
        const daysEl = document.getElementById('days');
        const hoursEl = document.getElementById('hours');
        const minutesEl = document.getElementById('minutes');
        const secondsEl = document.getElementById('seconds');
        const statusMessage = document.getElementById('status-message');
        const progressBar = document.getElementById('progress-bar');

        // Helper: build a local Date from YYYY-MM-DD and HH:MM[:SS]
        // This avoids ambiguous string parsing across browsers/timezones.
        function buildLocalDate(dateStr, timeStr) {
            if (!dateStr) return null;
            const dparts = dateStr.split('-').map(Number); // [YYYY, MM, DD]
            if (dparts.length < 3 || dparts.some(isNaN)) return null;
            const year = dparts[0];
            const monthIndex = (dparts[1] || 1) - 1;
            const day = dparts[2];

            let hour = 0,
                minute = 0,
                second = 0;
            if (timeStr) {
                const tparts = timeStr.split(':').map(Number);
                if (!isNaN(tparts[0])) hour = tparts[0];
                if (!isNaN(tparts[1])) minute = tparts[1];
                if (!isNaN(tparts[2])) second = tparts[2];
            }

            // Construct a local Date (client's timezone) for the given components
            return new Date(year, monthIndex, day, hour, minute, second);
        }

        // Create tournamentStart date
        const tournamentStart = buildLocalDate(dateStr, timeStr);

        console.log('Tournament date string:', dateStr, 'time string:', timeStr);
        console.log('Parsed tournamentStart (local):', tournamentStart, 'ts:', tournamentStart ? tournamentStart.getTime() :
            null);

        function formatElapsed(ms) {
            // For "Started Xh Ym ago"
            const totalSeconds = Math.floor(Math.abs(ms) / 1000);
            const days = Math.floor(totalSeconds / (3600 * 24));
            const hours = Math.floor((totalSeconds % (3600 * 24)) / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;
            if (days > 0) return `${days}d ${hours}h ${minutes}m`;
            if (hours > 0) return `${hours}h ${minutes}m`;
            if (minutes > 0) return `${minutes}m ${seconds}s`;
            return `${seconds}s`;
        }

        function clamp(n, min, max) {
            return Math.max(min, Math.min(max, n));
        }

        // Assuming $tournament->id is the tournament ID passed from the backend
        const tournamentId = @json($tournament->id);

        function updateCountdown() {
            if (!tournamentStart || isNaN(tournamentStart.getTime())) {
                statusMessage.textContent = '⚠️ Invalid tournament date/time';
                daysEl.textContent = hoursEl.textContent = minutesEl.textContent = secondsEl.textContent = '00';
                progressBar.style.width = '0%';
                return;
            }

            const now = Date.now();
            const startTs = tournamentStart.getTime();
            const timeLeft = startTs - now;

            if (timeLeft <= 0) {
                // Tournament already started (or exactly at start)
                daysEl.textContent = '00';
                hoursEl.textContent = '00';
                minutesEl.textContent = '00';
                secondsEl.textContent = '00';

                // Show how long ago it started
                const ago = formatElapsed(now - startTs);
                statusMessage.textContent = `✅ Tournament started ${ago} ago`;
                progressBar.style.width = '100%';

                // Redirect to the play route
                window.location.href = `/play-tournament/${tournamentId}`;

                return;
            }

            // Time breakdown
            const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            daysEl.textContent = String(days).padStart(2, '0');
            hoursEl.textContent = String(hours).padStart(2, '0');
            minutesEl.textContent = String(minutes).padStart(2, '0');
            secondsEl.textContent = String(seconds).padStart(2, '0');

            // Progress bar: relative to the last 24 hours before start
            const windowMs = 24 * 60 * 60 * 1000; // 24 hours
            const windowStart = startTs - windowMs;
            const elapsedInWindow = now - windowStart; // can be negative if >24h left
            const progress = clamp(elapsedInWindow / windowMs, 0, 1) * 100;
            progressBar.style.width = progress + '%';

            // Status messages by remaining time
            if (timeLeft > 2 * 60 * 60 * 1000) { // > 2 hours
                statusMessage.textContent = "⏳ Tournament coming soon, prepare yourself!";
            } else if (timeLeft > 30 * 1000) { // > 30 seconds
                statusMessage.textContent = "⚡ Final countdown starting soon!";
            } else {
                statusMessage.textContent = "🚀 Tournament is about to begin!";
            }
        }


        // run once immediately and then every second
        updateCountdown();
        const countdownInterval = setInterval(updateCountdown, 1000);
    </script>

</body>

</html>
