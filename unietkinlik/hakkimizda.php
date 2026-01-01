<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hakkımızda - ÜniKULÜP</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f9f9fb; color: #333; }
        .navbar-brand { font-weight: 700; color: #6C63FF !important; font-size: 1.5rem; }
        .hero-mini { background: linear-gradient(120deg, #6C63FF 0%, #8B80F9 100%); padding: 60px 0; color: white; text-align: center; border-bottom-left-radius: 30px; border-bottom-right-radius: 30px; }
        .feature-box { background: white; padding: 30px; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); height: 100%; transition: 0.3s; }
        .feature-box:hover { transform: translateY(-5px); }
        .icon-box { font-size: 2rem; color: #6C63FF; margin-bottom: 20px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">ÜniKULÜP</a>
            <div class="d-flex gap-3">
                <a href="index.php" class="nav-link fw-bold">Ana Sayfa</a>
                <a href="iletisim.php" class="nav-link">İletişim</a>
            </div>
        </div>
    </nav>

    <div class="hero-mini">
        <h1>Biz Kimiz?</h1>
        <p class="opacity-75">Üniversite hayatını sadece derslerden ibaret görmeyenlerin buluşma noktası.</p>
    </div>

    <div class="container py-5">
        <div class="row align-items-center mb-5">
            <div class="col-md-6">
                <h2 class="fw-bold mb-4">Misyonumuz</h2>
                <p class="text-muted lead">
                    ÜniKULÜP olarak amacımız, Türkiye'nin dört bir yanındaki üniversite öğrencilerini ortak ilgi alanlarında buluşturmak, kampüsler arası etkileşimi artırmak ve sosyalleşmeyi dijital çağın hızına uydurmaktır.
                </p>
                <p class="text-muted">
                    2025 yılında kurulan platformumuz, "Kampüs Senin Sahnen" mottosuyla yola çıktı. Bugün binlerce öğrencinin etkinliklere katılmasına, yeni hobiler edinmesine ve kariyer ağlarını genişletmesine aracılık ediyoruz.
                </p>
            </div>
            <div class="col-md-6">
                <img src="https://images.pexels.com/photos/1438072/pexels-photo-1438072.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="img-fluid rounded-4 shadow" alt="Biz Kimiz">
            </div>
        </div>

        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="feature-box">
                    <div class="icon-box"><i class="fas fa-users"></i></div>
                    <h5>Geniş Topluluk</h5>
                    <p class="text-muted small">Farklı üniversitelerden binlerce öğrenci ile tanışma fırsatı.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <div class="icon-box"><i class="fas fa-calendar-check"></i></div>
                    <h5>Güncel Etkinlikler</h5>
                    <p class="text-muted small">Her gün yenilenen etkinlik takvimi ile asla sıkılma.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <div class="icon-box"><i class="fas fa-shield-alt"></i></div>
                    <h5>Güvenilir Platform</h5>
                    <p class="text-muted small">Doğrulanmış kulüpler ve güvenli etkinlik ortamı.</p>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="text-center py-4 text-muted border-top mt-5">
        &copy; 2026 ÜniKULÜP. Tüm Hakları Saklıdır.
    </footer>
</body>
</html>