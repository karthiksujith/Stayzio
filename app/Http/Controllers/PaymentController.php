<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Payment Page
    |--------------------------------------------------------------------------
    */

    public function show(int $bookingId)
    {
        $booking = Booking::with('property')
            ->findOrFail($bookingId);

        /*
        |--------------------------------------------------------------------------
        | Only booking owner can access payment
        |--------------------------------------------------------------------------
        */

        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        /*
        |--------------------------------------------------------------------------
        | Cancelled booking
        |--------------------------------------------------------------------------
        */

        if ($booking->status === 'cancelled') {
            return redirect()
                ->route('profile')
                ->with('error', 'This booking has been cancelled.');
        }

        /*
        |--------------------------------------------------------------------------
        | Already paid
        |--------------------------------------------------------------------------
        */

        if ($booking->payment_status === 'paid') {
            return redirect()
                ->route('profile')
                ->with('error', 'This booking is already paid.');
        }

        return view('payment', compact('booking'));
    }

    /*
    |--------------------------------------------------------------------------
    | Demo Payment Success
    |--------------------------------------------------------------------------
    */

    public function paymentSuccess(int $id)
    {
        $booking = Booking::with('property')
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Only booking owner can pay
        |--------------------------------------------------------------------------
        */

        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        /*
        |--------------------------------------------------------------------------
        | Basic booking checks
        |--------------------------------------------------------------------------
        */

        if ($booking->payment_status === 'paid') {
            return redirect()
                ->route('profile')
                ->with('error', 'This booking is already paid.');
        }

        if ($booking->status === 'cancelled') {
            return redirect()
                ->route('profile')
                ->with('error', 'This booking has been cancelled.');
        }

        if ($booking->status !== 'pending') {
            return redirect()
                ->route('profile')
                ->with('error', 'This booking cannot be paid for.');
        }

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT: Prevent concurrent double-bookings
        |--------------------------------------------------------------------------
        |
        | Lock the property row for the entire transaction.
        |
        | If two users try to pay for overlapping dates at exactly the same
        | time, only one transaction can hold this lock at a time.
        |
        */

        $paymentSuccessful = DB::transaction(function () use ($booking) {

            /*
            |--------------------------------------------------------------------------
            | Lock the property row
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
            | Re-read the booking inside the transaction
            |--------------------------------------------------------------------------
            |
            | This prevents using stale booking data.
            |
            */

            $currentBooking = Booking::lockForUpdate()
                ->find($booking->id);

            if (!$currentBooking) {
                return false;
            }

            /*
            |--------------------------------------------------------------------------
            | Check booking state again
            |--------------------------------------------------------------------------
            */

            if ($currentBooking->payment_status === 'paid') {
                return false;
            }

            if ($currentBooking->status === 'cancelled') {
                return false;
            }

            if ($currentBooking->status !== 'pending') {
                return false;
            }

            /*
            |--------------------------------------------------------------------------
            | Final availability check while property is locked
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
            | Mark payment as paid
            |--------------------------------------------------------------------------
            */

            $currentBooking->update([
                'payment_status' => 'paid',
                'status' => 'pending',
                'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
            ]);

            return true;
        });

        /*
        |--------------------------------------------------------------------------
        | Payment failed because dates became unavailable
        |--------------------------------------------------------------------------
        */

        if (!$paymentSuccessful) {
            return redirect()
                ->route('profile')
                ->with(
                    'error',
                    'Sorry, this property was booked by another user for the selected dates. Your payment was not processed.'
                );
        }

        return redirect()
            ->route('profile')
            ->with(
                'success',
                'Demo payment successful! Waiting for host approval.'
            );
    }
}