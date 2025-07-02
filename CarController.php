<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        // Sadece satılmamış ve başkaları tarafından eklenmiş araçları göster
        $cars = Car::where('status', 'available')->latest()->get();
        return response()->json($cars);
    }

    public function show(string $id)
    {
        $car = Car::find($id);
        return $car ? response()->json($car) : response()->json(['message' => 'Araç bulunamadı'], 404);
    }

    /**
     * YENİ: Giriş yapmış kullanıcının yeni bir araba eklemesini sağlar.
     */
    public function store(Request $request)
    {
        // Gelen verilerin doğruluğunu kontrol et
        $validatedData = $request->validate([
            'model_name' => 'required|string|max:255',
            'series' => 'required|string|max:255',
            'fuel_type' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'year' => 'required|integer|min:1900|max:'.(date('Y') + 1),
            'description' => 'required|string',
            'main_image_url' => 'required|url',
        ]);
        
        // Doğrulanmış veriyi, isteği yapan kullanıcı ile ilişkilendirerek oluştur.
        $car = $request->user()->cars()->create($validatedData);

        return response()->json($car, 201); // 201 Created
    }
}
