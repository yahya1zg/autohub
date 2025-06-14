<?php
require_once 'includes/api_client.php';

$response = api_request('/cars');
$cars = $response['body'] ?? [];

$pageTitle = "Araçlar";
include 'includes/header.php';
?>
<main class="container">
    <div class="page-header">
        <h1>Modellerimiz</h1>
    </div>
    
    <div class="ai-assistant-box">
        <h2>Yapay Zeka Araç Asistanı</h2>
        <p>Nasıl bir araba aradığınızı anlatın, size en uygun olanları bulalım.</p>
        <form id="ai-form">
            <input type="text" id="ai-query" placeholder="Örn: Geniş ailem için teknolojik ve güvenli bir SUV..." required>
            <button type="submit">Tavsiye İste</button>
        </form>
    </div>

    <div class="car-grid" id="car-grid">
        <!-- Araçlar JavaScript ile yüklenecek -->
    </div>
    <div id="loading-indicator" style="display: none; text-align: center; padding: 2rem;">
        <p>Sizin için en iyi araçlar aranıyor...</p>
    </div>
</main>

<script>
    const carGrid = document.getElementById('car-grid');
    const aiForm = document.getElementById('ai-form');
    const loadingIndicator = document.getElementById('loading-indicator');
    const initialCars = <?php echo json_encode($cars); ?>;

    function createCarCard(car) {
        const price = new Intl.NumberFormat('tr-TR').format(car.price);
        let cardHTML = `<div class="car-card"><a href="arac-detay.php?id=${car.id}"><img src="${car.main_image_url}" alt="${car.model_name}"><div class="car-info"><h3>${car.model_name}</h3><p>${price} TL</p>`;
        if (car.ai_reason) {
            cardHTML += `<p class="ai-reason">✨ Asistan Tavsiyesi: ${car.ai_reason}</p>`;
        }
        cardHTML += `</div></a></div>`;
        return cardHTML;
    }

    function renderCars(carArray) {
        loadingIndicator.style.display = 'none';
        carGrid.innerHTML = '';
        if (carArray && carArray.length > 0) {
            carArray.forEach(car => { carGrid.innerHTML += createCarCard(car); });
        } else {
            carGrid.innerHTML = '<p>Bu kritere uygun araç bulunamadı.</p>';
        }
    }

    aiForm.addEventListener('submit', async function(event) {
        event.preventDefault();
        const query = document.getElementById('ai-query').value;
        if (!query) return;

        carGrid.innerHTML = '';
        loadingIndicator.style.display = 'block';

        try {
            const response = await fetch('http://127.0.0.1:8000/api/ai/recommend-car', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ query: query })
            });
            if (!response.ok) {
                const errorResult = await response.json();
                throw new Error(errorResult.error || 'Bir hata oluştu.');
            }
            const recommendedCars = await response.json();
            renderCars(recommendedCars);
        } catch (error) {
            loadingIndicator.style.display = 'none';
            carGrid.innerHTML = `<p style="color: #ff4d4d; text-align: center;">Hata: ${error.message}</p>`;
        }
    });

    renderCars(initialCars);
</script>

<?php include 'includes/footer.php'; ?>