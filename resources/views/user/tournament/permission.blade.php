<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Permission</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Hide toastr error title completely */
        #toast-container > .toast-error .toast-title,
        #toast-container > div > .toast-error .toast-title {
            display: none !important;
            height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            line-height: 0 !important;
        }

        /* Adjust message position when title is hidden */
        #toast-container > .toast-error .toast-message {
            margin-top: 0 !important;
            padding-top: 15px !important;
        }
    </style>
</head>

<body class="bg-gray-900 text-white">

    <!-- Header -->
    <div class="text-center mb-8 p-4 space-y-4">
        <h1 class="text-4xl font-bold text-blue-400">
            Hello {{ Auth::user()->username }}!
        </h1>
        <p class="text-xl text-gray-300">Submit a request to get permission or approval</p>
    </div>

    <!-- Permission Request Form -->
    <div
        class="max-w-3xl mx-auto bg-gray-800 bg-opacity-50 backdrop-blur-lg rounded-2xl p-8 border border-gray-700 shadow-lg">
        <form action="" method="POST" class="space-y-6">

            <!-- Request Title -->
            <div>
                <label for="title" class="block text-sm font-semibold text-blue-400 mb-2">Request for</label>
                <input type="text" id="title" name="title"
                    class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg p-3 focus:outline-none focus:border-blue-500"
                    value="{{ $tournament->name }}" required>
            </div>

            <!-- Request Status Badge -->
            <div class="flex items-center justify-between">
                <label class="block text-sm font-semibold text-blue-400">Request Status:</label>

                <span
                    class="
                    px-4 py-2 text-sm font-semibold rounded-full
                    @if ($status === 'Approved') bg-green-600 text-white
                    @elseif($status === 'Rejected')
                        bg-red-600 text-white
                    @else
                        bg-yellow-500 text-black @endif ">
                    {{ ucfirst($status) }}
                </span>
            </div>
            <!-- Submit Button -->
            <div class="text-center">
                <a href="{{ $status == 'Submit Request' ? url('request/submit', $tournament->id) : 'javascript:void(0)' }}"
                    class="font-semibold py-3 px-6 rounded-lg transition duration-300
        @if ($status == 'Submit Request') bg-blue-600 hover:bg-blue-700 text-white
        @else
            bg-gray-400 text-gray-200 cursor-not-allowed pointer-events-none @endif">
                    Submit Request
                </a>
            </div>

        </form>
    </div>

    <!-- Back to Dashboard -->
    <div class="text-center mt-8">
        <a href="{{ url('tournaments') }}"
            class="bg-gray-700 hover:bg-gray-800 text-white py-2 px-4 rounded-lg transition duration-300">
            ← Back to Tournaments
        </a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Override toastr's default error template to not include title
        if (typeof toastr !== 'undefined') {
            var originalError = toastr.error;
            toastr.error = function(message, title, options) {
                // Always pass null for title to prevent default "Error" title
                var opts = options || {};
                opts.onShown = function() {
                    // Remove title element after toast is shown
                    var $toast = $(this);
                    $toast.find('.toast-title').remove();
                    if (options && options.onShown) {
                        options.onShown.call(this);
                    }
                };
                return originalError.call(this, message, null, opts);
            };
        }
    </script>
    <!-- Include Pusher JS -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher('ae29d4284279ffb1f77e', {
            cluster: 'ap2'
        });

        var channel = pusher.subscribe('notify-user');

        channel.bind('my-event', function(data) {
            console.log('Received data:', data);

            // Check if success is true and user_id matches authenticated user
            const authUserId = {{ Auth::id() }};
            if (data.success && data.user_id == authUserId) {

                // ✅ Show success message from Pusher
                toastr.success(data.message, 'Success', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 4000,
                    positionClass: 'toast-top-right'
                });

                // ✅ Redirect if request accepted
                if (data.message && data.message.toLowerCase().includes('accepted')) {
                    setTimeout(() => {
                        window.location.href = "{{ route('waiting', $tournament->id) }}";
                    }, 2000);
                }

            } else {

                // ❌ Show error message from Pusher (no fallback)
                toastr.error(data.message, null, {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 4000,
                    positionClass: 'toast-top-right'
                });
            }

        });
    </script>

</body>

</html>
