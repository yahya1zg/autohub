<?php
require_once 'includes/api_client.php';

// API'ye çıkış isteği göndererek sunucudaki token'ı geçersiz kıl
if (isset($_SESSION['user_token'])) {
    api_request('/logout', 'POST');
}

// PHP oturumunu temizle
session_unset();
session_destroy();

// Kullanıcıyı ana sayfaya yönlendir
header('Location: index.php');
exit;
// include 'includes/footer.php'; // İsteğe bağlı olarak footer eklenebilir