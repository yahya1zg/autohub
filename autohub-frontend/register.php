<?php
require_once 'includes/api_client.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirmation = $_POST['password_confirmation'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        $error = "Tüm alanları doldurmak zorunludur.";
    } elseif ($password !== $password_confirmation) {
        $error = "Girdiğiniz şifreler uyuşmuyor.";
    } else {
        // API'ye kayıt isteği gönder
        $response = api_request('/register', 'POST', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password_confirmation,
        ]);

        // API'den gelen yanıta göre işlem yap
        if (isset($response['body']['access_token'])) {
            $success = "Kayıt başarılı! Araçlar sayfasına yönlendiriliyorsunuz...";
            
            // Kullanıcıyı otomatik olarak giriş yaptır
            $_SESSION['user_token'] = $response['body']['access_token'];
            $_SESSION['user_data'] = $response['body']['user'];
            
            // 2 saniye sonra yönlendir
            header('Refresh: 2; URL=araclar.php');
        } else {
            // API'den gelen hata mesajlarını birleştir
            $error_messages = [];
            if (isset($response['body']['errors'])) {
                foreach ($response['body']['errors'] as $field_errors) {
                    $error_messages = array_merge($error_messages, $field_errors);
                }
            } else {
                $error_messages[] = $response['body']['message'] ?? 'Bilinmeyen bir hata oluştu.';
            }
            $error = implode(' ', $error_messages);
        }
    }
}

$pageTitle = "Kayıt Ol";
include 'includes/header.php';
?>
<main class="container form-container">
    <form method="POST" action="register.php" class="user-form">
        <h1>Hesap Oluştur</h1>

        <?php if ($error): ?><p class="error"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
        <?php if ($success): ?><p class="success"><?php echo htmlspecialchars($success); ?></p><?php endif; ?>
        
        <input type="text" name="name" placeholder="Adınız Soyadınız" required>
        <input type="email" name="email" placeholder="E-posta Adresiniz" required>
        <input type="password" name="password" placeholder="Şifreniz" required>
        <input type="password" name="password_confirmation" placeholder="Şifreniz (Tekrar)" required>
        <button type="submit">Kayıt Ol</button>
    </form>
</main>
<?php include 'includes/footer.php'; ?>