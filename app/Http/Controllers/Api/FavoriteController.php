<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user()->favoriteCars()->get());
    }

    public function toggle(Request $request, $carId)
    {
        // Önce kullanıcının varlığını kontrol et
        if (!$request->user()) {
            return response()->json(['message' => 'Bu işlem için giriş yapmalısınız.'], 401);
        }

        $request->user()->favoriteCars()->toggle($carId);
        return response()->json(['message' => 'Favori durumu güncellendi']);
    }
}
