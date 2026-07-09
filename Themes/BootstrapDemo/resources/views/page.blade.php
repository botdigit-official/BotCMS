<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->title }} - {{ $site->name }}</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0b0f19;
            color: #f1f5f9;
        }
        .premium-container {
            background-color: rgba(17, 24, 39, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1rem;
        }
    </style>
</head>
<body class="d-flex flex-column h-100">
    
    <!-- Top Header -->
    <header class="border-bottom border-secondary bg-dark py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="/" class="fs-4 fw-bold text-info text-decoration-none">
                {{ $site->name }}
            </a>
            <div class="d-flex align-items-center gap-3">
                <a href="/shop" class="text-secondary text-decoration-none small fw-semibold hover:text-white">Shop</a>
                <a href="/cart" class="text-secondary text-decoration-none small fw-semibold hover:text-white">Cart</a>
                <a href="{{ route('login') }}" class="btn btn-info btn-sm fw-bold">
                    Admin Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Body -->
    <main class="flex-shrink-0 container my-auto py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 premium-container p-5">
                <h1 class="text-white border-bottom border-secondary pb-3 mb-4">
                    {{ $page->title }}
                </h1>
                <div class="text-muted leading-relaxed">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-top border-secondary py-3 bg-dark mt-auto">
        <div class="container text-center text-muted small">
            <p class="mb-0">&copy; {{ date('Y') }} {{ $site->name }}. Rendered using BootstrapDemo Theme.</p>
        </div>
    </footer>
</body>
</html>
