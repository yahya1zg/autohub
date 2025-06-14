<?php
require_once 'includes/api_client.php';

// Kullanıcı giriş yapmamışsa, login sayfasına yönlendir
if (!isset($_SESSION['user_token'])) {
    header('Location: login.php');
    exit;
}

// API'den sipariş geçmişini çek
$response = api_request('/orders');
$orders = $response['body'] ?? [];

$pageTitle = "Siparişlerim";
include 'includes/header.php';
?>
<style>
    .account-layout { display: flex; gap: 2rem; align-items: flex-start; }
    .account-sidebar { flex: 0 0 250px; }
    .account-content { flex-grow: 1; }
    .account-menu { list-style: none; padding: 0; margin: 0; }
    .account-menu li a { display: block; padding: 1rem; color: var(--secondary-text-color); text-decoration: none; border-radius: 8px; transition: all 0.2s; font-weight: 500; display: flex; align-items: center; }
    .account-menu li a:hover { background-color: var(--surface-color); color: var(--primary-text-color); }
    .account-menu li a.active { background-color: var(--accent-color); color: #000; }
    .account-menu .icon { width: 20px; margin-right: 0.75rem; text-align: center; }
    .logout-btn { color: #ff4d4d !important; }
    /* Sipariş sayfası için özel stiller */
    .order-item { background-color: var(--surface-color); padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid var(--border-color); }
    .order-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1rem; }
    .order-header h3 { margin: 0; font-size: 1.2rem; }
    .order-date { font-size: 0.9rem; color: var(--secondary-text-color); }
    .order-details { display: flex; gap: 1.5rem; align-items: center; }
    .order-details img { width: 120px; height: 80px; object-fit: cover; border-radius: 4px; }
    .order-car-info p { margin: 0; }
    .order-car-info .price { font-weight: 600; color: var(--primary-text-color); }
</style>
<main class="container">
    <div class="page-header">
        <h1>Siparişlerim</h1>
    </div>

    <div class="account-layout">
        <aside class="account-sidebar">
             <ul class="account-menu">
                <li><a href="hesabim.php"><i class="icon fa-solid fa-user"></i> Profil Bilgileri</a></li>
                <li><a href="siparislerim.php" class="active"><i class="icon fa-solid fa-file-invoice-dollar"></i> Siparişlerim</a></li>
                <li><a href="favorilerim.php"><i class="icon fa-solid fa-star"></i> Favorilerim</a></li>
                <li><a href="sepet.php"><i class="icon fa-solid fa-cart-shopping"></i> Sepetim</a></li>
                <li><a href="logout.php" class="logout-btn"><i class="icon fa-solid fa-right-from-bracket"></i> Güvenli Çıkış</a></li>
            </ul>
        </aside>
        <section class="account-content">
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-item">
                        <div class="order-header">
                            <h3>Sipariş ID: #<?php echo $order['id']; ?></h3>
                            <span class="order-date"><?php echo date('d F Y', strtotime($order['purchase_date'])); ?></span>
                        </div>
                        <div class="order-details">
                            <?php if (isset($order['car'])): ?>
                                <img src="<?php echo htmlspecialchars($order['car']['main_image_url']); ?>" onerror="this.src='https://placehold.co/120x80/1E1E1E/FFFFFF?text=G%C3%B6rsel+Yok'" alt="<?php echo htmlspecialchars($order['car']['model_name']); ?>">
                                <div class="order-car-info">
                                    <p><strong><?php echo htmlspecialchars($order['car']['model_name']); ?></strong></p>
                                    <p class="price"><?php echo number_format($order['purchase_price'], 0, ',', '.'); ?> TL</p>
                                </div>
                            <?php else: ?>
                                <p>Araç bilgisi bulunamadı.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: var(--secondary-text-color);">Henüz hiç sipariş vermediniz.</p>
            <?php endif; ?>
        </section>
    </div>
</main>
<?php include 'includes/footer.php'; ?>