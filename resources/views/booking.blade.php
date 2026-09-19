<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Stayzio - Booking</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:Arial,sans-serif;
            background:#f4f6f8;
        }

        .navbar{
            background:#0f172a;
            color:white;
            padding:1px 30px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            flex-wrap:wrap;
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
            display:flex;
            gap:20px;
        }

        .nav-links a{
            color:white;
            text-decoration:none;
            font-weight:bold;
        }

        .nav-links a:hover{
            color:#38bdf8;
        }

        .booking-container{
            max-width:600px;
            background:white;
            margin:40px auto;
            padding:30px;
            border-radius:15px;
            box-shadow:0 4px 14px rgba(0,0,0,0.08);
        }

        h1{
            color:#0f172a;
            margin-bottom:20px;
        }

        .property-info{
            margin-bottom:20px;
        }

        .property-info p{
            margin-bottom:8px;
        }

        .property-image{
            width:100%;
            height:250px;
            object-fit:cover;
            border-radius:12px;
            margin-bottom:25px;
        }

        .booking-form input{
            width:100%;
            padding:12px;
            margin-bottom:15px;
            border:1px solid #d1d5db;
            border-radius:8px;
            font-size:15px;
        }

        .booking-form label{
            display:block;
            font-weight:bold;
            margin-bottom:8px;
            color:#374151;
        }

        .price-summary{
            background:#f8fafc;
            padding:18px;
            border-radius:10px;
            margin:10px 0 20px;
        }

        .price-summary p{
            margin-bottom:8px;
        }

        .price-summary p:last-child{
            margin-bottom:0;
        }

        .book-btn{
            width:100%;
            padding:14px;
            background:#2563eb;
            color:white;
            border:none;
            border-radius:8px;
            font-size:16px;
            font-weight:bold;
            cursor:pointer;
        }

        .book-btn:hover{
            background:#1d4ed8;
        }

        .success-message{
            background:#d4edda;
            color:#155724;
            padding:12px;
            border-radius:8px;
            margin-bottom:15px;
        }

        .error-message{
            background:#f8d7da;
            color:#721c24;
            padding:12px;
            border-radius:8px;
            margin-bottom:15px;
        }

        .error-message ul{
            margin:0;
            padding-left:20px;
        }

        /* Chat */

        #chatBubble{
            position:fixed;
            right:25px;
            bottom:25px;
            width:60px;
            height:60px;
            border-radius:50%;
            background:#2563eb;
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:26px;
            cursor:pointer;
            box-shadow:0 5px 20px rgba(0,0,0,0.2);
        }

        #chatBox{
            position:fixed;
            right:25px;
            bottom:95px;
            width:350px;
            height:450px;
            background:white;
            border-radius:15px;
            box-shadow:0 8px 30px rgba(0,0,0,0.2);
            display:none;
            flex-direction:column;
            overflow:hidden;
        }

        #chatHeader{
            background:#0f172a;
            color:white;
            padding:15px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        #chatHeader button{
            background:none;
            border:none;
            color:white;
            font-size:18px;
            cursor:pointer;
        }

        #messages{
            flex:1;
            padding:15px;
            overflow-y:auto;
        }

        .msg{
            padding:10px;
            border-radius:10px;
            margin-bottom:10px;
            max-width:85%;
            white-space:pre-line;
        }

        .msg.user{
            background:#2563eb;
            color:white;
            margin-left:auto;
        }

        .msg.bot{
            background:#e5e7eb;
            color:#111827;
        }

        #typing{
            padding:8px 15px;
            font-size:13px;
            color:#6b7280;
        }

        #inputArea{
            display:flex;
            padding:10px;
            border-top:1px solid #ddd;
        }

        #userInput{
            flex:1;
            margin:0;
        }

        #inputArea button{
            width:50px;
            background:#2563eb;
            color:white;
            border:none;
            border-radius:8px;
            cursor:pointer;
        }

        @media(max-width:600px){

            .booking-container{
                margin:20px;
                padding:20px;
            }

            #chatBox{
                right:10px;
                left:10px;
                width:auto;
            }

        }

    </style>

</head>

<body>

    <div class="navbar">

        <div class="brand">
        <img src="{{ asset('images/logo.png') }}" alt="Stayzio Logo">
        <h2>Stayzio</h2>
        </div>
        <div class="nav-links">
            <a href="{{ route('home') }}">Home</a>
        </div>

    </div>


    <div class="booking-container">

        <h1>
            Book {{ $property->title }}
        </h1>


        <div class="property-info">

            <p>
                <strong>Location:</strong>
                {{ $property->location }}
            </p>

            <p>
                <strong>Price per night:</strong>
                ₹{{ number_format($property->price, 2) }}
            </p>

            <p>
                <strong>Maximum guests:</strong>
                {{ $property->max_guests }}
            </p>

        </div>


        @if($property->image)

            <img
                src="{{ asset('images/' . $property->image) }}"
                alt="{{ $property->title }}"
                class="property-image"
            >

        @endif


        @if(session('success'))

            <div class="success-message">
                {{ session('success') }}
            </div>

        @endif


        @if(session('error'))

            <div class="error-message">
                {{ session('error') }}
            </div>

        @endif


        @if($errors->any())

            <div class="error-message">

                <ul>

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form
            action="{{ route('booking.store', $property->id) }}"
            method="POST"
            class="booking-form"
        >

            @csrf


            <label for="phone">
                Phone
            </label>

            <input
                type="text"
                id="phone"
                name="phone"
                placeholder="Phone"
                value="{{ old('phone') }}"
                required
            >


            <label for="check_in">
                Check-in Date
            </label>

            <input
                type="date"
                id="check_in"
                name="check_in"
                min="{{ date('Y-m-d') }}"
                value="{{ old('check_in') }}"
                required
            >


            <label for="check_out">
                Check-out Date
            </label>

            <input
                type="date"
                id="check_out"
                name="check_out"
                min="{{ date('Y-m-d') }}"
                value="{{ old('check_out') }}"
                required
            >


            <div class="price-summary">

                <p>
                    Price per night:
                    ₹<span id="price">
                        {{ number_format($property->price, 2) }}
                    </span>
                </p>

                <p>
                    Total nights:
                    <span id="days">0</span>
                </p>

                <p>
                    <strong>
                        Total price:
                        ₹<span id="total_price">0.00</span>
                    </strong>
                </p>

            </div>


            <label for="guests">
                Guests
            </label>

            <input
                type="number"
                id="guests"
                name="guests"
                placeholder="Guests"
                value="{{ old('guests') }}"
                min="1"
                max="{{ $property->max_guests }}"
                required
            >


            <button
                type="submit"
                class="book-btn"
            >
                Proceed to Payment
            </button>

        </form>

    </div>


    <!-- Chat Bubble -->

    <div
        id="chatBubble"
        onclick="toggleChat()"
    >
        💬
    </div>


    <!-- Chat Box -->

    <div id="chatBox">

        <div id="chatHeader">

            <span>
                🏨 Smart Assistant
            </span>

            <button
                onclick="toggleChat()"
            >
                ✖
            </button>

        </div>


        <div id="messages"></div>


        <div
            id="typing"
            style="display:none;"
        >
            Bot is typing...
        </div>


        <div id="inputArea">

            <input
                id="userInput"
                type="text"
                placeholder="Ask about rooms..."
            >

            <button
                onclick="sendMessage()"
            >
                ➤
            </button>

        </div>

    </div>


    <script>

        /*
        |--------------------------------------------------------------------------
        | Booking price calculation
        |--------------------------------------------------------------------------
        */

        const checkIn =
            document.getElementById('check_in');

        const checkOut =
            document.getElementById('check_out');

        const pricePerNight =
            Number({{ $property->price }});


        function calculateTotal()
        {
            if (!checkIn.value || !checkOut.value) {

                document.getElementById('days').innerText = 0;

                document.getElementById('total_price').innerText =
                    '0.00';

                return;
            }


            const checkInDate =
                new Date(checkIn.value + 'T00:00:00');

            const checkOutDate =
                new Date(checkOut.value + 'T00:00:00');


            if (checkOutDate > checkInDate) {

                const timeDiff =
                    checkOutDate - checkInDate;

                const days =
                    Math.round(
                        timeDiff /
                        (1000 * 60 * 60 * 24)
                    );


                const total =
                    pricePerNight * days;


                document.getElementById('days').innerText =
                    days;


                document.getElementById('total_price').innerText =
                    total.toFixed(2);

            } else {

                document.getElementById('days').innerText =
                    0;

                document.getElementById('total_price').innerText =
                    '0.00';
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Make checkout at least one day after check-in
        |--------------------------------------------------------------------------
        */

        checkIn.addEventListener(
            'change',
            function(){

                if (checkIn.value) {

                    const date =
                        new Date(
                            checkIn.value + 'T00:00:00'
                        );

                    date.setDate(
                        date.getDate() + 1
                    );

                    const minimumCheckout =
                        date.toISOString()
                            .split('T')[0];

                    checkOut.min =
                        minimumCheckout;

                    if (
                        checkOut.value &&
                        checkOut.value <= checkIn.value
                    ) {

                        checkOut.value = '';
                    }

                }

                calculateTotal();
            }
        );


        checkOut.addEventListener(
            'change',
            calculateTotal
        );


        window.addEventListener(
            'load',
            function(){

                if (checkIn.value) {

                    const date =
                        new Date(
                            checkIn.value + 'T00:00:00'
                        );

                    date.setDate(
                        date.getDate() + 1
                    );

                    checkOut.min =
                        date.toISOString()
                            .split('T')[0];
                }

                calculateTotal();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Chatbot
        |--------------------------------------------------------------------------
        */

        let chatOpen = false;


        function toggleChat()
        {
            chatOpen = !chatOpen;

            document.getElementById(
                "chatBox"
            ).style.display =
                chatOpen ? "flex" : "none";
        }


        function appendMessage(
            text,
            type
        )
        {
            let div =
                document.createElement("div");

            div.classList.add(
                "msg",
                type
            );

            div.innerText = text;

            document.getElementById(
                "messages"
            ).appendChild(div);


            document.getElementById(
                "messages"
            ).scrollTop =
                document.getElementById(
                    "messages"
                ).scrollHeight;
        }


        async function sendMessage()
        {
            let input =
                document.getElementById(
                    "userInput"
                );

            let message =
                input.value.trim();


            if (!message) {
                return;
            }


            appendMessage(
                message,
                "user"
            );


            input.value = "";


            document.getElementById(
                "typing"
            ).style.display = "block";


            try {

                let res =
                    await fetch(
                        "/chatbot",
                        {
                            method:"POST",

                            headers:{
                                "Content-Type":
                                    "application/json",

                                "X-CSRF-TOKEN":
                                    "{{ csrf_token() }}"
                            },

                            body:JSON.stringify({
                                message:message
                            })
                        }
                    );


                let data =
                    await res.json();


                document.getElementById(
                    "typing"
                ).style.display = "none";


                appendMessage(
                    data.reply ||
                    "Sorry, I could not find an answer.",
                    "bot"
                );


            } catch(error) {

                document.getElementById(
                    "typing"
                ).style.display = "none";


                appendMessage(
                    "❌ Something went wrong.",
                    "bot"
                );
            }
        }


        document.getElementById(
            "userInput"
        ).addEventListener(
            "keypress",
            function(event){

                if(event.key === "Enter"){

                    event.preventDefault();

                    sendMessage();
                }
            }
        );

    </script>

</body>

</html>