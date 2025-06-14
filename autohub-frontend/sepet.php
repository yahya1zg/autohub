<?php
require_once 'includes/api_client.php';

// Kullanıcı giriş yapmamışsa, login sayfasına yönlendir
if (!isset($_SESSION['user_token'])) {
    header('Location: login.php');
    exit;
}

// POST isteğini handle et (sepetten çıkarma veya satın alma)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id_to_process = $_POST['car_id'] ?? null;

    if ($car_id_to_process) {
        // Sepetten çıkarma işlemi
        if (isset($_POST['remove_from_cart'])) {
            api_request('/cart/remove', 'POST', ['car_id' => $car_id_to_process]);
        }
        // Satın alma işlemi
        elseif (isset($_POST['purchase'])) {
            $purchase_response = api_request("/purchase", 'POST', ['car_id' => $car_id_to_process]);
            $param = ($purchase_response['status_code'] === 201) ? 'purchased=true' : 'purchase_failed=true';
            header("Location: sepet.php?{$param}");
            exit;
        }
    }
    // İşlemden sonra sayfanın güncel halini göstermek için yeniden yönlendir
    header("Location: sepet.php");
    exit;
}

// API'den sepet içeriğini çek
$response = api_request('/cart');
$cart_items = $response['body'] ?? [];
$total_price = (is_array($cart_items) && !empty($cart_items)) ? array_sum(array_column($cart_items, 'price')) : 0;

$pageTitle = "Sepetim";
include 'includes/header.php';
?>
<style>
/* Sepet sayfası için özel stiller */
.cart-item { display: flex; align-items: center; background-color: var(--surface-color); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; gap: 1.5rem; }
.cart-item img { width: 150px; height: 100px; object-fit: cover; border-radius: 4px; }
.cart-item-info { flex-grow: 1; }
.cart-item-info h3 { margin: 0 0 0.5rem 0; }
.cart-item-price { font-size: 1.2rem; font-weight: 600; }
.cart-actions { display: flex; flex-direction: column; gap: 0.5rem; }
.cart-actions button { padding: 0.5rem 1rem; cursor: pointer; border: none; border-radius: 5px; font-weight: 500;}
.cart-actions .remove-btn { background-color: #5c1f1f; color: #ffcccc; transition: background-color 0.2s; }
.cart-actions .remove-btn:hover { background-color: #8c2d2d; }
.cart-summary { border-top: 1px solid var(--border-color); margin-top: 2rem; padding-top: 2rem; display: flex; justify-content: space-between; align-items: center; }
.cart-total { font-size: 1.5rem; font-weight: 600; }
</style>

<main class="container">
    <div class="page-header">
        <h1>Alışveriş Sepetim</h1>
    </div>

    <?php if(isset($_GET['purchased'])): ?><p class="success">Tebrikler! Satın alma işlemi başarıyla tamamlandı.</p><?php endif; ?>
    <?php if(isset($_GET['purchase_failed'])): ?><p class="error">Satın alma başarısız oldu. Araç daha önce satılmış olabilir.</p><?php endif; ?>

    <?php if (!empty($cart_items)): ?>
        <?php foreach($cart_items as $item): ?>
            <div class="cart-item">
                <img src="<?php echo htmlspecialchars($item['main_image_url']); ?>" onerror="this.src='https://placehold.co/150x100/1E1E1E/FFFFFF?text=G%C3%B6rsel+Yok'" alt="<?php echo htmlspecialchars($item['model_name']); ?>">
                <div class="cart-item-info">
                    <h3><?php echo htmlspecialchars($item['model_name']); ?></h3>
                    <p class="cart-item-price"><?php echo number_format($item['price'], 0, ',', '.'); ?> TL</p>
                </div>
                <div class="cart-actions">
                    <form method="POST" onsubmit="return confirm('Bu aracı satın almak istediğinizden emin misiniz?');">
                        <input type="hidden" name="car_id" value="<?php echo $item['id']; ?>">
                        <button type="submit" name="purchase" class="purchase-btn">Satın Al</button>
                    </form>
                    <form method="POST" onsubmit="return confirm('Bu aracı sepetten kaldırmak istediğinizden emin misiniz?');">
                        <input type="hidden" name="car_id" value="<?php echo $item['id']; ?>">
                        <button type="submit" name="remove_from_cart" class="remove-btn">Kaldır</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="cart-summary">
             <div class="cart-total">
                <strong>Toplam Tutar:</strong> <?php echo number_format($total_price, 0, ',', '.'); ?> TL
            </div>
        </div>
    <?php else: ?>
        <p style="text-align: center; color: var(--secondary-text-color);">Sepetinizde henüz ürün bulunmuyor.</p>
    <?php endif; ?>
</main>
<?php include 'includes/footer.php'; ?>