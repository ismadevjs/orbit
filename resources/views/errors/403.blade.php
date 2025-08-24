<!doctype html>
<html lang="en" class="remember-theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>403 - Forbidden</title>
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/codebase.min.css') }}">
    <script src="{{ asset('assets/js/setTheme.js') }}"></script>
</head>
<body>
    <div id="page-container" class="main-content-boxed">
        <main id="main-container">
            <div class="hero bg-body-extra-light">
                <div class="hero-inner">
                    <div class="content content-full">
                        <div class="py-4 text-center">
                            <div class="display-1 fw-bold text-danger">
                                <i class="fa fa-exclamation-triangle opacity-50 me-1"></i> 403
                            </div>
                            <h1 class="fw-bold mt-5 mb-2">Oops.. Access denied..</h1>
                            <h2 class="fs-4 fw-medium text-muted mb-5">You do not have permission to access this page.</h2>
                            <a class="btn btn-lg btn-alt-secondary" href="{{ url('/') }}">
                                <i class="fa fa-arrow-left opacity-50 me-1"></i> Back to Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="{{ asset('assets/js/codebase.app.min.js') }}"></script>
</body>
</html>
