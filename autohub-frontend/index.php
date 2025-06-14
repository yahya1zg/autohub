<?php
require_once 'includes/api_client.php';

// Öne çıkan araçlar bölümü için API'den son eklenen 3 aracı çekelim.
$response = api_request('/cars');
// API'den gelen araç dizisinin sadece ilk 3 elemanını alalım.
$featured_cars = is_array($response['body']) ? array_slice($response['body'], 0, 3) : [];

$pageTitle = "Ana Sayfa";
include 'includes/header.php';
?>
<!-- Gelişmiş ana sayfa için özel ek stiller -->
<style>
    .section {
        padding: 4rem 0;
        border-bottom: 1px solid var(--border-color);
    }
    .section-title {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 3rem;
    }

    /* Hizmetler Bölümü */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        text-align: center;
    }
    .service-item {
        background-color: var(--surface-color);
        padding: 2.5rem 2rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }
    .service-item .icon {
        font-size: 3rem;
        color: var(--accent-color);
        margin-bottom: 1.5rem;
    }
    .service-item h3 {
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }
    .service-item p {
        color: var(--secondary-text-color);
        line-height: 1.6;
    }

    /* Tanıtım Bölümü */
    .promo-section {
        display: flex;
        align-items: center;
        gap: 3rem;
    }
    .promo-section .promo-text {
        flex: 1;
    }
    .promo-section .promo-image {
        flex: 1;
    }
    
    /* YENİ: Değişen Fotoğraf Alanı Stilleri */
    .promo-image-slider {
        position: relative;
        width: 100%;
        padding-top: 75%; /* 4:3 aspect ratio */
        border-radius: 12px;
        overflow: hidden;
        background-color: var(--surface-color);
    }
    .slider-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 1.5s ease-in-out; /* Geçiş efekti */
    }
    .slider-image.active {
        opacity: 1;
    }
</style>

<main>
    <!-- 1. BÖLÜM: KARŞILAMA EKRANI (HERO) -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>PERFORMANS SANATI</h1>
            <p>Sürüş tutkunuzu bir sonraki seviyeye taşıyın. AutoHub, gücü ve zarafeti birleştiren seçkin araçları sizin için bir araya getiriyor.</p>
            <a href="araclar.php" class="cta-button">KOLEKSİYONU GÖR</a>
        </div>
    </section>

    <!-- 2. BÖLÜM: ÖNE ÇIKAN ARAÇLAR -->
    <?php if (!empty($featured_cars)): ?>
    <section class="section">
        <div class="container">
            <h2 class="section-title">Öne Çıkan Araçlar</h2>
            <div class="car-grid">
                <?php foreach($featured_cars as $car): ?>
                    <div class="car-card">
                        <a href="arac-detay.php?id=<?php echo htmlspecialchars($car['id']); ?>">
                            <img src="<?php echo htmlspecialchars($car['main_image_url']); ?>" onerror="this.src='https://placehold.co/600x400/1E1E1E/FFFFFF?text=G%C3%B6rsel+Yok&font=inter'" alt="<?php echo htmlspecialchars($car['model_name']); ?>">
                            <div class="car-info">
                                <h3><?php echo htmlspecialchars($car['model_name']); ?></h3>
                                <p><?php echo number_format($car['price'], 0, ',', '.'); ?> TL</p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- 3. BÖLÜM: HİZMETLERİMİZ -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Neden AutoHub?</h2>
            <div class="services-grid">
                <div class="service-item">
                    <div class="icon"><i class="fa-solid fa-car-side"></i></div>
                    <h3>Geniş Araç Yelpazesi</h3>
                    <p>Her zevke ve bütçeye uygun, özenle seçilmiş lüks ve performans araçları koleksiyonumuzu keşfedin.</p>
                </div>
                <div class="service-item">
                    <div class="icon"><i class="fa-solid fa-robot"></i></div>
                    <h3>Yapay Zeka Asistanı</h3>
                    <p>Nasıl bir araç istediğinizi yazın, akıllı asistanımız size en uygun modelleri saniyeler içinde tavsiye etsin.</p>
                </div>
                <div class="service-item">
                    <div class="icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <h3>Güvenli Alışveriş</h3>
                    <p>Uçtan uca şifrelenmiş ve güvenli ödeme altyapımız ile hayalinizdeki araca güvenle sahip olun.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. BÖLÜM: MARKA TANITIMI (DEĞİŞEN FOTOĞRAFLARLA) -->
    <section class="section">
        <div class="container promo-section">
            <div class="promo-text">
                <h2 style="font-size: 2.5rem;">Sadece Bir Araba Değil, Bir Yaşam Tarzı</h2>
                <p style="color: var(--secondary-text-color); line-height: 1.7;">AutoHub olarak, size sadece bir otomobil değil, aynı zamanda bir tutku ve prestij sunuyoruz. Her model, en yüksek kalite standartlarına ve sürüş keyfine göre seçilir. Ailemize katılın ve mükemmelliği deneyimleyin.</p>
            </div>
            <!-- Statik resim yerine slider yapısı eklendi -->
            <div class="promo-image-slider">
                <img src="https://images.unsplash.com/photo-1552519507-da3b142c6e3d?q=80&w=2070&auto=format&fit=crop" alt="Yaşam Tarzı 1" class="slider-image active">
                <img src="https://images.unsplash.com/photo-1541443131876-44b73de101c5?q=80&w=2070&auto=format&fit=crop" alt="Yaşam Tarzı 2" class="slider-image">
                <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=2070&auto=format&fit=crop" alt="Yaşam Tarzı 3" class="slider-image">
            </div>
        </div>
    </section>
</main>

<!-- YENİ: Değişen fotoğraflar için JavaScript kodu -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sliderImages = document.querySelectorAll('.slider-image');
        let currentImageIndex = 0;

        if (sliderImages.length > 0) {
            function showNextImage() {
                // Mevcut resmi gizle
                sliderImages[currentImageIndex].classList.remove('active');
                
                // Bir sonraki resmin indeksini hesapla
                currentImageIndex = (currentImageIndex + 1) % sliderImages.length;
                
                // Yeni resmi göster
                sliderImages[currentImageIndex].classList.add('active');
            }

            // Her 4 saniyede bir fotoğrafı değiştir
            setInterval(showNextImage, 4000);
        }
    });
</script>

<?php include 'includes/footer.php'; ?>