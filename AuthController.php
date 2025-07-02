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
     * Handles login for both regular users and admins.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // 'web' guard'ını kullanarak giriş denemesi yap
        if (!Auth::guard('web')->attempt($credentials)) {
            return response()->json(['message' => 'Geçersiz giriş bilgileri. Lütfen e-posta ve şifrenizi kontrol edin.'], 401);
        }

        // Giriş başarılıysa, kullanıcıyı al
        $user = Auth::guard('web')->user();
        // Kullanıcı için bir API token'ı oluştur
        $token = $user->createToken('auth_token')->plainTextToken;

        // Frontend'e gönderilecek yanıta kullanıcının rol bilgisini de ekle
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role, // Bu bilgi frontend'de yönlendirme için kullanılacak
            ]
        ]);
    }

    /**
     * Handles user logout.
     */
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }
        
        return response()->json(['message' => 'Başarıyla çıkış yapıldı']);
    }
}
