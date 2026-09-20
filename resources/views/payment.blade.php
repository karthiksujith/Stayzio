<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Payment | Stayzio</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            color: #333;
            min-height: 100vh;   
            padding: 0;
            margin: 0;
        }
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

        .payment-container {
            width: 100%;
            max-width: 900px;
            
            margin: 40px auto;
            
            background: white;

            border-radius: 15px;

            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);

            overflow: hidden;

            display: grid;
            grid-template-columns: 1fr 1fr;
        }


        /* =========================
           BOOKING SUMMARY
        ========================= */

        .booking-summary {
            padding: 35px;

            background: #f8fafc;

            border-right: 1px solid #e5e7eb;
        }

        .booking-summary h1 {
            font-size: 28px;

            margin-bottom: 25px;

            color: #1f2937;
        }

        .property-name {
            font-size: 21px;

            font-weight: bold;

            color: #2563eb;

            margin-bottom: 20px;
        }

        .detail {
            display: flex;

            justify-content: space-between;

            gap: 15px;

            padding: 12px 0;

            border-bottom: 1px solid #e5e7eb;

            font-size: 15px;
        }

        .detail span:first-child {
            color: #6b7280;
        }

        .detail span:last-child {
            font-weight: 600;

            color: #374151;

            text-align: right;
        }

        .total {
            display: flex;

            justify-content: space-between;

            gap: 15px;

            margin-top: 25px;

            font-size: 22px;

            font-weight: bold;

            color: #111827;
        }


        /* =========================
           PAYMENT SECTION
        ========================= */

        .payment-section {
            padding: 35px;
        }

        .payment-section h2 {
            font-size: 25px;

            margin-bottom: 20px;

            color: #1f2937;
        }


        /* =========================
           DEMO NOTICE
        ========================= */

        .demo-notice {
            background: #fff7ed;

            border: 1px solid #fed7aa;

            color: #9a3412;

            padding: 13px 15px;

            border-radius: 8px;

            font-size: 13px;

            margin-bottom: 22px;

            line-height: 1.5;
        }


        /* =========================
           PAYMENT METHODS
        ========================= */

        .methods {
            display: flex;

            gap: 10px;

            margin-bottom: 22px;
        }

        .method {
            flex: 1;

            padding: 13px;

            border: 1px solid #d1d5db;

            border-radius: 8px;

            text-align: center;

            cursor: pointer;

            font-size: 14px;

            background: white;

            transition: 0.2s;
        }

        .method:hover {
            border-color: #2563eb;
        }

        .method.active {
            border-color: #2563eb;

            background: #eff6ff;

            color: #2563eb;

            font-weight: bold;
        }


        /* =========================
           FORM
        ========================= */

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;

            margin-bottom: 7px;

            font-size: 14px;

            font-weight: 600;

            color: #374151;
        }

        .form-group input {
            width: 100%;

            padding: 12px;

            border: 1px solid #d1d5db;

            border-radius: 7px;

            font-size: 14px;

            outline: none;
        }

        .form-group input:focus {
            border-color: #2563eb;

            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.08);
        }


        /* =========================
           CARD ROW
        ========================= */

        .row {
            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 12px;
        }


        /* =========================
           PAY BUTTON
        ========================= */

        .pay-button {
            width: 100%;

            border: none;

            padding: 14px;

            margin-top: 8px;

            border-radius: 8px;

            background: #2563eb;

            color: white;

            font-size: 16px;

            font-weight: bold;

            cursor: pointer;

            transition: 0.3s;
        }

        .pay-button:hover {
            background: #1d4ed8;
        }


        /* =========================
           NOTES
        ========================= */

        .security-note {
            text-align: center;

            margin-top: 15px;

            font-size: 12px;

            color: #6b7280;
        }

        .back-link {
            display: block;

            text-align: center;

            margin-top: 18px;

            color: #2563eb;

            text-decoration: none;

            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }


        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 700px) {

            body {
                align-items: flex-start;

                padding-top: 20px;
            }

            .payment-container {
                grid-template-columns: 1fr;
            }

            .booking-summary {
                border-right: none;

                border-bottom: 1px solid #e5e7eb;
            }

            .payment-section,
            .booking-summary {
                padding: 25px;
            }

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
            <a href="{{ route('home') }}">Home</a>
        </div>
    </div>


<div class="payment-container">


    <!-- ==================================
         BOOKING SUMMARY
    ================================== -->

    <div class="booking-summary">

        <h1>Booking Summary</h1>


        <div class="property-name">

            {{ $booking->property->title }}

        </div>


        <div class="detail">

            <span>Booking ID</span>

            <span>#{{ $booking->id }}</span>

        </div>


        <div class="detail">

            <span>Check-in</span>

            <span>
                {{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}
            </span>

        </div>


        <div class="detail">

            <span>Check-out</span>

            <span>
                {{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}
            </span>

        </div>


        <div class="detail">

            <span>Guests</span>

            <span>{{ $booking->guests }}</span>

        </div>


        <div class="total">

            <span>Total</span>

            <span>
                ₹{{ number_format($booking->total_price, 2) }}
            </span>

        </div>

    </div>



    <!-- ==================================
         PAYMENT SECTION
    ================================== -->

    <div class="payment-section">

        <h2>Payment</h2>


        <!-- ==================================
             DEMO PAYMENT NOTICE
        ================================== -->

        <div class="demo-notice">

            ⚠️ Demo Payment — No real payment will be processed.

        </div>



        <!-- ==================================
             PAYMENT METHODS
        ================================== -->

        <div class="methods">

            <div
                class="method active"
                id="cardMethod"
                onclick="selectMethod('card')"
            >

                💳 Card

            </div>


            <div
                class="method"
                id="upiMethod"
                onclick="selectMethod('upi')"
            >

                📱 UPI

            </div>

        </div>



        <!-- ==================================
             CARD FORM
        ================================== -->

        <form
            action="{{ route('payment.success', $booking->id) }}"
            method="POST"
            id="cardForm"
        >

            @csrf


            <div class="form-group">

                <label for="card_name">
                    Cardholder Name
                </label>

                <input
                    type="text"
                    id="card_name"
                    name="card_name"
                    placeholder="John Doe"
                    autocomplete="off"
                >

            </div>



            <div class="form-group">

                <label for="card_number">
                    Card Number
                </label>

                <input
                    type="text"
                    id="card_number"
                    name="card_number"
                    placeholder="1234 5678 9012 3456"
                    maxlength="19"
                    autocomplete="off"
                >

            </div>



            <div class="row">


                <div class="form-group">

                    <label for="expiry">
                        Expiry Date
                    </label>

                    <input
                        type="text"
                        id="expiry"
                        name="expiry"
                        placeholder="MM/YY"
                        maxlength="5"
                        autocomplete="off"
                    >

                </div>



                <div class="form-group">

                    <label for="cvv">
                        CVV
                    </label>

                    <input
                        type="password"
                        id="cvv"
                        name="cvv"
                        placeholder="123"
                        maxlength="3"
                        autocomplete="off"
                    >

                </div>


            </div>



            <button
                type="submit"
                class="pay-button"
            >

                Pay ₹{{ number_format($booking->total_price, 2) }}

            </button>

        </form>



        <!-- ==================================
             UPI FORM
        ================================== -->

        <form
            action="{{ route('payment.success', $booking->id) }}"
            method="POST"
            id="upiForm"
            style="display: none;"
        >

            @csrf


            <div class="form-group">

                <label for="upi_id">
                    UPI ID
                </label>

                <input
                    type="text"
                    id="upi_id"
                    name="upi_id"
                    placeholder="example@upi"
                    autocomplete="off"
                >

            </div>



            <button
                type="submit"
                class="pay-button"
            >

                Pay ₹{{ number_format($booking->total_price, 2) }}

            </button>

        </form>



        <!-- ==================================
             BACK TO PROFILE
        ================================== -->

        <a
            href="{{ route('profile') }}"
            class="back-link"
        >

            ← Back to Profile

        </a>

    </div>

</div>



<!-- ==================================
     JAVASCRIPT
================================== -->

<script>

function selectMethod(method) {

    const cardMethod =
        document.getElementById('cardMethod');

    const upiMethod =
        document.getElementById('upiMethod');

    const cardForm =
        document.getElementById('cardForm');

    const upiForm =
        document.getElementById('upiForm');


    if (method === 'card') {

        cardMethod.classList.add('active');

        upiMethod.classList.remove('active');

        cardForm.style.display = 'block';

        upiForm.style.display = 'none';

    }


    if (method === 'upi') {

        upiMethod.classList.add('active');

        cardMethod.classList.remove('active');

        upiForm.style.display = 'block';

        cardForm.style.display = 'none';

    }

}

</script>


</body>

</html>