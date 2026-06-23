<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournament Access — {{ $tournament->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse-ring {
            0%, 100% { box-shadow: 0 0 0 0 rgba(234, 179, 8, 0.45); }
            50% { box-shadow: 0 0 0 12px rgba(234, 179, 8, 0); }
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .gradient-bg {
            background: linear-gradient(-45deg, #1a1a2e, #16213e, #0f3460, #533483);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .fade-in-up {
            animation: fadeInUp 0.55s ease-out forwards;
        }

        .btn-pending {
            animation: pulse-ring 2s ease-in-out infinite;
        }

        .spinner {
            animation: spin 0.9s linear infinite;
        }

        #toast-container > .toast-error .toast-title {
            display: none !important;
        }
    </style>
</head>

<body class="gradient-bg min-h-screen text-white">

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
    </div>

    <div class="relative max-w-lg mx-auto px-4 py-10 md:py-14">
        <header class="text-center mb-8 fade-in-up">
            <p class="text-gray-400 text-sm mb-2">Hello, <span class="text-blue-300 font-semibold">{{ Auth::user()->username }}</span></p>
            <h1 class="text-3xl md:text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400">
                Tournament Access
            </h1>
            <p class="text-gray-300 mt-3 text-sm md:text-base">This tournament requires admin approval before you can enter.</p>
        </header>

        <div class="glass-card rounded-2xl p-6 md:p-8 shadow-2xl fade-in-up space-y-6">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-blue-300 mb-2">Tournament</label>
                <div class="w-full bg-gray-900/70 border border-gray-700 rounded-xl px-4 py-3 text-lg font-semibold text-white">
                    {{ $tournament->name }}
                </div>
            </div>

            <div id="cta-wrapper">
                @if ($uiState === 'submit')
                    <a id="permission-cta" href="{{ route('request.permission.submit', $tournament->id) }}"
                        data-state="submit"
                        class="group flex flex-col items-center justify-center w-full rounded-xl py-5 px-6 font-bold text-lg transition-all duration-300 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white shadow-lg shadow-blue-900/40 hover:scale-[1.02]">
                        <span id="cta-primary" class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Submit Request
                        </span>
                        <span id="cta-secondary" class="text-xs font-medium text-blue-100/80 mt-1.5">Status: Not submitted yet</span>
                    </a>
                @elseif ($uiState === 'pending')
                    <button type="button" id="permission-cta" disabled data-state="pending"
                        class="btn-pending flex flex-col items-center justify-center w-full rounded-xl py-5 px-6 font-bold text-lg bg-gradient-to-r from-amber-500/90 to-yellow-500/90 text-gray-900 cursor-wait border border-amber-300/30">
                        <span id="cta-primary" class="flex items-center gap-2">
                            <svg class="w-5 h-5 spinner" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Pending Approval
                        </span>
                        <span id="cta-secondary" class="text-xs font-semibold text-amber-950/70 mt-1.5">Status: Waiting for admin review</span>
                    </button>
                @elseif ($uiState === 'accepted')
                    <a id="permission-cta" href="{{ route('waiting', $tournament->id) }}" data-state="accepted"
                        class="group flex flex-col items-center justify-center w-full rounded-xl py-5 px-6 font-bold text-lg transition-all duration-300 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 text-white shadow-lg shadow-emerald-900/40 hover:scale-[1.02]">
                        <span id="cta-primary" class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Enter Tournament
                        </span>
                        <span id="cta-secondary" class="text-xs font-medium text-emerald-100/80 mt-1.5">Status: Approved — you may enter now</span>
                    </a>
                @else
                    <button type="button" id="permission-cta" disabled data-state="rejected"
                        class="flex flex-col items-center justify-center w-full rounded-xl py-5 px-6 font-bold text-lg bg-gradient-to-r from-red-600/90 to-rose-600/90 text-white cursor-not-allowed border border-red-400/30">
                        <span id="cta-primary" class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Request Rejected
                        </span>
                        <span id="cta-secondary" class="text-xs font-medium text-red-100/80 mt-1.5">Status: Admin declined your request</span>
                    </button>
                @endif
            </div>

            <p id="status-hint" class="text-center text-sm text-gray-400">
                @if ($uiState === 'pending')
                    You will hear a sound and this button will update when the admin approves your request.
                @elseif ($uiState === 'accepted')
                    Your request was approved. Tap the button above to join the waiting area.
                @elseif ($uiState === 'rejected')
                    Contact the tournament admin if you believe this was a mistake.
                @else
                    Submit a request and an admin will review it shortly.
                @endif
            </p>
        </div>

        <div class="text-center mt-8 fade-in-up">
            <a href="{{ url('tournaments') }}"
                class="inline-flex items-center gap-2 text-gray-300 hover:text-white transition text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Tournaments
            </a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        const TOURNAMENT_ID = {{ $tournament->id }};
        const AUTH_USER_ID = {{ Auth::id() }};
        const WAITING_URL = @json(route('waiting', $tournament->id));
        const STATUS_URL = @json(route('request.permission.status', $tournament->id));

        let currentState = @json($uiState);
        let approvalNotified = false;
        let pollTimer = null;

        toastr.options = {
            closeButton: true,
            progressBar: true,
            timeOut: 5000,
            positionClass: 'toast-top-right',
        };

        @if (session('success'))
            toastr.success(@json(session('success')));
        @endif
        @if (session('error'))
            toastr.error(@json(session('error')), null);
        @endif

        function playApprovalSound() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const notes = [523.25, 659.25, 783.99];
                notes.forEach((freq, i) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(freq, ctx.currentTime + i * 0.12);
                    gain.gain.setValueAtTime(0.22, ctx.currentTime + i * 0.12);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + i * 0.12 + 0.35);
                    osc.start(ctx.currentTime + i * 0.12);
                    osc.stop(ctx.currentTime + i * 0.12 + 0.35);
                });
            } catch (e) {}
        }

        function parseBroadcastPayload(data) {
            if (!data) return {};
            if (typeof data === 'string') {
                try { data = JSON.parse(data); } catch (e) { return {}; }
            }
            if (data.data) {
                if (typeof data.data === 'string') {
                    try { return JSON.parse(data.data); } catch (e) { return {}; }
                }
                return data.data;
            }
            return data;
        }

        function setAcceptedUI() {
            const wrapper = document.getElementById('cta-wrapper');
            wrapper.innerHTML = `
                <a id="permission-cta" href="${WAITING_URL}" data-state="accepted"
                    class="group flex flex-col items-center justify-center w-full rounded-xl py-5 px-6 font-bold text-lg transition-all duration-300 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 text-white shadow-lg shadow-emerald-900/40 hover:scale-[1.02]">
                    <span id="cta-primary" class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Enter Tournament
                    </span>
                    <span id="cta-secondary" class="text-xs font-medium text-emerald-100/80 mt-1.5">Status: Approved — you may enter now</span>
                </a>`;
            document.getElementById('status-hint').textContent =
                'Your request was approved. Tap the button above to join the waiting area.';
            currentState = 'accepted';
        }

        function handleApproval(message) {
            if (approvalNotified || currentState === 'accepted') return;
            approvalNotified = true;

            playApprovalSound();
            setAcceptedUI();

            toastr.success(message || 'Your request has been approved! You can enter the tournament now.', 'Approved');

            if (pollTimer) {
                clearInterval(pollTimer);
                pollTimer = null;
            }
        }

        function checkPermissionStatus() {
            if (currentState !== 'pending') return;

            fetch(STATUS_URL, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'Accepted') {
                        handleApproval('Your request has been approved! You can enter the tournament now.');
                    } else if (data.status === 'Rejected' && currentState === 'pending') {
                        window.location.reload();
                    }
                })
                .catch(() => {});
        }

        if (currentState === 'pending') {
            document.addEventListener('click', function unlockAudio() {
                try {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();
                    if (ctx.state === 'suspended') ctx.resume();
                } catch (e) {}
            }, { once: true });

            pollTimer = setInterval(checkPermissionStatus, 4000);
            checkPermissionStatus();
        }

        try {
            const pusher = new Pusher('ae29d4284279ffb1f77e', { cluster: 'ap2' });
            const channel = pusher.subscribe('notify-user');

            channel.bind('my-event', function(raw) {
                const data = parseBroadcastPayload(raw);
                if (parseInt(data.user_id, 10) !== AUTH_USER_ID) return;

                if (data.success) {
                    handleApproval(data.message);
                } else {
                    toastr.error(data.message || 'Your request was not approved.', null);
                    if (currentState === 'pending') {
                        setTimeout(() => window.location.reload(), 2000);
                    }
                }
            });
        } catch (e) {
            console.warn('Pusher unavailable, using status polling only.');
        }
    </script>
</body>

</html>
