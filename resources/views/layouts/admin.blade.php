<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link href="{{ asset('assets/vendor/fonts/circular-std/style.css" rel="stylesheet') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/charts/chartist-bundle/chartist.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/charts/morris-bundle/morris.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/charts/c3charts/c3.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icon-css/flag-icon.min.css') }}">

    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/css/buttons.bootstrap4.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables/css/select.bootstrap4.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/vendor/datatables/css/fixedHeader.bootstrap4.css') }}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/admin-theme.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Admin Dashboard</title>

    @yield('style')

</head>

<body>



    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
        <!-- ============================================================== -->
        <!-- navbar -->
        <!-- ============================================================== -->
        <div class="dashboard-header">
            <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top admin-top-navbar">
                <div class="admin-navbar-inner">
                    <a class="navbar-brand admin-navbar-brand" href="{{ url('admin/dashboard') }}">
                        <span class="brand-full">The Genius Arena</span>
                        <span class="brand-short">Genius Arena</span>
                    </a>

                    <ul class="navbar-nav navbar-right-top admin-navbar-actions align-items-center flex-row ml-auto">
                        <li class="nav-item dropdown notification">
                            <a class="nav-link nav-icons" href="#" id="navbarDropdownMenuLink1"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    class="fas fa-fw fa-bell"></i> <span class="indicator" id="notification-indicator"></span></a>
                            <ul class="dropdown-menu dropdown-menu-right notification-dropdown">
                                <li>
                                    <div class="notification-title"> Notification</div>
                                    <div class="notification-list">
                                        <div class="list-group">
                                            {{-- append permission requests here dynamically --}}
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="list-footer">
                                        <a href="{{ route('requests.pending') }}">View all requests</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown nav-user">
                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                                    src="{{ asset('assets/images/avatar-1.jpg') }}" alt=""
                                    class="user-avatar-md rounded-circle"></a>
                            <div class="dropdown-menu dropdown-menu-right nav-user-dropdown"
                                aria-labelledby="navbarDropdownMenuLink2">
                                <div class="nav-user-info">
                                    <h5 class="mb-0 text-white nav-user-name">Admin </h5>
                                </div>
                                <a class="dropdown-item" href="{{ route('admin.logout') }}"><i
                                        class="fas fa-power-off mr-2"></i>Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- ============================================================== -->
        <!-- end navbar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- left sidebar -->
        <!-- ============================================================== -->
        <div class="nav-left-sidebar sidebar-dark">
            <div class="menu-list">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="d-xl-none d-lg-none" href="#">Dashboard</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav flex-column">
                            <li class="nav-divider">
                                Menu
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link @if (($active ?? '') == 'dashboard') active @endif"
                                    href="{{ url('admin/dashboard') }}">
                                    <i class="fas fa-fw fa-tachometer-alt"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if ($active == 'game') active @endif" href="#"
                                    data-toggle="collapse" aria-expanded="false" data-target="#submenu-2"
                                    aria-controls="submenu-2">
                                    <i class="fas fa-fw fa-gamepad"></i> Games
                                </a>
                                <div id="submenu-2" class="collapse submenu" style="">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('admin.game.create') }}">Add</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('admin.game') }}">View All</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link collapsed @if ($active == 'tournament') active @endif"
                                    href="#" data-toggle="collapse" aria-expanded="false"
                                    data-target="#submenu-5" aria-controls="submenu-5">
                                    <i class="fas fa-fw fa-trophy"></i> Tournaments
                                </a>
                                <div id="submenu-5" class="submenu collapse" style="">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('admin.tournament.create') }}">Add</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('admin.tournament') }}">View All</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link collapsed @if ($active == 'requests') active @endif"
                                    href="#" data-toggle="collapse" aria-expanded="false"
                                    data-target="#submenu-6" aria-controls="submenu-6">
                                    <i class="fas fa-fw fa-user-check"></i> Requests
                                </a>
                                <div id="submenu-6" class="submenu collapse" style="">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('requests.pending') }}">Pending</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('requests.approved') }}">Approved</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('requests.rejected') }}">Rejected</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end left sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper">
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content ">

                    <!-- ============================================================== -->
                    <!-- pageheader  -->
                    <!-- ============================================================== -->

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header d-flex flex-wrap justify-content-between align-items-end">
                                <div class="mb-2">
                                    <h1 class="admin-page-title">{{ $heading ?? 'Admin' }}</h1>
                                    @if (!empty($title) && $title !== ($heading ?? ''))
                                        <p class="admin-page-subtitle">{{ $title }}</p>
                                    @endif
                                </div>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item">
                                            <a href="{{ url('admin/dashboard') }}" class="breadcrumb-link">Dashboard</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            {{ $heading ?? 'Page' }}
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>

                    @yield('content')
                    <!-- ============================================================== -->
                    <!-- end pageheader  -->
                    <!-- ============================================================== -->

                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <!-- jquery 3.3.1 -->
    <script src="{{ asset('assets/vendor/jquery/jquery-3.3.1.min.js') }}"></script>
    <!-- bootstap bundle js -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <!-- slimscroll js -->
    <script src="{{ asset('assets/vendor/slimscroll/jquery.slimscroll.js') }}"></script>
    <!-- main js -->
    <script src="{{ asset('assets/libs/js/main-js.js') }}"></script>
    <!-- chart chartist js -->
    <script src="{{ asset('assets/vendor/charts/chartist-bundle/chartist.min.js') }}"></script>
    <!-- sparkline js -->
    <script src="{{ asset('assets/vendor/charts/sparkline/jquery.sparkline.js') }}"></script>
    <!-- morris js -->
    <script src="{{ asset('assets/vendor/charts/morris-bundle/raphael.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/charts/morris-bundle/morris.js') }}"></script>
    <!-- chart c3 js -->
    <script src="{{ asset('assets/vendor/charts/c3charts/c3.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/charts/c3charts/d3-5.4.0.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/charts/c3charts/C3chartjs.js') }}"></script>
    <script src="{{ asset('assets/libs/js/dashboard-ecommerce.js') }}"></script>



    <script src="{{ asset('assets/vendor/multi-select/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/js/data-table.js') }}"></script>
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js') }}"></script>
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js') }}"></script>
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/rowgroup/1.0.4/js/dataTables.rowGroup.min.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    @php
        $adminFlash = array_filter([
            'success' => session('success'),
            'error' => session('error'),
            'warning' => session('warning'),
            'info' => session('info'),
        ]);
    @endphp
    <script>
        window.adminFlash = @json($adminFlash);
    </script>
    <script src="{{ asset('assets/js/admin-ui.js') }}"></script>


    <!-- Include Pusher JS -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher('ae29d4284279ffb1f77e', {
            cluster: 'ap2'
        });

        var channel = pusher.subscribe('permission');

        channel.bind('my-event', function(data) {
            console.log('Received data:', data);
            let message = (data.username || 'Unknown User') + " requested for " + (data.message || 'a tournament');

            // Show popup
            toastr.info(message, 'New Permission Request');

            // Play a subtle notification sound (unlocked after any user interaction)
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (AudioContext) {
                    const ctx = new AudioContext();
                    const duration = 0.15;
                    const oscillator = ctx.createOscillator();
                    const gainNode = ctx.createGain();

                    oscillator.type = 'triangle';
                    oscillator.frequency.setValueAtTime(880, ctx.currentTime);
                    gainNode.gain.setValueAtTime(0.0001, ctx.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.3, ctx.currentTime + 0.02);
                    gainNode.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + duration);

                    oscillator.connect(gainNode);
                    gainNode.connect(ctx.destination);
                    oscillator.start();
                    oscillator.stop(ctx.currentTime + duration);
                }
            } catch (e) {
                console.warn('Audio notification failed', e);
            }

            // Append new notification item
            let newNotification = `
                    <a href="{{ url('admin/permission/request/pending') }}" class="list-group-item list-group-item-action active">
                        <div class="notification-info">
                            <div class="notification-list-user-img"><i class="fas fa-bell"></i></div>
                            <div class="notification-list-user-block">
                                <span class="notification-list-user-name">${data.username || 'Unknown User'} requested for</span> ${data.title || 'New Request'}
                            </div>
                        </div>
                    </a>
                `;

            const listGroup = document.querySelector('.notification-list .list-group');
            if (listGroup) {
                listGroup.insertAdjacentHTML('afterbegin', newNotification);
            }

            // Update bell indicator with count dot
            const indicator = document.getElementById('notification-indicator');
            if (indicator) {
                let current = parseInt(indicator.getAttribute('data-count') || '0', 10);
                current += 1;
                indicator.setAttribute('data-count', current);
                indicator.classList.add('active');
                indicator.textContent = current > 9 ? '9+' : current;
            }
        });
    </script>


    @yield('script')
</body>

</html>
