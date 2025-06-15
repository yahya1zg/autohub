AutoHub - Modern Araç Satış Platformu
AutoHub, kullanıcıların araçları listeleyebileceği, detaylarını inceleyebileceği ve satın alabileceği, modern teknolojilerle geliştirilmiş bir web projesidir. Proje, frontend ve backend katmanlarının birbirinden ayrıldığı bir mimariye sahiptir ve yapay zeka destekli araç tavsiye sistemi gibi yenilikçi özellikler içerir.

 Özellikler
Kullanıcı Sistemi: Güvenli kayıt olma ve giriş yapma.

Araç Kataloğu: Satıştaki araçları listeleme ve detaylarını inceleme.

Favorilere Ekleme: Kullanıcıların beğendikleri araçları kendi profillerine kaydetmesi.

Alışveriş Sepeti: Araçları sepete ekleme, sepeti görüntüleme ve sepetten kaldırma.

Satın Alma: Sepetteki araçlar için satın alma işlemini gerçekleştirme.

Yapay Zeka Asistanı: Google Gemini API'sini kullanarak, kullanıcıların doğal dilde yazdıkları isteklere göre kişiselleştirilmiş araç tavsiyeleri sunma.

Hesabım Sayfası: Kullanıcıların profil bilgilerini, sipariş geçmişini, favorilerini ve sepetini tek bir yerden yönetmesi.

Modern Arayüz: Koyu tema üzerine kurulu, şık ve kullanıcı dostu tasarım.

 Teknoloji Stack'i
Bu proje, iki ana bölümden oluşmaktadır:

Backend (API): autohub-api

Framework: Laravel 11

Dil: PHP 8.2+

Veritabanı: MySQL

API Kimlik Doğrulama: Laravel Sanctum (Token-based)

Yapay Zeka: Google Gemini API

Frontend (Arayüz): autohub-frontend

Dil: PHP 8.2+

İstemci Taraflı: HTML5, CSS3, JavaScript (Fetch API)

API İletişimi: cURL
 
 Kurulum ve Başlatma
Projeyi yerel makinenizde çalıştırmak için aşağıdaki adımları izleyin.

Gereksinimler
XAMPP (Apache, MySQL, PHP içeren sunucu paketi)

Composer (PHP paket yöneticisi)

1. Backend Kurulumu (autohub-api)
Sunucuyu Başlatın: XAMPP Kontrol Paneli'nden Apache ve MySQL servislerini başlatın.

Veritabanını Oluşturun:

http://localhost/phpmyadmin adresine gidin.

autohub_db adında yeni bir veritabanı oluşturun (Karşılaştırma: utf8mb4_general_ci).

Proje Klasörüne Gidin: Bir terminal açın ve autohub-api klasörünün içine girin.

cd /yol/proje/autohub-api

.env Dosyasını Ayarlayın: .env.example dosyasını kopyalayarak .env adında yeni bir dosya oluşturun ve veritabanı bilgilerinizi girin:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=autohub_db
DB_USERNAME=root
DB_PASSWORD=

Bağımlılıkları Yükleyin:

composer install

Uygulama Anahtarını Oluşturun:

php artisan key:generate

Veritabanını Kurun ve Doldurun: Bu komut tüm tabloları oluşturacak (sessions dahil) ve örnek verileri ekleyecektir.

php artisan session:table
php artisan migrate:fresh --seed

API Sunucusunu Başlatın:

php artisan serve

API artık http://127.0.0.1:8000 adresinde çalışıyor. Bu terminali kapatmayın.

2. Frontend Kurulumu (autohub-frontend)
Dosyaları Taşıyın: autohub-frontend klasörünü, XAMPP'ın htdocs klasörünün içine yerleştirin.

API Adresini Kontrol Edin: includes/api_client.php dosyasını açın ve API_BASE_URL'in http://127.0.0.1:8000/api olarak ayarlandığından emin olun.

Siteyi Ziyaret Edin: Web tarayıcınızı açın ve aşağıdaki adrese gidin:

http://localhost/autohub-frontend/

Artık AutoHub projesi bilgisayarınızda çalışıyor!
