<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Stayzio - Home</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            color: #333;
        }


        /* =========================
           NAVBAR
        ========================= */

        .navbar {
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

        .brand h1 {
            margin: 0;
            font-size: 24px;
        }


        /* =========================
           NAV LINKS
        ========================= */

        .nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: bold;

            transition: 0.3s;

            padding: 10px 16px;
            border-radius: 8px;
        }

        .nav-links a:hover {
            color: #38bdf8;
        }


        /* =========================
           LOGIN / LOGOUT
        ========================= */

        .login-btn,
        .logout-btn {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 8px;

            padding: 10px 16px;

            border-radius: 8px;

            text-decoration: none;

            font-weight: bold;

            border: none;

            cursor: pointer;

            transition: background 0.3s ease;
        }

        .login-btn {
            background: #2563eb;
            color: white;
        }

        .login-btn:hover {
            background: #1d4ed8;
        }

        .logout-btn {
            background: #dc2626;
            color: white;
        }

        .logout-btn:hover {
            background: #b91c1c;
        }

        .welcome-text {
            color: white;

            font-weight: bold;

            font-size: 14px;

            padding: 10px 16px;

            border-radius: 8px;
        }


        /* =========================
           SEARCH BAR
        ========================= */

        .search-bar {
            max-width: 1000px;

            margin: 30px auto;

            background: white;

            padding: 15px 20px;

            border-radius: 50px;

            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .search-bar form {
            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            flex-wrap: wrap;
        }

        .search-item {
            display: flex;

            flex-direction: column;

            flex: 1;

            min-width: 150px;
        }

        .search-item label {
            font-size: 12px;

            font-weight: bold;

            color: #333;

            margin-bottom: 5px;
        }

        .search-item input {
            border: none;

            outline: none;

            font-size: 14px;

            background: transparent;
        }

        .search-btn {
            background: #ff385c;

            color: white;

            border: none;

            padding: 14px 25px;

            border-radius: 30px;

            cursor: pointer;

            font-weight: bold;

            font-size: 14px;
        }

        .search-btn:hover {
            background: #e03150;
        }


        /* =========================
           HERO
        ========================= */

        .hero {
            background:
                linear-gradient(
                    rgba(0, 0, 0, 0.4),
                    rgba(0, 0, 0, 0.4)
                ),
                url('/images/home.avif') center/cover no-repeat;

            height: 300px;

            display: flex;

            justify-content: center;

            align-items: center;

            text-align: center;

            color: white;

            padding: 20px;
        }

        .hero h2 {
            font-size: 38px;

            margin-bottom: 10px;
        }

        .hero p {
            font-size: 18px;
        }


        /* =========================
           SECTION
        ========================= */

        .section {
            padding: 40px 20px;

            max-width: 1200px;

            margin: auto;
        }

        .section-title {
            text-align: center;

            font-size: 32px;

            margin-bottom: 30px;

            color: #0f172a;
        }


        /* =========================
           HOTEL CARDS
        ========================= */

        .hotel-grid {
            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 20px;
        }

        .hotel-card {
            background: white;

            border-radius: 15px;

            overflow: hidden;

            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);

            transition:
                transform 0.3s ease,
                box-shadow 0.3s ease;
        }

        .hotel-card:hover {
            transform: translateY(-8px);

            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.15);
        }

        .hotel-card img {
            width: 100%;

            height: 220px;

            object-fit: cover;

            display: block;
        }

        .hotel-info {
            padding: 18px;
        }

        .hotel-info h3 {
            font-size: 22px;

            margin-bottom: 8px;

            color: #111827;
        }

        .hotel-info .location {
            font-size: 15px;

            color: #6b7280;

            margin-bottom: 10px;
        }

        .hotel-info .price {
            font-size: 20px;

            color: #16a34a;

            font-weight: bold;
        }

        .hotel-info .price span {
            font-size: 14px;

            color: #6b7280;

            font-weight: normal;
        }


        /* =========================
           DETAILS BUTTON
        ========================= */

        .details-btn {
            display: inline-block;

            margin-top: 12px;

            padding: 10px 18px;

            background: #2563eb;

            color: white;

            text-decoration: none;

            border-radius: 6px;

            font-weight: bold;

            transition: 0.3s;
        }

        .details-btn:hover {
            background: #1d4ed8;
        }


        /* =========================
           EMPTY MESSAGE
        ========================= */

        .empty {
            text-align: center;

            font-size: 18px;

            color: #6b7280;

            margin-top: 30px;
        }


        /* =========================
           CHAT BUBBLE
        ========================= */

        #chatBubble {
            position: fixed;

            bottom: 20px;

            right: 20px;

            width: 60px;

            height: 60px;

            background: #4f46e5;

            color: white;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 26px;

            cursor: pointer;

            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);

            z-index: 9999;
        }


        /* =========================
           CHAT BOX
        ========================= */

        #chatBox {
            position: fixed;

            bottom: 90px;

            right: 20px;

            width: 320px;

            height: 450px;

            background: #fff;

            border-radius: 15px;

            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);

            display: none;

            flex-direction: column;

            overflow: hidden;

            font-family: Arial;

            z-index: 9999;
        }


        /* =========================
           CHAT HEADER
        ========================= */

        #chatHeader {
            background: #4f46e5;

            color: white;

            padding: 10px;

            display: flex;

            justify-content: space-between;

            align-items: center;

            font-weight: bold;
        }


        /* =========================
           CHAT MESSAGES
        ========================= */

        #messages {
            flex: 1;

            padding: 10px;

            overflow-y: auto;

            background: #f9fafb;
        }

        .msg {
            margin: 5px 0;

            padding: 8px 10px;

            border-radius: 10px;

            max-width: 80%;
        }

        .user {
            background: #4f46e5;

            color: white;

            margin-left: auto;
        }

        .bot {
            background: #e5e7eb;

            color: #111;

            margin-right: auto;
        }


        /* =========================
           CHAT INPUT
        ========================= */

        #inputArea {
            display: flex;

            padding: 10px;

            border-top: 1px solid #ddd;
        }

        #userInput {
            flex: 1;

            padding: 8px;
        }

        #chatBox button {
            margin-left: 8px;

            background: #4f46e5;

            color: white;

            border: none;

            padding: 8px 12px;

            border-radius: 8px;

            cursor: pointer;
        }

        #typing {
            font-size: 12px;

            color: gray;

            padding: 5px 10px;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 992px) {

            .hotel-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .hero h2 {
                font-size: 30px;
            }

        }


        @media (max-width: 600px) {

            .hotel-grid {
                grid-template-columns: 1fr;
            }

            .navbar {
                flex-direction: column;

                gap: 10px;

                padding: 5px 20px;
            }

            .nav-links {
                flex-wrap: wrap;

                justify-content: center;
            }

            .hero {
                height: 220px;
            }

            .hero h2 {
                font-size: 24px;
            }

            .hero p {
                font-size: 15px;
            }

        }

    </style>

</head>


<body>


<!-- =========================
     NAVBAR
========================= -->

<div class="navbar">

    <a href="{{ route('home') }}" class="brand">

        <img
            src="{{ asset('images/logo.png') }}"
            alt="Stayzio Logo"
        >

        <h1>Stayzio</h1>

    </a>


    <div class="nav-links">

        <a href="{{ route('profile') }}">
            Profile
        </a>


        @auth

            <span class="welcome-text">

                {{ auth()->user()->name }}

                ({{ auth()->user()->role }})

            </span>


            <form
                action="{{ route('logout') }}"
                method="POST"
                style="display: inline;"
            >

                @csrf

                <button
                    type="submit"
                    class="logout-btn"
                >
                    Logout
                </button>

            </form>

        @else

            <a
                href="{{ route('login') }}"
                class="login-btn"
            >
                Login
            </a>

        @endauth

    </div>

</div>



<!-- =========================
     SEARCH BAR
========================= -->

<div class="search-bar">

    <form
        action="{{ route('properties.search') }}"
        method="GET"
    >

        <div class="search-item">

            <label>Where</label>

            <input
                type="text"
                name="location"
                placeholder="Search destinations"
            >

        </div>


        <div class="search-item">

            <label>Check in</label>

            <input
                type="date"
                min="{{ date('Y-m-d') }}"
                name="check_in"
            >

        </div>


        <div class="search-item">

            <label>Check out</label>

            <input
                type="date"
                min="{{ date('Y-m-d') }}"
                name="check_out"
            >

        </div>


        <div class="search-item">

            <label>Guests</label>

            <input
                type="number"
                name="guests"
                min="1"
                placeholder="Add guests"
            >

        </div>


        <button
            type="submit"
            class="search-btn"
        >
            Search
        </button>

    </form>

</div>



<!-- =========================
     HERO
========================= -->

<div class="hero">

    <div>

        <h2>
            Find Your Perfect Stay
        </h2>

        <p>
            Explore beautiful hotels and book your next trip easily
        </p>

    </div>

</div>



<!-- =========================
     HOTEL SECTION
========================= -->

<div class="section">

    <h2 class="section-title">
        Available Hotels
    </h2>


    @if(session('success'))

        <p
            style="
                text-align: center;
                color: green;
                margin-bottom: 20px;
            "
        >

            {{ session('success') }}

        </p>

    @endif


    @if($properties->count() > 0)

        <div
            class="hotel-grid"
            id="hotel-grid"
        >


            @foreach($properties as $property)

                <div
                    class="hotel-card"
                    data-location="{{ strtolower($property->location) }}"
                >


                    @if($property->image)

                        <img
                            src="{{ asset('images/' . $property->image) }}"
                            alt="{{ $property->title }}"
                        >

                    @else

                        <img
                            src="{{ asset('images/default-property.jpg') }}"
                            alt="No Image"
                        >

                    @endif


                    <div class="hotel-info">


                        <h3>
                            {{ $property->title }}
                        </h3>


                        <p class="location">
                            {{ $property->location }}
                        </p>


                        <p class="price">

                            ₹{{ $property->price }}

                            <span>
                                / night
                            </span>

                        </p>


                        <a
                            href="{{ route('property.show', $property->id) }}"
                            class="details-btn"
                        >
                            View Details
                        </a>



                        @if(Auth::check() && Auth::user()->role === 'host')


                            <div
                                style="
                                    margin-top: 15px;
                                    padding-top: 15px;
                                    border-top: 1px solid #ddd;
                                "
                            >

                                <h4
                                    style="
                                        margin-bottom: 10px;
                                        color: #0f172a;
                                    "
                                >
                                    Customer Bookings
                                </h4>


                                @if($property->bookings->count() > 0)


                                    @foreach($property->bookings as $booking)


                                        <div
                                            style="
                                                background: #f8fafc;
                                                padding: 10px;
                                                border-radius: 8px;
                                                margin-bottom: 10px;
                                                font-size: 14px;
                                            "
                                        >

                                            <p>
                                                <strong>Name:</strong>
                                                {{ $booking->user->name ?? 'N/A' }}
                                            </p>

                                            <p>
                                                <strong>Email:</strong>
                                                {{ $booking->user->email ?? 'N/A' }}
                                            </p>

                                            <p>
                                                <strong>Phone:</strong>
                                                {{ $booking->phone }}
                                            </p>

                                            <p>
                                                <strong>Check In:</strong>
                                                {{ $booking->check_in }}
                                            </p>

                                            <p>
                                                <strong>Check Out:</strong>
                                                {{ $booking->check_out }}
                                            </p>

                                            <p>
                                                <strong>Guests:</strong>
                                                {{ $booking->guests }}
                                            </p>


                                            @if($booking->status === 'pending')

                                                <div
                                                    style="
                                                        display: flex;
                                                        gap: 8px;
                                                        margin-top: 8px;
                                                    "
                                                >

                                                    <form
                                                        action="{{ route('booking.confirm', $booking->id) }}"
                                                        method="POST"
                                                    >

                                                        @csrf

                                                        <button
                                                            type="submit"
                                                            style="
                                                                padding: 6px 12px;
                                                                background: #16a34a;
                                                                color: white;
                                                                border: none;
                                                                border-radius: 5px;
                                                                cursor: pointer;
                                                                font-weight: bold;
                                                            "
                                                        >
                                                            Confirm
                                                        </button>

                                                    </form>

                                                </div>


                                            @elseif($booking->status === 'confirmed')

                                                <div
                                                    style="
                                                        margin-top: 8px;
                                                        color: rgb(19, 136, 19);
                                                        font-weight: bold;
                                                    "
                                                >
                                                    Status: Confirmed
                                                </div>


                                            @elseif($booking->status === 'cancelled')

                                                <div
                                                    style="
                                                        margin-top: 8px;
                                                        color: #dc2626;
                                                        font-weight: bold;
                                                    "
                                                >
                                                    Status: Cancelled
                                                </div>

                                            @endif

                                        </div>

                                    @endforeach


                                @else

                                    <p
                                        style="
                                            font-size: 14px;
                                            color: #6b7280;
                                        "
                                    >
                                        No bookings yet for this hotel.
                                    </p>

                                @endif

                            </div>

                        @endif

                    </div>

                </div>

            @endforeach

        </div>


    @else

        <p class="empty">
            No hotels available yet. Add your first hotel from Host page.
        </p>

    @endif

</div>



<!-- =========================
     LOCATION SEARCH
========================= -->

<script>

const locationInput =
    document.querySelector('input[name="location"]');

const cards =
    document.querySelectorAll('.hotel-card');


if (locationInput) {

    locationInput.addEventListener('input', function () {

        const query =
            this.value.trim().toLowerCase();

        cards.forEach(card => {

            const loc =
                card.getAttribute('data-location');

            if (loc.includes(query)) {

                card.style.display = '';

            } else {

                card.style.display = 'none';

            }

        });

    });

}

</script>



<!-- =========================
     FLOATING CHAT BUTTON
========================= -->

<div
    id="chatBubble"
    onclick="toggleChat()"
>
    💬
</div>



<!-- =========================
     CHAT BOX
========================= -->

<div id="chatBox">


    <div id="chatHeader">

        <span>
            🏨 Smart Assistant
        </span>

        <button onclick="toggleChat()">
            ✖
        </button>

    </div>


    <div id="messages"></div>


    <div
        id="typing"
        style="display: none;"
    >
        Bot is typing...
    </div>


    <div id="inputArea">

        <input
            id="userInput"
            type="text"
            placeholder="Ask about rooms..."
        >

        <button onclick="sendMessage()">
            ➤
        </button>

    </div>

</div>



<!-- =========================
     CHAT JAVASCRIPT
========================= -->

<script>

let chatOpen = false;


function toggleChat() {

    chatOpen = !chatOpen;

    document.getElementById("chatBox").style.display =
        chatOpen ? "flex" : "none";

}


function appendMessage(text, type) {

    let div =
        document.createElement("div");

    div.classList.add("msg", type);

    div.innerText = text;

    document
        .getElementById("messages")
        .appendChild(div);

    document
        .getElementById("messages")
        .scrollTop =
        document.getElementById("messages").scrollHeight;

}


async function sendMessage() {

    let input =
        document.getElementById("userInput");

    let message =
        input.value.trim();


    if (!message) {
        return;
    }


    appendMessage(message, "user");

    input.value = "";


    document.getElementById("typing").style.display =
        "block";


    try {

        let res = await fetch(
            "{{ url('/chatbot') }}",
            {
                method: "POST",

                headers: {
                    "Content-Type": "application/json",

                    "X-CSRF-TOKEN":
                        "{{ csrf_token() }}"
                },

                body: JSON.stringify({
                    message: message
                })
            }
        );


        let data = await res.json();


        document.getElementById("typing").style.display =
            "none";


        appendMessage(
            data.reply,
            "bot"
        );


    } catch (error) {

        document.getElementById("typing").style.display =
            "none";


        appendMessage(
            "❌ Something went wrong.",
            "bot"
        );

    }

}


document
    .getElementById("userInput")
    .addEventListener(
        "keypress",
        function(event) {

            if (event.key === "Enter") {

                event.preventDefault();

                sendMessage();

            }

        }
    );

</script>


</body>

</html>