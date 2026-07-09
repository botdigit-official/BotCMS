<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ apply_filters('botcms_homepage_title', 'BotCMS Site Frontend (Bootstrap)') }}</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0b0f19;
            color: #f1f5f9;
        }
        .premium-card {
            background-color: rgba(17, 24, 39, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1rem;
        }
    </style>
</head>
<body class="d-flex flex-column h-100">
    
    <!-- Top Nav -->
    <header class="border-bottom border-secondary bg-dark py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <span class="fs-4 fw-bold text-info">
                BotCMS Theme: BootstrapDemo
            </span>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-info-subtle text-info font-monospace uppercase">Bootstrap 5</span>
                <a href="{{ route('login') }}" class="btn btn-info btn-sm fw-bold shadow-sm">
                    Admin Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-shrink-0 container my-auto py-5 text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-extrabold text-white mb-4">
                    {!! apply_filters('botcms_homepage_welcome_text', 'Welcome to BotCMS Platform!') !!}
                </h1>
                <p class="lead text-muted mb-5">
                    This frontend is rendered dynamically using the <strong class="text-info">BootstrapDemo Theme</strong> built on <strong class="text-info">Bootstrap 5</strong>.
                </p>

                <div class="row g-4 justify-content-center text-start">
                    <div class="col-md-6">
                        <div class="premium-card p-4">
                            <span class="text-info font-monospace d-block mb-2 font-weight-bold">Dynamic Styling Demonstration</span>
                            <p class="small text-muted mb-0">
                                You can switch this entire page layout to Tailwind CSS by logging into the admin settings, changing the theme back to <strong>Default</strong>, and reloading.
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="premium-card p-4">
                            <span class="text-warning font-monospace d-block mb-2 font-weight-bold">Secure Hook Execution</span>
                            <p class="small text-muted mb-0">
                                The title and headers on this page are wrapped in WordPress-style filter hooks: <code class="text-warning">apply_filters()</code>.
                            </p>
                        </div>
                    </div>
                </div>

                @php do_action('botcms_homepage_content_footer'); @endphp
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-top border-secondary py-4 bg-dark mt-auto">
        <div class="container text-center text-muted small">
            <p class="mb-1">&copy; {{ date('Y') }} BotCMS Platform. Created with Laravel and Modern Architecture.</p>
            <p class="mb-0">Active Visual Theme: <span class="text-white fw-bold">BootstrapDemo</span> | Active Database Driver: <span class="text-white font-monospace">SQLite</span></p>
        </div>
    </footer>
</body>
</html>
