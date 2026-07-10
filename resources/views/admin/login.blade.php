<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Flora Admin Panel - Login</title>
    <style>
        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            min-height: 100dvh;
            background: #f5f6f7;
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 360px;
            margin: 24px;
            padding: 32px 28px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        }

        h1 {
            color: #1a1a1a;
            font-size: 22px;
            text-align: center;
            margin: 0 0 24px;
        }

        label {
            display: block;
            color: #6b7280;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #1a1a1a;
            font-size: 16px;
            margin-bottom: 18px;
        }

        input[type="password"]:focus {
            outline: none;
            border-color: #009a4c;
            box-shadow: 0 0 0 3px rgba(0, 154, 76, 0.15);
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 6px;
            background: #009a4c;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
        }

        button:hover {
            background: #007a3d;
        }

        .error {
            background: #fef2f2;
            border: 1px solid #d1d5db;
            color: #1a1a1a;
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>Flora Admin Panel</h1>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <label for="password">Password</label>
            <input type="password" id="password" name="password" autofocus required>
            <button type="submit">Log In</button>
        </form>
    </div>
</body>
</html>
