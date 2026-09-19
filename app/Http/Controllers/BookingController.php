<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function create(int $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login as user to book a property.');
        }

        if (Auth::user()->role !== 'user') {
            return redirect()->route('home')
                ->with('error', 'Only users can book properties.');
        }

        $property = Property::findOrFail($id);

        return view('booking', compact('property'));
    }

    public function store(Request $request, int $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login as user to book a property.');
        }

        if (Auth::user()->role !== 'user') {
            return redirect()->route('home')
                ->with('error', 'Only users can book properties.');
        }

        $request->validate([
            'phone' => 'required|string|max:20',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
        ]);

        $property = Property::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Guest limit
        |--------------------------------------------------------------------------
        */

        if ($request->guests > $property->max_guests) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Guests cannot exceed the maximum allowed for this property (' .
                    $property->max_guests .
                    ').'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent host from booking own property
        |--------------------------------------------------------------------------
        */

        if ($property->user_id === Auth::id()) {
            return back()
                ->withInput()
                ->with('error', 'You cannot book your own property.');
        }

        /*
        |--------------------------------------------------------------------------
        | Parse dates
        |--------------------------------------------------------------------------
        */

        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);

        $days = $checkIn->diffInDays($checkOut);

        if ($days < 1) {
            return back()
                ->withInput()
                ->with('error', 'Invalid booking dates.');
        }

        /*
        |--------------------------------------------------------------------------
        | Check existing paid bookings
        |
        | Existing booking overlaps requested booking when:
        |
        | existing check-in < requested check-out
        | AND
        | existing check-out > requested check-in
        |--------------------------------------------------------------------------
        */

        $alreadyBooked = Booking::where('property_id', $property->id)
            ->where('payment_status', 'paid')
            ->where('status', '!=', 'cancelled')
            ->where('check_in', '<', $checkOut->toDateString())
            ->where('check_out', '>', $checkIn->toDateString())
            ->exists();

        if ($alreadyBooked) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'This property is already booked for the selected dates. Please choose different dates.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate total price
        |
        | Price is per night.
        |--------------------------------------------------------------------------
        */

        $totalPrice = $property->price * $days;

        /*
        |--------------------------------------------------------------------------
        | Create booking
        |
        | Payment starts as pending.
        |--------------------------------------------------------------------------
        */

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'property_id' => $property->id,
            'user_name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'phone' => $request->phone,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'guests' => $request->guests,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        return redirect()->route('payment.show', $booking->id);
    }

    public function cancel(int $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to cancel your booking.');
        }

        $booking = Booking::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Only the booking owner can cancel
        |--------------------------------------------------------------------------
        */

        if ($booking->user_id !== Auth::id()) {
            return back()
                ->with('error', 'Unauthorized action.');
        }

        /*
        |--------------------------------------------------------------------------
        | Already cancelled
        |--------------------------------------------------------------------------
        */

        if ($booking->status === 'cancelled') {
            return back()
                ->with('error', 'This booking is already cancelled.');
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent cancellation of past bookings
        |--------------------------------------------------------------------------
        */

        if (Carbon::parse($booking->check_out)->isPast()) {
            return back()
                ->with(
                    'error',
                    'Past bookings cannot be cancelled.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Cancel booking
        |--------------------------------------------------------------------------
        */

        $booking->update([
            'status' => 'cancelled',
        ]);

        return back()
            ->with(
                'success',
                'Booking cancelled successfully.'
            );
    }

    public function confirm(int $id)
    {
        $booking = Booking::with('property')->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Only property owner can confirm
        |--------------------------------------------------------------------------
        */

        if (Auth::id() !== $booking->property->user_id) {
            return back()->with('error', 'Unauthorized.');
        }

        /*
        |--------------------------------------------------------------------------
        | Confirm booking inside transaction
        |--------------------------------------------------------------------------
        */

        $confirmed = DB::transaction(function () use ($booking) {

            /*
            |--------------------------------------------------------------------------
            | Lock property row
            |--------------------------------------------------------------------------
            */

            $property = $booking->property()
                ->lockForUpdate()
                ->first();

            if (!$property) {
                return false;
            }

            /*
            |--------------------------------------------------------------------------
            | Reload and lock booking
            |--------------------------------------------------------------------------
            */

            $currentBooking = Booking::lockForUpdate()
                ->find($booking->id);

            if (!$currentBooking) {
                return false;
            }

            /*
            |--------------------------------------------------------------------------
            | Payment must be completed
            |--------------------------------------------------------------------------
            */

            if ($currentBooking->payment_status !== 'paid') {
                return false;
            }

            /*
            |--------------------------------------------------------------------------
            | Cannot confirm cancelled booking
            |--------------------------------------------------------------------------
            */

            if ($currentBooking->status === 'cancelled') {
                return false;
            }

            /*
            |--------------------------------------------------------------------------
            | Already confirmed
            |--------------------------------------------------------------------------
            */

            if ($currentBooking->status === 'confirmed') {
                return false;
            }

            /*
            |--------------------------------------------------------------------------
            | Check overlapping paid bookings
            |--------------------------------------------------------------------------
            */

            $alreadyBooked = Booking::where(
                    'property_id',
                    $property->id
                )
                ->where(
                    'id',
                    '!=',
                    $currentBooking->id
                )
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
                    $currentBooking->check_out
                )
                ->where(
                    'check_out',
                    '>',
                    $currentBooking->check_in
                )
                ->exists();

            if ($alreadyBooked) {
                return false;
            }

            /*
            |--------------------------------------------------------------------------
            | Confirm
            |--------------------------------------------------------------------------
            */

            $currentBooking->update([
                'status' => 'confirmed',
            ]);

            return true;
        });

        if (!$confirmed) {
            return back()->with(
                'error',
                'This booking cannot be confirmed because it is no longer available.'
            );
        }

        return back()->with(
            'success',
            'Booking confirmed successfully!'
        );
    }
}

