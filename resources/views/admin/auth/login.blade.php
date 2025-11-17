<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link href="{{ asset('assets/vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            font-family: 'Circular Std', sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            overflow: hidden;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }

        /* Floating particles */
        body::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="1.5" fill="rgba(255,255,255,0.15)"><animate attributeName="cy" values="20;80;20" dur="8s" repeatCount="indefinite"/></circle><circle cx="70" cy="60" r="2" fill="rgba(255,255,255,0.15)"><animate attributeName="cy" values="60;30;60" dur="12s" repeatCount="indefinite"/></circle><circle cx="50" cy="40" r="1" fill="rgba(255,255,255,0.15)"><animate attributeName="cy" values="40;70;40" dur="10s" repeatCount="indefinite"/></circle></svg>') repeat;
            pointer-events: none;
            z-index: 0;
        }

        .splash-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
            animation: fadeInUp 1s ease forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 2;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
        }

        .card-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-align: center;
            padding: 2rem 1rem;
            font-weight: bold;
            font-size: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .card-header:hover::before {
            left: 100%;
        }

        .card-header .splash-description {
            display: block;
            font-size: 0.9rem;
            font-weight: 400;
            margin-top: 5px;
            color: rgba(255, 255, 255, 0.85);
        }

        .card-body {
            padding: 2rem;
        }

        .form-control {
            height: 50px;
            border-radius: 12px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 10px 15px;
            font-size: 16px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 12px;
            height: 50px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .card-footer {
            display: flex;
            background: transparent;
            border-top: none;
            padding: 0;
        }

        .card-footer-item {
            flex: 1;
            text-align: center;
            padding: 12px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card-footer-item-bordered {
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }

        .footer-link {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .footer-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #fff;
            transition: width 0.3s ease;
        }

        .footer-link:hover::after {
            width: 100%;
        }

        .custom-checkbox .custom-control-label::before {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
            background: #667eea;
            border-color: #667eea;
        }

        .custom-checkbox .custom-control-label {
            color: #fff;
            font-size: 14px;
            margin-left: 8px;
        }

        @media (max-width: 480px) {
            .card-body {
                padding: 1.5rem;
            }

            .card-header {
                font-size: 1.25rem;
                padding: 1.5rem 1rem;
            }

            .btn-primary {
                height: 45px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="splash-container">
        <div class="card ">
            <div class="card-header text-center">
                The Genius Arena
                <span class="splash-description">Please enter your user information.</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.authenticate') }}">
                    @csrf
                    <div class="form-group">
                        <input id="email" type="email"
                            class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password"
                            required autocomplete="current-password">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox"><span
                                class="custom-control-label">Remember Me</span>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
                </form>
            </div>
            {{-- <div class="card-footer bg-blue p-0">
                <div class="card-footer-item card-footer-item-bordered">
                    <a href="#" class="footer-link ">Create An Account</a></div>
                <div class="card-footer-item card-footer-item-bordered">
                    <a href="#" class="footer-link">Forgot Password</a>
                </div>
            </div> --}}
        </div>
    </div>

    <!-- Optional JavaScript -->
    <script src="{{ asset('assets/vendor/jquery/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.js') }}"></script>
</body>

</html>
