<?php
require_once 'includes/api_client.php';
$car_id = $_GET['id'] ?? null;
if (!$car_id) { header('Location: araclar.php'); exit; }

$user_logged_in = isset($_SESSION['user_token']);

// POST isteğini handle et
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_logged_in) {
    $response = null;
    if (isset($_POST['toggle_favorite'])) {
        $response = api_request("/favorites/{$car_id}", 'POST');
        $successMessage = 'Favori durumu güncellendi.';
        $errorMessage = 'Favori durumu güncellenemedi.';
    } 
    elseif (isset($_POST['add_to_cart'])) {
        $response = api_request("/cart/add", 'POST', ['car_id' => $car_id]);
        $successMessage = 'Araç başarıyla sepete eklendi.';
        $errorMessage = 'Araç sepete eklenemedi.';
    }
    
    if ($response) {
        $messageText = $response['body']['message'] ?? $errorMessage;
        $_SESSION['flash_message'] = $response['status_code'] === 200 || $response['status_code'] === 201 ?
            ['type' => 'success', 'text' => $successMessage] :
            ['type' => 'error', 'text' => $messageText];
    }
    
    header("Location: arac-detay.php?id={$car_id}");
    exit;
}

// Flash mesajını al ve oturumdan temizle
$flash_message = $_SESSION['flash_message'] ?? null;
if ($flash_message) {
    unset($_SESSION['flash_message']);
}

// Diğer kodlar aynı şekilde devam ediyor...
$car_response = api_request("/cars/{$car_id}");
$car = $car_response['body'] ?? null;
$is_favorite = false;
$is_in_cart = false;
if ($user_logged_in && $car) {
    $fav_response = api_request('/favorites');
    if ($fav_response['status_code'] === 200 && is_array($fav_response['body'])) {
        $is_favorite = in_array($car_id, array_column($fav_response['body'], 'id'));
    }
    $cart_response = api_request('/cart');
    if ($cart_response['status_code'] === 200 && is_array($cart_response['body'])) {
        $is_in_cart = in_array($car_id, array_column($cart_response['body'], 'id'));
    }
}
$pageTitle = $car['model_name'] ?? 'Araç Detayı';
include 'includes/header.php';
?>
<main class="container">
    <?php if ($flash_message): ?>
        <p class="<?php echo $flash_message['type']; ?>"><?php echo htmlspecialchars($flash_message['text']); ?></p>
    <?php endif; ?>
    <!-- Sayfanın geri kalanı aynı -->
    <?php if ($car): ?>
        <div class="car-detail-layout">
            <div class="car-image">
                <img src="<?php echo htmlspecialchars($car['main_image_url']); ?>" onerror="this.src='https://placehold.co/1200x800/1E1E1E/FFFFFF?text=G%C3%B6rsel+Yok&font=inter'" alt="<?php echo htmlspecialchars($car['model_name']); ?>">
            </div>
            <div class="car-details-card">
                <p class="series"><?php echo htmlspecialchars($car['series']); ?></p>
                <h1><?php echo htmlspecialchars($car['model_name']); ?></h1>
                <p class="price"><?php echo number_format($car['price'], 0, ',', '.'); ?> TL</p>
                <p class="description"><?php echo nl2br(htmlspecialchars($car['description'])); ?></p>
                <div class="actions">
                    <?php if ($user_logged_in): ?>
                        <form method="POST"><button type="submit" name="toggle_favorite" class="fav-btn"><?php echo $is_favorite ? '★ Favorilerden Kaldır' : '☆ Favorilere Ekle'; ?></button></form>
                        <?php if ($car['status'] === 'available'): ?>
                             <?php if ($is_in_cart): ?>
                                <a href="sepet.php" class="purchase-btn" style="text-align:center; text-decoration:none; display:block;">Sepete Git</a>
                             <?php else: ?>
                                <form method="POST"><button type="submit" name="add_to_cart" class="purchase-btn">Sepete Ekle</button></form>
                             <?php endif; ?>
                        <?php else: ?>
                            <p class="sold-out">BU ARAÇ SATILMIŞTIR</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p>İşlem yapmak için lütfen <a href="login.php">giriş yapın</a>.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="page-header"><h1>Araç Bulunamadı</h1></div>
    <?php endif; ?>
</main>
<?php include 'includes/footer.php'; ?>
