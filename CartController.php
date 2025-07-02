<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Sepet içeriğini getir
    public function index(Request $request)
    {
        $cartItems = $request->user()->cartItems()->where('status', 'available')->get();
        return response()->json($cartItems);
    }

    // Sepete ekle
    public function add(Request $request)
    {
        // Önce kullanıcının varlığını kontrol et
        if (!$request->user()) {
            return response()->json(['message' => 'Bu işlem için giriş yapmalısınız.'], 401);
        }

        $validatedData = $request->validate(['car_id' => 'required|exists:cars,id']);
        $car = Car::find($validatedData['car_id']);

        if ($car->status === 'sold') {
            return response()->json(['message' => 'Bu araç satıldığı için sepete eklenemez.'], 422);
        }

        $request->user()->cartItems()->syncWithoutDetaching($validatedData['car_id']);
        
        return response()->json(['message' => 'Araç sepete eklendi.'], 200);
    }

    // Sepetten kaldır
    public function remove(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Bu işlem için giriş yapmalısınız.'], 401);
        }
        
        $validatedData = $request->validate(['car_id' => 'required|exists:cars,id']);
        $request->user()->cartItems()->detach($validatedData['car_id']);

        return response()->json(['message' => 'Araç sepetten kaldırıldı.'], 200);
    }
}