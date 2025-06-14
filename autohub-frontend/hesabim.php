<?php
require_once 'includes/api_client.php';

// Kullanıcı giriş yapmamışsa, login sayfasına yönlendir
if (!isset($_SESSION['user_token'])) {
    header('Location: login.php');
    exit;
}

// Oturumdan kullanıcı bilgilerini al
$user_name = $_SESSION['user_data']['name'] ?? 'Kullanıcı';
$user_email = $_SESSION['user_data']['email'] ?? 'E-posta bilgisi yok';

$pageTitle = "Hesabım";
include 'includes/header.php';
?>
<style>
    /* Profil sayfası için özel stiller */
    .account-layout {
        display: flex;
        gap: 2rem;
        align-items: flex-start;
        margin-top: 2rem; /* Üstten boşluk */
    }
    .account-sidebar {
        flex: 0 0 250px; /* Kenar çubuğu sabit genişlik */
    }
    .account-content {
        flex-grow: 1; /* Ana içerik alanının kalan boşluğu doldurması */
        background-color: var(--surface-color);
        padding: 2rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }
    .account-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .account-menu li a {
        display: block;
        padding: 1rem;
        color: var(--secondary-text-color);
        text-decoration: none;
        border-radius: 8px;
        transition: background-color 0.2s, color 0.2s;
        font-weight: 500;
        display: flex;
        align-items: center;
    }
    .account-menu li a:hover {
        background-color: var(--surface-color);
        color: var(--primary-text-color);
    }
    .account-menu li a.active {
        background-color: var(--accent-color);
        color: #000;
    }
    .account-menu .icon {
        width: 20px; /* İkonlar için sabit genişlik */
        margin-right: 0.75rem;
        text-align: center;
    }
    .logout-btn {
        color: #ff4d4d !important; /* Çıkış yap butonunu kırmızı yapar */
    }
    .content-box h2 {
        margin-top: 0;
        font-size: 1.5rem;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }
    .content-box p {
        color: var(--secondary-text-color);
        font-size: 1.1rem;
        line-height: 1.8;
    }
    .content-box strong {
        color: var(--primary-text-color);
    }
</style>

<main class="container">
    <div class="page-header">
        <h1>Hesabım</h1>
    </div>

    <div class="account-layout">
        <aside class="account-sidebar">
            <ul class="account-menu">
                <li><a href="hesabim.php" class="active"><i class="icon fa-solid fa-user"></i> Profil Bilgileri</a></li>
                <li><a href="siparislerim.php"><i class="icon fa-solid fa-file-invoice-dollar"></i> Siparişlerim</a></li>
                <li><a href="favorilerim.php"><i class="icon fa-solid fa-star"></i> Favorilerim</a></li>
                <li><a href="sepet.php"><i class="icon fa-solid fa-cart-shopping"></i> Sepetim</a></li>
                <li><a href="logout.php" class="logout-btn"><i class="icon fa-solid fa-right-from-bracket"></i> Güvenli Çıkış</a></li>
            </ul>
        </aside>
        
        <section class="account-content">
            <div class="content-box">
                <h2>Kullanıcı Bilgileri</h2>
                <p><strong>Ad Soyad:</strong> <?php echo htmlspecialchars($user_name); ?></p>
                <p><strong>E-posta Adresi:</strong> <?php echo htmlspecialchars($user_email); ?></p>
                </div>
        </section>
    </div>
</main>

<?php include 'includes/footer.php'; ?>