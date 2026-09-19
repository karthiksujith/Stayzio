<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>

    <style>
        *{ margin:0; padding:0; box-sizing:border-box; }

        body{
            font-family: Arial, sans-serif;
            background:#f1f5f9;
        }

        .navbar{
            background:#0f172a;
            color:white;
            padding:1px 30px;
            display:flex;
            justify-content:space-between;
            align-items: center;
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

        .nav-links a{
            color:white;
            text-decoration:none;
            font-weight:bold;
        }

        .main{
            max-width:1100px;
            margin:30px auto;
        }

        .grid{
            display:grid;
            grid-template-columns:1fr 2fr;
            gap:25px;
        }

        .card{
            background:white;
            padding:25px;
            border-radius:15px;
            box-shadow:0 8px 20px rgba(0,0,0,0.08);
        }
        .back-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 16px;
            background: #0f172a;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }

        .back-btn:hover {
            background: #1e293b;
        }
        .booking{
            border-top:1px solid #e2e8f0;
            padding:15px 0;
        }

        .status{
            font-weight:bold;
        }

        .status.pending{ color:#2563eb; }
        .status.confirmed{ color:#16a34a; }
        .status.cancelled{ color:#ef4444; }

        .payment.paid {
            color: #16a34a;
        }

        .payment.unpaid {
            color: #6b7280;
        }

        .btn-danger {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 14px;
            background: #dc2626;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <a href="{{ route('home') }}" class="brand">
        <img src="{{ asset('images/logo.png') }}" alt="Stayzio Logo">
        <h2>Stayzio</h2>
    </a>
    <div class="nav-links">
        <a href="/">Home</a>
    </div>
</div>

<!-- MAIN -->
<div class="main">

    <div class="grid">

        <!-- PROFILE -->
        <div class="card">
            <h2>My Profile</h2>

            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
            <a href="{{ route('host.page') }}" class="back-btn">
                Add Property
            </a>
        
        </div>

        <!-- BOOKINGS -->
        <div class="card">
            <h2>My Bookings</h2>

            @forelse($bookings as $booking)
                <div class="booking">

                    <p><strong>Hotel:</strong> {{ $booking->property?->title ?? 'Deleted Property' }}</p>
                    <p><strong>Location:</strong> {{ $booking->property?->location ?? 'N/A' }}</p>


                    <p>
                        <strong>Dates:</strong>
                        {{ $booking->check_in }} → {{ $booking->check_out }}
                    </p>

                    <p><strong>Guests:</strong> {{ $booking->guests }}</p>

                    <!-- Booking Status -->
                    <p>
                        <strong>Booking:</strong>
                        <span class="status {{ $booking->status }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </p>

                    <!-- Payment Status -->
                    <p>
                        <strong>Payment:</strong>
                        <span class="payment {{ $booking->payment_status === 'paid' ? 'paid' : 'unpaid' }}">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    </p>
                    @if($booking->status !== 'cancelled')
                        <form action="{{ route('booking.cancel', $booking->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-danger">
                                Cancel Booking
                            </button>
                        </form>
                    @endif

                </div>

                @empty
                <p>No bookings yet.</p>
            @endforelse

        </div>

        
    </div>

</div>

</body>
</html>