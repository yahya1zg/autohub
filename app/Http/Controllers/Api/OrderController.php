<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Giriş yapmış kullanıcının geçmiş siparişlerini listeler.
     */
    public function index(Request $request)
    {
        // 'with('car')' komutu, her siparişe ait araba bilgisini de yükler.
        $orders = $request->user()->orders()->with('car')->latest()->get();
        return response()->json($orders);
    }
}
