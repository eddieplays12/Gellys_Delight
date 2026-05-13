<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelly's Delights Admin Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            color: #333;
            padding: 1.5rem;
        }

        .login-panel {
            width: min(100%, 420px);
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.12);
        }

        h1 {
            color: #c85a47;
            font-size: 1.65rem;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: #666;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.4rem;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font: inherit;
        }

        .alert-error {
            background: #f8d7da;
            border-left: 4px solid #e74c3c;
            color: #721c24;
            padding: 0.9rem 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        button {
            width: 100%;
            border: 0;
            border-radius: 6px;
            background: #d4755f;
            color: white;
            cursor: pointer;
            font: inherit;
            font-weight: 600;
            padding: 0.85rem 1rem;
            margin-top: 0.5rem;
        }

        button:hover {
            background: #c85a47;
        }
    </style>
</head>
<body>
    <main class="login-panel">
        <h1>Admin Login</h1>
        <p class="subtitle">Sign in to manage Gelly's Delights.</p>

        @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="form-group">
                <label for="admin_id">Admin ID</label>
                <input id="admin_id" name="admin_id" type="text" value="{{ old('admin_id') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
            </div>

            <button type="submit">Login</button>
        </form>
    </main>
</body>
</html>
