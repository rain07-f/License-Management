<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Enterprise License System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #4B49AC;
            --primary-light: #98BDFF;
        }

        body {
            background-color: #f4f7ff;
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .btn-primary {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            padding: 12px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #3a388f;
        }

        .form-control {
            padding: 12px;
            border-radius: 8px;
        }

        .brand-logo {
            color: var(--primary-dark);
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 30px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="brand-logo">LicenseServer</div>
        <h5 class="text-center mb-4 fw-bold">Sign In to Admin Panel</h5>

        @if($errors->any())
            <div class="alert alert-danger border-0 small rounded-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-medium">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="admin@license.com" required
                    value="{{ old('email') }}">
            </div>
            <div class="mb-4">
                <label class="form-label small fw-medium">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">Login to Dashboard</button>
        </form>

        <div class="text-center mt-3">
            <span class="text-muted small">Enterprise License Management System v1.0</span>
        </div>
    </div>
</body>

</html>