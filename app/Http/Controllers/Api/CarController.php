<?php
// Araç listeleme ve detay işlemlerini yönetir.
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::where('status', 'available')->latest()->get();
        return response()->json($cars);
    }

    public function show(string $id)
    {
        $car = Car::find($id);
        return $car ? response()->json($car) : response()->json(['message' => 'Araç bulunamadı'], 404);
    }
}
