<!DOCTYPE html>
<html>
<head>
    <title>{{ $property->title }}</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            color: #333;
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
            align-items: center;
            gap: 15px;
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

        .container{
            max-width: 1000px;
            margin: 30px auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        h1{
            margin-bottom: 20px;
            color: #0f172a;
        }

        .main-image img{
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 20px;
            margin-bottom: 25px;

            /* classy shadow */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18);

            /* smooth effect */
            transition: all 0.4s ease;

            /* elegant border */
            border: 3px solid rgba(255,255,255,0.7);
        }

        /* premium hover effect */
        .main-image img:hover{
            transform: scale(1.01);
            box-shadow: 0 14px 40px rgba(0, 0, 0, 0.25);
        }

            .more-images{
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .more-images img{
            width: 32%;
            height: 220px;
            object-fit: cover;
            border-radius: 16px;

            /* premium clean border */
            border: 2px solid rgba(255,255,255,0.8);

            /* classy shadow */
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);

            /* smooth animation */
            transition: all 0.35s ease;

            cursor: pointer;
        }

        /* elegant hover effect */
        .more-images img:hover{
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 12px 28px rgba(0,0,0,0.22);
        }

                .details p{
            margin-bottom: 16px;
            font-size: 18px;
            line-height: 1.8;
            color: #334155;

            /* clean modern card style */
            background: #f8fafc;
            padding: 10px 15px;
            border-radius: 12px;

            /* subtle premium effect */
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);

            /* left accent */
            border-left: 4px solid #2563eb;

            transition: all 0.3s ease;
        }

        .details p:hover{
            transform: translateX(4px);
            background: #f1f5f9;
        }

        .book-btn{
            display: inline-block;
            margin-top: 20px;
            margin-bottom: 20px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
        }

        .book-btn:hover{
            background: #1d4ed8;
        }

        .property-description{
            line-height: 1.9;
            font-size: 18px;
            color: #475569;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin-top: 30px;

            /* classy layout */
            background: #ffffff;
            padding: 28px;
            border-radius: 18px;

            /* elegant shadow */
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);

            border: 1px solid #e2e8f0;
        }

        .property-description strong{
            display: inline-block;
            font-size: 30px;
            margin-top: 15px;
            margin-bottom: 18px;
            color: #0f172a;
            font-weight: 700;
            position: relative;
            padding-bottom: 8px;
        }

        /* stylish underline effect */
        .property-description strong::after{
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(to right, #2563eb, #60a5fa);
            border-radius: 10px;
        }

        .message {
            max-width: 1000px;
            margin: 20px auto 0;
            padding: 12px 18px;
            border-radius: 8px;
            font-weight: bold;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
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

    @if(session('success'))
        <div class="message success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="message error">{{ session('error') }}</div>
    @endif

    <div class="container">
        <h1>{{ $property->title }}</h1>

        <div class="main-image">
            @if($property->image)
                <img src="{{ asset('images/' . $property->image) }}" alt="{{ $property->title }}">
            @else
                <img src="{{ asset('images/default-property.jpg') }}" alt="No Image">
            @endif
        </div>

        @if($property->images->count() > 0)
            <div class="more-images">
                @foreach($property->images as $img)
                    <img src="{{ asset('images/' . $img->image_path) }}" alt="Property Image">
                @endforeach
            </div>
        @endif

        <div class="details">
            <p><strong>Location:</strong> {{ $property->location }}</p>
            <p><strong>Price:</strong> ₹{{ $property->price }}</p>
        </div>

        @php
            $formattedDescription = e($property->description);
            $headings = [
                'About this place',
                'The space',
                'Guest access',
                'Other things to note',
                'What this place offers',
            ];

            foreach ($headings as $heading) {
                $formattedDescription = str_replace(
                    $heading,
                    '<strong>' . $heading . '</strong>',
                    $formattedDescription
                );
            }

            $formattedDescription = nl2br($formattedDescription);
        @endphp

        <a href="{{ route('booking.create', $property->id) }}" class="book-btn">Book Now</a>

        <div class="property-description">
            {!! $formattedDescription !!}
        </div>
    </div>

</body>
</html>