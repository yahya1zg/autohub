<?php
require_once 'includes/api_client.php';

// Kullanıcı zaten giriş yapmışsa, başka bir sayfaya yönlendir
if (isset($_SESSION['user_token'])) {
    header('Location: araclar.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Girdilerin boş olup olmadığını kontrol et
    if (empty($email) || empty($password)) {
        $error = "E-posta ve şifre alanları zorunludur.";
    } else {
        // API'ye giriş isteği gönder
        $response = api_request('/login', 'POST', [
            'email' => $email,
            'password' => $password,
        ]);

        // API'den başarılı bir yanıt geldiyse (token içeriyorsa)
        if (isset($response['body']['access_token']) && isset($response['body']['user'])) {
            // Oturum değişkenlerini ayarla
            $_SESSION['user_token'] = $response['body']['access_token'];
            $_SESSION['user_data'] = $response['body']['user'];
            
            // Kullanıcıyı araçlar sayfasına yönlendir
            header('Location: araclar.php');
            exit;
        } else {
            // API'den gelen hata mesajını al, yoksa genel bir hata mesajı kullan
            $error = $response['body']['message'] ?? 'Giriş başarısız. Bilgilerinizi kontrol edip tekrar deneyin.';
        }
    }
}

$pageTitle = "Giriş Yap";
include 'includes/header.php';
?>
<main class="container form-container">
    <form method="POST" action="login.php" class="user-form">
        <h1>Giriş Yap</h1>
        
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <input type="email" name="email" placeholder="E-posta Adresiniz" required>
        <input type="password" name="password" placeholder="Şifreniz" required>
        <button type="submit">Giriş Yap</button>
    </form>
</main>
<?php include 'includes/footer.php'; ?>