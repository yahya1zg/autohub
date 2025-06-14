<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate(['car_id' => 'required|exists:cars,id']);
        $car = Car::find($validatedData['car_id']);
        $user = $request->user();

        if ($car->status === 'sold') {
            return response()->json(['message' => 'Bu araç zaten satılmış.'], 422);
        }

        DB::transaction(function () use ($user, $car) {
            // Arabanın durumunu 'sold' olarak güncelle
            $car->status = 'sold';
            $car->save();

            // Sipariş kaydı oluştur
            Order::create([
                'user_id' => $user->id,
                'car_id' => $car->id,
                'purchase_price' => $car->price,
                'purchase_date' => now(),
            ]);

            // Arabayı kullanıcının sepetinden kaldır
            $user->cartItems()->detach($car->id);
        });

        return response()->json(['message' => 'Araç başarıyla satın alındı!'], 201);
    }
}
