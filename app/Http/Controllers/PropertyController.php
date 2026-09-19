<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PropertyController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Home Page
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        if (Auth::check() && Auth::user()->role === 'host') {

            $properties = Property::with('bookings')
                ->where('user_id', Auth::id())
                ->latest()
                ->get();

        } else {

            $properties = Property::latest()->get();
        }

        return view('home', compact('properties'));
    }


    /*
    |--------------------------------------------------------------------------
    | Host Page
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        if (!Auth::check() || Auth::user()->role !== 'host') {
            return redirect()
                ->route('home')
                ->with('error', 'Only hosts can add properties.');
        }

        return view('host');
    }


    /*
    |--------------------------------------------------------------------------
    | Store Property
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'host') {
            return redirect()
                ->route('home')
                ->with('error', 'Only hosts can add properties.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,avif|max:2048',

            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,avif|max:2048',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Main Image
        |--------------------------------------------------------------------------
        */

        $imageName = null;

        if ($request->hasFile('image')) {

            $imageName =
                time() .
                '_' .
                uniqid() .
                '.' .
                $request->file('image')->extension();

            $request->file('image')->move(
                public_path('images'),
                $imageName
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Property
        |--------------------------------------------------------------------------
        */

        $property = Property::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'price' => $request->price,
            'max_guests' => $request->max_guests,
            'image' => $imageName,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Additional Images
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $img) {

                $multiImageName =
                    time() .
                    '_' .
                    uniqid() .
                    '.' .
                    $img->extension();

                $img->move(
                    public_path('images'),
                    $multiImageName
                );

                $property->images()->create([
                    'image_path' => $multiImageName,
                ]);
            }
        }


        return redirect()
            ->route('home')
            ->with('success', 'Property added successfully!');
    }


    /*
    |--------------------------------------------------------------------------
    | Update Property
    |--------------------------------------------------------------------------
    */

    public function update(Request $request,int $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'host') {
            return redirect()
                ->route('home')
                ->with('error', 'Only hosts can update properties.');
        }

        $property = Property::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Ownership Check
        |--------------------------------------------------------------------------
        */

        if (Auth::id() !== $property->user_id) {
            return redirect()
                ->route('home')
                ->with('error', 'Unauthorized action.');
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,avif|max:2048',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Keep Old Image
        |--------------------------------------------------------------------------
        */

        $oldImage = $property->image;
        $newImage = null;


        /*
        |--------------------------------------------------------------------------
        | Update Main Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            $newImage =
                time() .
                '_' .
                uniqid() .
                '.' .
                $request->file('image')->extension();

            try {

                /*
                | Upload the new image first.
                */

                $request->file('image')->move(
                    public_path('images'),
                    $newImage
                );


                /*
                |--------------------------------------------------------------------------
                | Update Property
                |--------------------------------------------------------------------------
                */

                $property->image = $newImage;

                $property->title = $request->title;
                $property->description = $request->description;
                $property->location = $request->location;
                $property->price = $request->price;
                $property->max_guests = $request->max_guests;

                $property->save();


            } catch (\Throwable $e) {

                /*
                |--------------------------------------------------------------------------
                | Remove New Image If Update Fails
                |--------------------------------------------------------------------------
                */

                if ($newImage) {

                    $newPath = public_path(
                        'images/' . $newImage
                    );

                    if (file_exists($newPath)) {
                        @unlink($newPath);
                    }
                }

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'The property could not be updated. Please try again.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Delete Old Image Only After Successful Update
            |--------------------------------------------------------------------------
            */

            if ($oldImage && $oldImage !== $newImage) {

                $oldPath = public_path(
                    'images/' . $oldImage
                );

                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }


        } else {

            /*
            |--------------------------------------------------------------------------
            | Update Property Without Changing Image
            |--------------------------------------------------------------------------
            */

            $property->title = $request->title;
            $property->description = $request->description;
            $property->location = $request->location;
            $property->price = $request->price;
            $property->max_guests = $request->max_guests;

            $property->save();
        }


        return redirect()
            ->route('home')
            ->with('success', 'Property updated successfully!');
    }


    /*
    |--------------------------------------------------------------------------
    | Property Details
    |--------------------------------------------------------------------------
    */

    public function show(int $id)
    {
        $property = Property::with('images')
            ->findOrFail($id);

        return view('user', compact('property'));
    }


    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    public function search(Request $request)
    {
        $request->validate([
            'location' => 'nullable|string',

            'guests' => 'nullable|integer|min:1',

            'check_in' =>
                'nullable|date|after_or_equal:today',

            'check_out' =>
                'nullable|date|after:check_in',
        ]);


        $query = Property::query();


        /*
        |--------------------------------------------------------------------------
        | Location
        |--------------------------------------------------------------------------
        */

        if ($request->filled('location')) {

            $query->where(
                'location',
                'LIKE',
                '%' . $request->location . '%'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Guests
        |--------------------------------------------------------------------------
        */

        if ($request->filled('guests')) {

            $query->where(
                'max_guests',
                '>=',
                $request->guests
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Date Availability
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('check_in') &&
            $request->filled('check_out')
        ) {

            $checkIn = Carbon::parse(
                $request->check_in
            );

            $checkOut = Carbon::parse(
                $request->check_out
            );


            /*
            |--------------------------------------------------------------------------
            | Exclude Properties With Overlapping Paid Bookings
            |--------------------------------------------------------------------------
            */

            $query->whereDoesntHave(
                'bookings',
                function ($bookingQuery) use (
                    $checkIn,
                    $checkOut
                ) {

                    $bookingQuery
                        ->where(
                            'payment_status',
                            'paid'
                        )
                        ->where(
                            'status',
                            '!=',
                            'cancelled'
                        )
                        ->where(
                            'check_in',
                            '<',
                            $checkOut->toDateString()
                        )
                        ->where(
                            'check_out',
                            '>',
                            $checkIn->toDateString()
                        );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Properties
        |--------------------------------------------------------------------------
        */

        $properties = $query
            ->latest()
            ->get();


        return view(
            'home',
            compact('properties')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Live Search
    |--------------------------------------------------------------------------
    */

    public function liveSearch(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:1',
        ]);


        $properties = Property::where(
            'location',
            'LIKE',
            '%' . $request->q . '%'
        )
            ->limit(5)
            ->get();


        return response()->json($properties);
    }
}
