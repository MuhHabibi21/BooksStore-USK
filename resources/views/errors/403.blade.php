<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak (Forbidden) | BookStore</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            padding: 1.5rem;
        }
        .error-card {
            background-color: #1e293b;
            border: 1px solid #334155;
            border-radius: 1rem;
            max-width: 580px;
            width: 100%;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 8px 10px -6px rgba(0, 0, 0, 0.5);
        }
        .icon-circle {
            width: 90px;
            height: 90px;
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 2px solid rgba(239, 68, 68, 0.3);
        }
    </style>
</head>
<body>
    <div class="error-card p-4 p-md-5 text-center">
        <!-- Icon Peringatan Keamanan -->
        <div class="mb-4">
            <div class="icon-circle">
                <i class="bi bi-shield-x fs-1"></i>
            </div>
        </div>

        <span class="badge bg-danger px-3 py-2 text-uppercase fw-bold mb-2">
            <i class="bi bi-lock-fill me-1"></i> HTTP 403 Forbidden
        </span>

        <h2 class="fw-bold mb-3 mt-1">Akses Ditolak!</h2>

        <p class="text-secondary fs-6 mb-4">
            {{ $exception->getMessage() ?: 'Halaman ini merupakan area terproteksi khusus Administrator. Anda tidak memiliki izin atau belum login dengan akun yang memiliki hak akses admin.' }}
        </p>

        <div class="alert alert-dark border-secondary small text-start mb-4 py-3">
            <strong class="text-warning d-block mb-1">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> Catatan Keamanan Sistem:
            </strong>
            <span class="text-light opacity-75">
                Area <code>/admin/*</code> dilindungi oleh middleware otentikasi role. Setiap upaya akses tanpa identitas Administrator yang valid akan secara otomatis diblokir oleh sistem.
            </span>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
            <a href="{{ route('home') }}" class="btn btn-outline-light px-4 py-2">
                <i class="bi bi-house-door me-1"></i> Kembali ke Beranda
            </a>
            <a href="{{ route('login') }}" class="btn btn-danger px-4 py-2 fw-semibold">
                <i class="bi bi-box-arrow-in-right me-1"></i> Login sebagai Admin
            </a>
        </div>
    </div>
</body>
</html>
