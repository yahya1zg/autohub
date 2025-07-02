<?php
require_once 'includes/api_client.php';
if (!isset($_SESSION['user_token'])) { header('Location: login.php'); exit; }

$car_id = $_GET['car_id'] ?? null;
if (!$car_id) { header('Location: sepet.php'); exit; }

$car_response = api_request("/cars/{$car_id}");
$car = $car_response['body'] ?? null;

if (!$car || $car['status'] !== 'available') {
    header('Location: sepet.php?error_message=' . urlencode('Seçilen araç artık mevcut değil.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'car_id' => $car_id,
        'customer_name' => $_POST['customer_name'],
        'phone_number' => $_POST['phone_number'],
        'address' => $_POST['address'],
        'city' => $_POST['city'],
    ];
    $purchase_response = api_request("/purchase", 'POST', $data);

    if($purchase_response['status_code'] === 201) {
        // Başarılı olursa siparişlerim sayfasına yönlendir
        header("Location: siparislerim.php?success=true");
    } else {
        // Başarısız olursa, API'den gelen gerçek hata mesajını sepet sayfasına gönder
        $errorMessage = urlencode($purchase_response['body']['message'] ?? 'Bilinmeyen bir hata nedeniyle satın alma başarısız oldu.');
        header("Location: sepet.php?error_message={$errorMessage}");
    }
    exit;
}

$pageTitle = "Siparişi Onayla";
include 'includes/header.php';
?>
<main class="container">
    <div class="page-header"><h1>Siparişi Onayla</h1></div>
    <div class="admin-grid">
        <section class="admin-section">
            <h2>Teslimat Bilgileri</h2>
            <form method="POST" class="user-form" style="padding:0; box-shadow:none;">
                <input type="text" name="customer_name" value="<?php echo htmlspecialchars($_SESSION['user_data']['name']); ?>" placeholder="Ad Soyad" required>
                <input type="tel" name="phone_number" placeholder="Telefon Numarası" required>
                <textarea name="address" placeholder="Teslimat Adresi" rows="4" required></textarea>
                <input type="text" name="city" placeholder="Şehir" required>
                <button type="submit">Siparişi Tamamla</button>
            </form>
        </section>
        <section class="admin-section">
            <h2>Sipariş Özeti</h2>
            <div class="cart-item">
                <img src="<?php echo htmlspecialchars($car['main_image_url']); ?>" style="width:100px;">
                <div class="cart-item-info">
                    <h3><?php echo htmlspecialchars($car['model_name']); ?></h3>
                    <p class="cart-item-price"><?php echo number_format($car['price'], 0, ',', '.'); ?> TL</p>
                </div>
            </div>
            <div class="cart-total" style="text-align:left;">
                <strong>Genel Toplam: <?php echo number_format($car['price'], 0, ',', '.'); ?> TL</strong>
            </div>
        </section>
    </div>
</main>
<?php include 'includes/footer.php'; ?>
<style>textarea{width: 100%;padding: 0.8rem 1rem;margin-bottom: 1.5rem;border: 1px solid #333;border-radius: 8px;background-color: var(--background-color);color: var(--primary-text-color);font-size: 1rem;box-sizing: border-box;font-family: inherit;}</style>