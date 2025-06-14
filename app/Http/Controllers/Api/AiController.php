<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    public function recommendCar(Request $request)
    {
        $userQuery = $request->validate(['query' => 'required|string|max:500'])['query'];
        $availableCars = Car::where('status', 'available')->get(['id', 'model_name', 'series', 'fuel_type', 'price', 'year', 'description'])->toArray();

        if (empty($availableCars)) {
            return response()->json([]);
        }

        $prompt = "You are a helpful car sales assistant for a website called AutoHub. Based on the user's request, recommend up to 3 cars from the provided list. For each recommendation, provide a short, one-sentence reason why it's a good fit. User's request: \"{$userQuery}\". Here is the list of available cars in JSON format: " . json_encode($availableCars);

        $jsonSchema = [
            'type' => 'object',
            'properties' => [
                'recommendations' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'car_id' => ['type' => 'integer'],
                            'reason' => ['type' => 'string'],
                        ],
                        'required' => ['car_id', 'reason'],
                    ],
                ],
            ],
        ];
        
        $payload = [
            'contents' => [['role' => 'user', 'parts' => [['text' => $prompt]]]],
            'generationConfig' => ['responseMimeType' => 'application/json', 'responseSchema' => $jsonSchema]
        ];

        $apiKey = ""; 
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";
        
        $response = Http::post($apiUrl, $payload);

        if (!$response->successful()) {
            return response()->json(['error' => 'AI assistant is currently unavailable.'], 503);
        }

        $result = $response->json();
        $recommendationData = json_decode($result['candidates'][0]['content']['parts'][0]['text'], true);
        
        if (empty($recommendationData['recommendations'])) {
             return response()->json(['error' => 'Could not find a suitable car for your request.'], 404);
        }

        $recommendedIds = array_column($recommendationData['recommendations'], 'car_id');
        $reasons = array_column($recommendationData['recommendations'], 'reason', 'car_id');

        $recommendedCars = Car::find($recommendedIds)->map(function ($car) use ($reasons) {
            $car->ai_reason = $reasons[$car->id] ?? 'This is a great choice.';
            return $car;
        });

        return response()->json($recommendedCars);
    }
}