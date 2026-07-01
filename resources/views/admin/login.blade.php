<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - Hero | Abans Auto Grand Draw</title>
    <style>
        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            min-height: 100dvh;
            background: #0a0000;
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
            background: #180505;
            border: 1px solid #4d0f0f;
            border-radius: 10px;
            box-shadow: 0 0 40px rgba(255, 0, 0, 0.15);
        }

        h1 {
            color: #fff;
            font-size: 22px;
            text-align: center;
            margin: 0 0 24px;
        }

        label {
            display: block;
            color: #d99;
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
            border: 1px solid #5a1414;
            background: #260808;
            color: #fff;
            font-size: 16px;
            margin-bottom: 18px;
        }

        input[type="password"]:focus {
            outline: none;
            border-color: #e11d1d;
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 6px;
            background: #e11d1d;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
        }

        button:hover {
            background: #c81616;
        }

        .error {
            background: rgba(225, 29, 29, 0.15);
            border: 1px solid #e11d1d;
            color: #ff9d9d;
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>Admin Login</h1>

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
