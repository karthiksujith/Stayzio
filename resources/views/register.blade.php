<!DOCTYPE html>
<html>

<head>

    <title>Register</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }

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
            width: 420px;
            max-width: calc(100% - 40px);
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
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

        button[type="submit"]:hover {
            background: #218838;
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

        .error-list {
            margin: 0;
            padding-left: 20px;
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }

    </style>

</head>

<body>


    <div class="navbar">

        <a href="{{ route('home') }}" class="brand">
            <img src="{{ asset('images/logo.png') }}" alt="Stayzio Logo">
            <h2>Stayzio</h2>
        </a>

        <div class="nav-links">

            <a href="{{ route('home') }}">
                Home
            </a>

        </div>

    </div>


    <div class="container">

        <h2>Register</h2>


        @if($errors->any())

            <div class="message error">

                <ul class="error-list">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <div class="tabs">

            <button
                type="button"
                class="tab-btn active"
                onclick="setRole('user', event)"
            >
                Register as User
            </button>

            <button
                type="button"
                class="tab-btn"
                onclick="setRole('host', event)"
            >
                Register as Host
            </button>

        </div>


        <form
            action="{{ route('register.submit') }}"
            method="POST"
        >

            @csrf


            <input
                type="hidden"
                name="role"
                id="roleInput"
                value="user"
            >


            <input
                type="text"
                name="name"
                placeholder="Enter Name"
                value="{{ old('name') }}"
                required
            >


            <input
                type="email"
                name="email"
                placeholder="Enter Email"
                value="{{ old('email') }}"
                required
            >


            <input
                type="password"
                name="password"
                placeholder="Enter Password"
                required
            >


            <input
                type="password"
                name="password_confirmation"
                placeholder="Confirm Password"
                required
            >


            <button type="submit">
                Register
            </button>

        </form>


        <div class="link">

            <p>
                Already have an account?

                <a href="{{ route('login') }}">
                    Login here
                </a>
            </p>

        </div>

    </div>


    <script>

        function setRole(role, event)
        {
            event.preventDefault();


            document.getElementById(
                'roleInput'
            ).value = role;


            let buttons =
                document.querySelectorAll(
                    '.tab-btn'
                );


            buttons.forEach(
                function(btn)
                {
                    btn.classList.remove(
                        'active'
                    );
                }
            );


            event.target.classList.add(
                'active'
            );
        }

    </script>

</body>

</html>