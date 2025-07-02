<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Car;
use App\Models\Order;

class AdminController extends Controller
{
    public function getUsers()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return response()->json($users);
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Kullanıcı başarıyla silindi.']);
    }

    public function getCars()
    {
        $cars = Car::latest()->get();
        return response()->json($cars);
    }

    public function deleteCar(Car $car)
    {
        $car->delete();
        return response()->json(['message' => 'Araç ilanı başarıyla silindi.']);
    }

    public function getOrders()
    {
        $orders = Order::with(['user', 'car'])->latest()->get();
        return response()->json($orders);
    }
}
