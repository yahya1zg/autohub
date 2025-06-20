<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handles user registration.
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['access_token' => $token, 'token_type' => 'Bearer', 'user' => $user]);
    }

    /**
     * Handles user login.
     */
    public function login(Request $request)
    {
        // Gelen e-posta ve şifreyi doğrula
        $credentials = $request->only('email', 'password');

        // ÖNEMLİ DÜZELTME: Giriş denemesini 'web' guard'ı üzerinden yap.
        // Bu, 'attempt' metodunun bulunmasını sağlar.
        if (!Auth::guard('web')->attempt($credentials)) {
            // Eğer karşılaştırma başarısız olursa, hata döndür.
            return response()->json(['message' => 'Geçersiz giriş bilgileri. Lütfen e-posta ve şifrenizi kontrol edin.'], 401);
        }

        // Eğer giriş başarılıysa, kullanıcıyı bul ve API token'ı oluştur.
        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * Handles user logout.
     */
    public function logout(Request $request)
    {
        // İstekle gelen token'ı geçersiz kıl
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Başarıyla çıkış yapıldı']);
    }
}
