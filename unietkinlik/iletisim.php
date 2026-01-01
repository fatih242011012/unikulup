<?php 
header('Content-Type: text/html; charset=utf-8'); 
include 'db.php'; // Veritabanı bağlantısını dahil ettik

$mesajGonderildi = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = htmlspecialchars($_POST['ad']);
    $email = htmlspecialchars($_POST['email']);
    $mesaj = htmlspecialchars($_POST['mesaj']);

    // Veritabanına Kayıt İşlemi
    $sql = "INSERT INTO mesajlar (ad, email, mesaj) VALUES (:ad, :email, :mesaj)";
    $stmt = $conn->prepare($sql);
    $sonuc = $stmt->execute(['ad'=>$ad, 'email'=>$email, 'mesaj'=>$mesaj]);

    if($sonuc) {
        $mesajGonderildi = true;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim - ÜniKULÜP</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f9f9fb; color: #333; }
        .navbar-brand { font-weight: 700; color: #6C63FF !important; font-size: 1.5rem; }
        .hero-mini { background: linear-gradient(120deg, #6C63FF 0%, #8B80F9 100%); padding: 60px 0; color: white; text-align: center; border-bottom-left-radius: 30px; border-bottom-right-radius: 30px; }
        .contact-card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .form-control { padding: 12px; border-radius: 10px; border: 1px solid #eee; background-color: #fcfcfc; }
        .form-control:focus { box-shadow: none; border-color: #6C63FF; }
        .btn-send { background-color: #6C63FF; color: white; padding: 12px 30px; border-radius: 50px; border: none; font-weight: 600; transition: 0.3s; }
        .btn-send:hover { transform: scale(1.05); background-color: #5a52d5; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">ÜniKULÜP</a>
            <div class="d-flex gap-3">
                <a href="index.php" class="nav-link">Ana Sayfa</a>
                <a href="hakkimizda.php" class="nav-link">Hakkımızda</a>
            </div>
        </div>
    </nav>

    <div class="hero-mini">
        <h1>İletişim</h1>
        <p class="opacity-75">Sorularınız, önerileriniz veya iş birlikleri için bize ulaşın.</p>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <?php if($mesajGonderildi): ?>
                <div class="alert alert-success text-center shadow-sm rounded-4 mb-4">
                    <i class="fas fa-check-circle me-2"></i> Mesajınız başarıyla bize ulaştı! En kısa sürede döneceğiz.
                </div>
                <?php endif; ?>

                <div class="contact-card">
                    <div class="row">
                        <div class="col-md-5 mb-4 border-end">
                            <h5 class="fw-bold mb-4">İletişim Bilgileri</h5>
                            <div class="mb-3">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                <span class="text-muted">Alaaddin Keykubat Üniversitesi<br>Alanya/Antalya</span>
                            </div>
                            <div class="mb-3">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <span class="text-muted">info@unikulup.com</span>
                            </div>
                            
                            <hr class="my-4">
                            <div class="d-flex gap-3">
                                <a href="#" class="text-secondary fs-4"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="text-secondary fs-4"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <h5 class="fw-bold mb-4">Bize Yazın</h5>
                            <form action="" method="POST">
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Adınız Soyadınız</label>
                                    <input type="text" name="ad" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted">E-posta Adresiniz</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Mesajınız</label>
                                    <textarea name="mesaj" rows="4" class="form-control" required></textarea>
                                </div>
                                <button type="submit" class="btn-send w-100">Gönder <i class="fas fa-paper-plane ms-2"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="text-center py-4 text-muted border-top mt-5">
        &copy; 2026 ÜniKULÜP. Tüm Hakları Saklıdır.
    </footer>
</body>
</html>