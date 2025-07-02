<?php
require_once 'includes/api_client.php';

// Kullanıcı giriş yapmamışsa, login sayfasına yönlendir
if (!isset($_SESSION['user_token'])) {
    header('Location: login.php');
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'model_name' => $_POST['model_name'] ?? '',
        'series' => $_POST['series'] ?? '',
        'fuel_type' => $_POST['fuel_type'] ?? '',
        'price' => $_POST['price'] ?? 0,
        'year' => $_POST['year'] ?? 2025,
        'description' => $_POST['description'] ?? '',
        'main_image_url' => $_POST['main_image_url'] ?? '',
    ];

    $response = api_request('/cars', 'POST', $data);

    if ($response['status_code'] === 201) {
        $success = "Araç başarıyla ilana eklendi!";
    } else {
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

$pageTitle = "Yeni Araç Ekle";
include 'includes/header.php';
?>
<main class="container">
    <div class="page-header">
        <h1>Yeni Araç İlanı Ekle</h1>
    </div>

    <form method="POST" action="arac-ekle.php" class="user-form" style="max-width: 700px;">
        <?php if ($error): ?><p class="error"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
        <?php if ($success): ?><p class="success"><?php echo htmlspecialchars($success); ?></p><?php endif; ?>

        <input type="text" name="model_name" placeholder="Model Adı (Örn: C 200 4MATIC)" required>
        <input type="text" name="series" placeholder="Seri (Örn: C-Serisi)" required>
        <input type="text" name="fuel_type" placeholder="Yakıt Tipi (Örn: Benzin, Dizel, Elektrik)" required>
        <input type="number" name="price" placeholder="Fiyat (TL)" step="1000" required>
        <input type="number" name="year" placeholder="Yıl" required>
        <input type="url" name="main_image_url" placeholder="Ana Fotoğraf URL'si" required>
        <textarea name="description" placeholder="Araç Açıklaması" rows="6" required></textarea>

        <button type="submit">İlanı Yayınla</button>
    </form>
</main>
<?php include 'includes/footer.php'; ?>
<style>textarea { width: 100%; padding: 0.8rem 1rem; margin-bottom: 1.5rem; border: 1px solid #333; border-radius: 8px; background-color: var(--background-color); color: var(--primary-text-color); font-size: 1rem; box-sizing: border-box; font-family: inherit; }</style>
