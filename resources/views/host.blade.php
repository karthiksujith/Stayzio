<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stayzio - Host</title>
    <style>
         *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
         }
        body{
            font-family: Arial, sans-serif;
            background: #f4f6f8;

        }

       .navbar{
            background: #0f172a;
            color: white;
            padding: 15px 30px;
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


        .form-container{
            max-width: 600px;
            background: white;
            margin: 40px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        }

        .form-container h2{
            text-align: center;
            margin-bottom: 25px;
            color: #0f172a;
        }

        .form-group{
            margin-bottom: 18px;
        }

        .form-group label{
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #374151;
        }

        .form-group input,
        .form-group textarea{
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 15px;
        }

        .form-group textarea{
            resize: vertical;
            min-height: 100px;
        }

        .btn{
            width: 100%;
            background: #2563eb;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn:hover{
            background: #1d4ed8;
        }

        .error{
            color: red;
            font-size: 14px;
            margin-top: 5px;
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

    <div class="form-container">
        <h2>Add Property</h2>

        <form action="{{ route('property.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Title:</label>
                <input type="text" name="title" value="{{ old('title') }}">
                @error('title')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Description:</label>
                <textarea name="description">{{ old('description') }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Location:</label>
                <input type="text" name="location" value="{{ old('location') }}">
                @error('location')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Price:</label>
                <input type="number" name="price" value="{{ old('price') }}">
                @error('price')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Max Guests:</label>
                <input type="number" name="max_guests" min="1" value="{{ old('max_guests', 1) }}">
                @error('max_guests')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Main Image:</label>
                <input type="file" name="image">
                @error('image')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>More Images:</label>
                <input type="file" name="images[]" multiple>
                @error('images')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn">Add Property</button>
 
        </form>
    </div>

</body>
</html>