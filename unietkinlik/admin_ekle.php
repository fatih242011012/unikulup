<?php
session_start();
include 'db.php';

// Güvenlik: Giriş yapılmış mı?
if(!isset($_SESSION['admin_logged_in'])) { header("Location: admin_login.php"); exit; }

// Mevcut Üniversiteleri Çek (Listede göstermek için)
$uniQuery = $conn->query("SELECT DISTINCT universite FROM etkinlikler ORDER BY universite ASC");
$mevcutUniversiteler = $uniQuery->fetchAll(PDO::FETCH_COLUMN);

// KAYDETME İŞLEMİ
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "INSERT INTO etkinlikler (baslik, kategori, universite, resim_url, aciklama, konum, tarih, saat) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        $_POST['baslik'], $_POST['kategori'], $_POST['universite'], 
        $_POST['resim_url'], $_POST['aciklama'], $_POST['konum'], 
        $_POST['tarih'], $_POST['saat']
    ]);
    header("Location: admin.php?msg=eklendi");
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Etkinlik Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4 p-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2"></i>Yeni Etkinlik Oluştur</h5>
                    <a href="admin.php" class="btn btn-sm btn-light text-primary fw-bold"><i class="fas fa-arrow-left"></i> Geri Dön</a>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        
                        <div class="form-floating mb-3">
                            <input type="text" name="baslik" class="form-control" id="fBaslik" placeholder="Başlık" required>
                            <label for="fBaslik">Etkinlik Başlığı</label>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Kategori</label>
                                <select name="kategori" class="form-select" required>
                                    <option value="" selected disabled>Seçiniz...</option>
                                    <option>Teknoloji</option>
                                    <option>Spor</option>
                                    <option>Müzik</option>
                                    <option>Yemek</option>
                                    <option>Kültür</option>
                                    <option>Film ve Tiyatro</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Üniversite</label>
                                
                                <input type="text" name="universite" class="form-control" list="uniListesi" placeholder="Listeden seçin veya yeni yazın..." required autocomplete="off">
                                
                                <datalist id="uniListesi">
                                    <?php foreach($mevcutUniversiteler as $uni): ?>
                                        <option value="<?php echo htmlspecialchars($uni); ?>">
                                    <?php endforeach; ?>
                                </datalist>
                                
                                <div class="form-text text-success small">
                                    <i class="fas fa-magic"></i> Listede olmayan bir okul yazarsanız <b>otomatik olarak yeni eklenir.</b>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Resim URL</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-image"></i></span>
                                <input type="text" name="resim_url" class="form-control" placeholder="https://..." required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-muted small fw-bold">Tarih</label>
                                <input type="date" name="tarih" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-muted small fw-bold">Saat</label>
                                <input type="time" name="saat" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-muted small fw-bold">Konum</label>
                                <input type="text" name="konum" class="form-control" placeholder="Örn: Kampüs Bahçesi" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Etkinlik Açıklaması</label>
                            <textarea name="aciklama" rows="5" class="form-control" placeholder="Etkinlik detaylarını buraya yazın..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm">
                            <i class="fas fa-check"></i> Kaydet ve Yayınla
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>