<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    private string $geminiUrl =
        'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent';

    public function handle(Request $request)
    {
        $userMessage = trim($request->input('message', ''));

        if ($userMessage === '') {
            return response()->json([
                'reply' => 'Please enter a question.'
            ]);
        }

        $apiKey = config('services.gemini.api_key');

        if (empty($apiKey)) {
            Log::error('Gemini API key is missing.');

            return response()->json([
                'reply' => 'Sorry, the chatbot is temporarily unavailable.'
            ], 503);
        }

        $today = Carbon::today()->toDateString();

        /*
        |--------------------------------------------------------------------------
        | Step 1: Understand the user's request
        |--------------------------------------------------------------------------
        */

        $extractionPrompt = <<<PROMPT
You are the request-understanding system for Stayzio, a hotel booking website.

Today's date is {$today}.

Read the user's message and return ONLY valid JSON.

Use exactly this structure:

{
    "intent": "search",
    "location": null,
    "budget": null,
    "guests": null,
    "check_in": null,
    "check_out": null
}

Allowed intents:

- search
- general
- booking_help

Rules:

- Use "search" when the user wants to find a hotel, property, room, resort, lodge, or stay.
- Use "booking_help" when the user asks how to book, pay, cancel, or use Stayzio.
- Use "general" for greetings and general questions.
- location must be a string or null.
- budget must be a number or null.
- guests must be an integer or null.
- check_in must be YYYY-MM-DD or null.
- check_out must be YYYY-MM-DD or null.
- Convert natural-language dates into YYYY-MM-DD.
- If only one date is provided, use it as check_in and the following day as check_out.
- Budget means the maximum price per night.
- "under 4000", "below 4000", and "up to 4000" mean budget = 4000.
- "3 guests", "3 people", and "3 persons" mean guests = 3.
- Do not invent information.
- Do not answer the user.
- Only return JSON.

USER MESSAGE:

{$userMessage}

PROMPT;

        try {
            $response = $this->callGemini(
                $extractionPrompt,
                $apiKey,
                true
            );

            if (!$response->successful()) {
                Log::error(
                    'Gemini API request failed during request extraction.',
                    [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | Simple fallback for common non-search messages
                |--------------------------------------------------------------------------
                */

                $lowerMessage = strtolower($userMessage);

                if (
                    preg_match(
                        '/^(hi|hello|hey|hii|helo|good morning|good afternoon|good evening)$/i',
                        trim($userMessage)
                    )
                ) {
                    return response()->json([
                        'reply' =>
                            "Hello! 👋 I'm Stayzio AI.\n\n" .
                            "I can help you search for properties, understand booking steps, " .
                            "payments, and cancellations.\n\n" .
                            "How can I help you today?"
                    ]);
                }

                if (
                    str_contains($lowerMessage, 'how do i book') ||
                    str_contains($lowerMessage, 'how to book') ||
                    str_contains($lowerMessage, 'how can i book') ||
                    str_contains($lowerMessage, 'booking')
                ) {
                    return response()->json([
                        'reply' =>
                            "🏨 To book a property on Stayzio:\n\n" .
                            "1️⃣ Search for a property.\n" .
                            "2️⃣ Select the property you want.\n" .
                            "3️⃣ Click Book Now.\n" .
                            "4️⃣ Select your check-in and check-out dates.\n" .
                            "5️⃣ Enter the required booking details.\n" .
                            "6️⃣ Complete the demo payment.\n" .
                            "7️⃣ The host can then approve the booking."
                    ]);
                }

                return response()->json([
                    'reply' =>
                        "Sorry, I'm having trouble connecting to the AI service right now. " .
                        "You can still ask me about booking or try your question again shortly."
                ], 503);
            }

            $data = $response->json();

            $jsonText =
                $data['candidates'][0]['content']['parts'][0]['text']
                ?? null;

            if (!$jsonText) {
                Log::error('Gemini returned no usable extraction response.', [
                    'response' => $data,
                ]);

                return response()->json([
                    'reply' => 'Sorry, I could not understand your request.'
                ], 500);
            }

            /*
            |--------------------------------------------------------------------------
            | Remove Markdown code fences if Gemini returns them
            |--------------------------------------------------------------------------
            */

            $jsonText = trim($jsonText);

            $jsonText = preg_replace(
                '/^```(?:json)?\s*/i',
                '',
                $jsonText
            );

            $jsonText = preg_replace(
                '/\s*```$/',
                '',
                $jsonText
            );

            $filters = json_decode(
                trim($jsonText),
                true
            );

            if (!is_array($filters)) {
                Log::error('Gemini returned invalid JSON.', [
                    'response' => $jsonText,
                    'json_error' => json_last_error_msg(),
                ]);

                return response()->json([
                    'reply' =>
                        'I could not understand your request. Please try again.'
                ], 500);
            }
        } catch (\Throwable $e) {
            Log::error('Gemini connection error.', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'reply' =>
                    'Sorry, I am unable to respond right now. Please try again later.'
            ], 503);
        }

        /*
        |--------------------------------------------------------------------------
        | Extract filters
        |--------------------------------------------------------------------------
        */

        $intent = $filters['intent'] ?? 'general';

        $location = null;

        if (
            isset($filters['location']) &&
            is_string($filters['location'])
        ) {
            $location = trim($filters['location']);
        }

        $budget = null;

        if (
            isset($filters['budget']) &&
            is_numeric($filters['budget'])
        ) {
            $budget = (float) $filters['budget'];
        }

        $guests = null;

        if (
            isset($filters['guests']) &&
            is_numeric($filters['guests'])
        ) {
            $guests = (int) $filters['guests'];
        }

        $checkIn = $filters['check_in'] ?? null;
        $checkOut = $filters['check_out'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | General question
        |--------------------------------------------------------------------------
        */

        if ($intent === 'general') {
            $prompt = <<<PROMPT
You are Stayzio AI, the assistant for the Stayzio hotel booking website.

Answer the user's question briefly and naturally.

You may explain:

- what Stayzio is
- how Stayzio works
- searching for properties
- booking
- payment
- cancellation

Do not invent property names, prices, amenities, attractions, reviews, ratings,
availability, or policies.

USER:

{$userMessage}

PROMPT;

            return $this->generateReply(
                $prompt,
                $apiKey,
                $userMessage
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Booking help
        |--------------------------------------------------------------------------
        */

        if ($intent === 'booking_help') {
            return response()->json([
                'reply' =>
                    "🏨 To book a property on Stayzio:\n\n" .
                    "1️⃣ Search for a property.\n" .
                    "2️⃣ Select the property you want.\n" .
                    "3️⃣ Click Book Now.\n" .
                    "4️⃣ Select your check-in and check-out dates.\n" .
                    "5️⃣ Enter the required booking details.\n" .
                    "6️⃣ Complete the demo payment.\n" .
                    "7️⃣ The host can then approve the booking."
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Search real properties from database
        |--------------------------------------------------------------------------
        */

        $query = Property::query();

        /*
        |--------------------------------------------------------------------------
        | Budget
        |--------------------------------------------------------------------------
        */

        if ($budget !== null) {
            $query->where('price', '<=', $budget);
        }

        /*
        |--------------------------------------------------------------------------
        | Guest capacity
        |--------------------------------------------------------------------------
        */

        if ($guests !== null) {
            $query->where('max_guests', '>=', $guests);
        }

        /*
        |--------------------------------------------------------------------------
        | Location
        |--------------------------------------------------------------------------
        */

        if ($location !== null && $location !== '') {
            $query->where(function ($q) use ($location) {
                $q->where(
                    'location',
                    'LIKE',
                    '%' . $location . '%'
                )->orWhere(
                    'title',
                    'LIKE',
                    '%' . $location . '%'
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Date availability
        |--------------------------------------------------------------------------
        */

        $dateSearch = false;

        if ($checkIn && $checkOut) {
            try {
                $checkInDate = Carbon::createFromFormat(
                    'Y-m-d',
                    $checkIn
                )->startOfDay();

                $checkOutDate = Carbon::createFromFormat(
                    'Y-m-d',
                    $checkOut
                )->startOfDay();

                if ($checkOutDate->gt($checkInDate)) {
                    $dateSearch = true;

                    $query->whereDoesntHave(
                        'bookings',
                        function ($bookingQuery) use (
                            $checkInDate,
                            $checkOutDate
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
                                    $checkOutDate->toDateString()
                                )
                                ->where(
                                    'check_out',
                                    '>',
                                    $checkInDate->toDateString()
                                );
                        }
                    );
                }
            } catch (\Throwable $e) {
                $checkIn = null;
                $checkOut = null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Get actual database results
        |--------------------------------------------------------------------------
        */

        $properties = $query
            ->latest()
            ->limit(10)
            ->get([
                'id',
                'title',
                'location',
                'price',
                'max_guests'
            ]);

        /*
        |--------------------------------------------------------------------------
        | No matching properties
        |--------------------------------------------------------------------------
        */

        if ($properties->isEmpty()) {
            if ($dateSearch) {
                return response()->json([
                    'reply' =>
                        "❌ No property matching your requirements is available for those dates."
                ]);
            }

            return response()->json([
                'reply' =>
                    "❌ I couldn't find a matching property.\n\n" .
                    "Try changing the location, budget, or number of guests."
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Return real database results
        |--------------------------------------------------------------------------
        */

        $reply = "🏨 Here are the matching Stayzio properties:\n\n";

        foreach ($properties as $property) {
            $reply .=
                "🏨 {$property->title}\n" .
                "📍 {$property->location}\n" .
                "💰 ₹" . number_format($property->price, 2) . " per night\n" .
                "👥 Maximum guests: {$property->max_guests}\n\n";
        }

        if ($dateSearch) {
            $reply .=
                "📅 Availability was checked for " .
                Carbon::parse($checkIn)->format('d M Y') .
                " to " .
                Carbon::parse($checkOut)->format('d M Y') .
                ".";
        }

        return response()->json([
            'reply' => trim($reply)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Gemini reply
    |--------------------------------------------------------------------------
    */

    private function generateReply(
        string $prompt,
        string $apiKey,
        string $userMessage
    ) {
        try {
            $response = $this->callGemini(
                $prompt,
                $apiKey,
                false
            );

            if (!$response->successful()) {
                Log::error(
                    'Gemini API request failed while generating reply.',
                    [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]
                );

                return response()->json([
                    'reply' =>
                        $this->fallbackGeneralReply($userMessage)
                ]);
            }

            $data = $response->json();

            $reply =
                $data['candidates'][0]['content']['parts'][0]['text']
                ?? null;

            if (!$reply) {
                Log::error(
                    'Gemini returned no usable generated reply.',
                    [
                        'response' => $data,
                    ]
                );

                return response()->json([
                    'reply' =>
                        $this->fallbackGeneralReply($userMessage)
                ]);
            }

            return response()->json([
                'reply' => trim($reply)
            ]);
        } catch (\Throwable $e) {
            Log::error(
                'Gemini connection error while generating reply.',
                [
                    'message' => $e->getMessage(),
                ]
            );

            return response()->json([
                'reply' =>
                    $this->fallbackGeneralReply($userMessage)
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Fallback general responses
    |--------------------------------------------------------------------------
    */

    private function fallbackGeneralReply(string $userMessage): string
    {
        $message = strtolower(trim($userMessage));

        if (
            preg_match(
                '/^(hi|hello|hey|hii|helo|good morning|good afternoon|good evening)$/i',
                $message
            )
        ) {
            return
                "Hello! 👋 I'm Stayzio AI.\n\n" .
                "I can help you search for properties, understand booking steps, " .
                "payments, and cancellations.\n\n" .
                "How can I help you today?";
        }

        if (
            str_contains($message, 'what is stayzio') ||
            str_contains($message, 'what is stayzio?') ||
            str_contains($message, 'about stayzio')
        ) {
            return
                "🏨 Stayzio is a hotel booking website where users can " .
                "search for properties, check availability, make bookings, " .
                "and complete demo payments.\n\n" .
                "Hosts can also list and manage their properties.";
        }

        if (
            str_contains($message, 'how do i book') ||
            str_contains($message, 'how to book') ||
            str_contains($message, 'how can i book')
        ) {
            return
                "🏨 To book a property on Stayzio:\n\n" .
                "1️⃣ Search for a property.\n" .
                "2️⃣ Select the property you want.\n" .
                "3️⃣ Click Book Now.\n" .
                "4️⃣ Select your check-in and check-out dates.\n" .
                "5️⃣ Enter the required booking details.\n" .
                "6️⃣ Complete the demo payment.\n" .
                "7️⃣ The host can then approve the booking.";
        }

        return
            "I'm Stayzio AI, your hotel booking assistant. 🏨\n\n" .
            "I can help you search for properties, understand how booking " .
            "works, and explain Stayzio's booking process.";
    }

    /*
    |--------------------------------------------------------------------------
    | Gemini API request
    |--------------------------------------------------------------------------
    */

    private function callGemini(
        string $prompt,
        string $apiKey,
        bool $jsonResponse = false
    ) {
        $request = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        [
                            'text' => $prompt
                        ]
                    ]
                ]
            ]
        ];

        if ($jsonResponse) {
            $request['generationConfig'] = [
                'responseMimeType' => 'application/json',
            ];
        }

        return Http::timeout(30)
            ->retry(1, 1000)
            ->withHeaders([
                'x-goog-api-key' => $apiKey,
                'Content-Type' => 'application/json',
            ])
            ->post($this->geminiUrl, $request);
    }
}