<?php
// Oturum başlatılmamışsa başlat
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('API_BASE_URL', 'http://127.0.0.1:8000/api');

function api_request(string $endpoint, string $method = 'GET', array $data = [])
{
    $url = API_BASE_URL . $endpoint;
    // Oturumda saklanan token'ı al
    $token = $_SESSION['user_token'] ?? null;

    $ch = curl_init();
    
    // Standart başlıkları ayarla
    $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
    ];

    // Eğer token varsa, Authorization başlığını isteğe ekle
    if ($token) {
        $headers[] = "Authorization: Bearer {$token}";
    }
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, 1);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'body' => json_decode($response, true),
        'status_code' => $http_code
    ];
}
