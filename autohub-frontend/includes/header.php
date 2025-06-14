<?php
// Oturum başlatılmamışsa başlat
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Bu değişken, header'ın ana sayfada farklı bir stilde olmasını kontrol eder.
$is_home_page = basename($_SERVER['PHP_SELF']) == 'index.php';

// Kullanıcı adını güvenli bir şekilde almak için bir değişken oluştur
$userName = '';
if (isset($_SESSION['user_data']) && is_array($_SESSION['user_data']) && isset($_SESSION['user_data']['name'])) {
    $userName = htmlspecialchars($_SESSION['user_data']['name']);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - AutoHub' : 'AutoHub'; ?></title>
    
    <!-- YENİ: Favicon Linkleri -->
    <!-- Modern tarayıcılar için PNG formatında favicon -->
    <link rel="icon" type="image/png" href="https://em-content.zobj.net/source/microsoft-teams/363/automobile_1f697.png">
    <!-- Apple cihazları için dokunmatik ikon -->
    <link rel="apple-touch-icon" href="https://em-content.zobj.net/source/microsoft-teams/363/automobile_1f697.png">
    
    <!-- Font Awesome ikon kütüphanesi -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" xintegrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Yeni menü için ek stiller -->
    <style>
        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem; /* İkonlar ve linkler arası boşluk */
        }
        .nav-links a {
            font-size: 1rem;
            font-weight: 500;
        }
        .nav-icon {
            font-size: 1.3rem; /* İkonların boyutu */
            color: var(--secondary-text-color);
            transition: color 0.3s;
        }
        .nav-icon:hover {
            color: var(--primary-text-color);
        }
        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            text-decoration: none;
        }
        .user-menu .user-icon {
            font-size: 2rem; /* Kullanıcı ikonunun boyutu */
        }
        .user-menu-text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            line-height: 1.3;
        }
        .user-menu-text span {
            font-size: 0.8rem;
            color: var(--secondary-text-color);
        }
        .user-menu-text strong {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--primary-text-color);
        }

        .nav-register-btn {
            background-color: var(--accent-color);
            color: #000;
            padding: 0.6rem 1.2rem;
            border-radius: 5px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }
        .nav-register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 168, 240, 0.3);
        }
    </style>
</head>
<body>
    <header <?php if(!$is_home_page) echo 'style="position: static; background-color: #000;"'; ?>>
        <nav class="container nav-container">
            <a href="index.php" class="logo">AutoHub</a>
            <ul class="nav-links">
                <!-- Arama ikonu gibi genel linkler -->
                <li><a href="araclar.php" class="nav-icon" title="Araçları Ara"><i class="fa-solid fa-magnifying-glass"></i></a></li>
                
                <?php if (isset($_SESSION['user_token'])): ?>
                    <!-- KULLANICI GİRİŞ YAPTIĞINDA GÖRÜNECEK LİNKLER -->
                    <li><a href="favorilerim.php" class="nav-icon" title="Favorilerim"><i class="fa-solid fa-star"></i></a></li>
                    <li><a href="sepet.php" class="nav-icon" title="Sepetim"><i class="fa-solid fa-cart-shopping"></i></a></li>
                    <li>
                        <a href="hesabim.php" class="user-menu" title="Hesabım">
                            <i class="fa-solid fa-user-circle nav-icon user-icon"></i>
                            <div class="user-menu-text">
                                <span><?php echo $userName; ?></span>
                                <strong>Hesabım</strong>
                            </div>
                        </a>
                    </li>
                <?php else: ?>
                    <!-- KULLANICI GİRİŞ YAPMADIĞINDA GÖRÜNECEK LİNKLER -->
                    <li>
                        <a href="login.php" class="user-menu" title="Giriş Yap">
                            <i class="fa-solid fa-user-circle nav-icon user-icon"></i>
                            <div class="user-menu-text">
                                <span>Hesabım</span>
                                <strong>Giriş Yap</strong>
                            </div>
                        </a>
                    </li>
                    <li><a href="register.php" class="nav-register-btn">Kayıt Ol</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>