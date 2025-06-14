<?php
require_once 'includes/api_client.php';

// Kullanıcı giriş yapmamışsa, login sayfasına yönlendir
if (!isset($_SESSION['user_token'])) {
    header('Location: login.php');
    exit;
}

// API'den favori araçları çek
$response = api_request('/favorites');
$cars = $response['body'] ?? [];

$pageTitle = "Favorilerim";
include 'includes/header.php';
?>
<main class="container">
    <div class="page-header">
        <h1>Favori Araçlarım</h1>
    </div>

    <div class="car-grid">
        <?php if ($cars && $response['status_code'] === 200): ?>
            <?php foreach ($cars as $car): ?>
                <div class="car-card">
                    <a href="arac-detay.php?id=<?php echo htmlspecialchars($car['id']); ?>">
                        <img src="<?php echo htmlspecialchars($car['main_image_url']); ?>" alt="<?php echo htmlspecialchars($car['model_name']); ?>">
                        <div class="car-info">
                            <h3><?php echo htmlspecialchars($car['model_name']); ?></h3>
                            <p><?php echo number_format($car['price'], 0, ',', '.'); ?> TL</p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php elseif (empty($cars)): ?>
            <p style="text-align: center; color: var(--secondary-text-color);">Henüz favori aracınız bulunmuyor. Araçları inceleyerek favorilerinize ekleyebilirsiniz.</p>
        <?php else: ?>
            <p style="text-align: center; color: #ff4d4d;">Favoriler yüklenemedi. Lütfen daha sonra tekrar deneyin.</p>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>