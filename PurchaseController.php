<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        // Önce kullanıcının varlığını kontrol et
        if (!$request->user()) {
            return response()->json(['message' => 'Sipariş vermek için giriş yapmalısınız.'], 401);
        }

        $validatedData = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'customer_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
        ]);
        
        $car = Car::find($validatedData['car_id']);
        $user = $request->user();

        if ($car->status === 'sold') {
            return response()->json(['message' => 'Üzgünüz, bu araç siz işlemi tamamlarken satıldı.'], 422);
        }

        try {
            DB::transaction(function () use ($user, $car, $validatedData) {
                // Arabanın durumunu 'sold' olarak güncelle
                $car->status = 'sold';
                $car->save();

                // Sipariş kaydını yeni bilgilerle oluştur
                Order::create([
                    'user_id' => $user->id,
                    'car_id' => $car->id,
                    'purchase_price' => $car->price,
                    'purchase_date' => now(),
                    'customer_name' => $validatedData['customer_name'],
                    'phone_number' => $validatedData['phone_number'],
                    'address' => $validatedData['address'],
                    'city' => $validatedData['city'],
                ]);

                // Arabayı kullanıcının sepetinden kaldır
                $user->cartItems()->detach($car->id);
            });
        } catch (Exception $e) {
            // Veritabanı hatası olursa, detaylı bir hata döndür
            return response()->json(['message' => 'Veritabanı hatası nedeniyle sipariş oluşturulamadı.'], 500);
        }

        return response()->json(['message' => 'Siparişiniz başarıyla alındı!'], 201);
    }
}
