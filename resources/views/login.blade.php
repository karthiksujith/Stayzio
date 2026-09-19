<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        /* Navbar */
        .navbar{
            background: #0f172a;
            color: white;
            padding: 1px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0;
            color: white;
            text-decoration: none;
        }

        .brand img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .brand h2 {
            margin: 0;
            font-size: 24px;
        }

        .nav-links{
            display: flex;
            gap: 20px;
        }

        .nav-links a{
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .nav-links a:hover{
            color: #38bdf8;
        }

        .container {
            width: 400px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .tabs {
            display: flex;
            margin-bottom: 20px;
        }

        .tab-btn {
            flex: 1;
            padding: 12px;
            border: none;
            cursor: pointer;
            background: #ddd;
            font-weight: bold;
        }

        .tab-btn.active {
            background: #007bff;
            color: white;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background: #28a745;
            border: none;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }

        .hidden-role {
            display: none;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <a href="{{ route('home') }}" class="brand">
            <img src="{{ asset('images/logo.png') }}" alt="Stayzio Logo">
            <h2>Stayzio</h2>
        </a>

        <div class="nav-links">
            <a href="{{ route('home') }}">Home</a>
        </div>
    </div>

    <div class="container">
        <h2>Login</h2>

        @if(session('error'))
            <div class="message error">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="message success">{{ session('success') }}</div>
        @endif

        <div class="tabs">
            <button class="tab-btn active" onclick="setRole('user', event)">Login as User</button>
            <button class="tab-btn" onclick="setRole('host', event)">Login as Host</button>
        </div>

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf

            <input type="hidden" name="role" id="roleInput" value="user">

            <input type="email" name="email" placeholder="Enter Email" required>
            <input type="password" name="password" placeholder="Enter Password" required>

            <button type="submit">Login</button>
        </form>

        <div class="link">
            <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
        </div>
    </div>

<script>
    function setRole(role, event) {
        event.preventDefault();

        document.getElementById('roleInput').value = role;

        let buttons = document.querySelectorAll('.tab-btn');
        buttons.forEach(btn => btn.classList.remove('active'));

        event.target.classList.add('active');
    }
</script>

</body>
</html>