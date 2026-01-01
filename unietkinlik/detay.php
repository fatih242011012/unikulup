<?php
header('Content-Type: text/html; charset=utf-8');
include 'db.php';

// 1. ID KONTROLÜ VE ETKİNLİK VERİSİNİ ÇEKME
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM etkinlikler WHERE id = :id");
$stmt->execute(['id' => $id]);
$etkinlik = $stmt->fetch();

if (!$etkinlik) {
    echo "Etkinlik bulunamadı.";
    exit;
}

// 2. KATILMA İŞLEMİ (Form Gönderilince Çalışır)
$mesaj = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['katil_btn'])) {
    $etkinlikAdi = $_POST['etkinlik_adi'];
    $isim = htmlspecialchars($_POST['adsoyad']);
    $okul = htmlspecialchars($_POST['okul']);
    
    $mesaj = "Sayın <b>$isim</b>, <b>$okul</b> öğrencisi olarak <b>$etkinlikAdi</b> etkinliğine kaydınız başarıyla alındı! 🎉";
}

// 3. ÜNİVERSİTE LİSTESİNİ ÇEKME (Modal içindeki liste için gerekli)
$uniQuery = $conn->query("SELECT DISTINCT universite FROM etkinlikler ORDER BY universite ASC");
$universiteler = $uniQuery->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($etkinlik['baslik']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background-color:#f8f9fa;">

<?php if($mesaj): ?>
<div class="alert alert-success text-center fixed-top m-3 shadow" style="z-index: 9999;">
    <?php echo $mesaj; ?> <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="container py-5">
    <a href="index.php" class="btn btn-outline-secondary mb-3"><i class="fas fa-arrow-left"></i> Geri Dön</a>
    
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        <div class="row g-0">
            <div class="col-md-6">
                <img src="<?php echo $etkinlik['resim_url']; ?>" class="img-fluid w-100 h-100" style="object-fit:cover; min-height:400px;" alt="Etkinlik">
            </div>
            <div class="col-md-6 p-5 d-flex flex-column justify-content-center">
                <span class="badge bg-primary w-25 mb-2"><?php echo htmlspecialchars($etkinlik['kategori']); ?></span>
                <h1 class="display-5 fw-bold mb-3"><?php echo htmlspecialchars($etkinlik['baslik']); ?></h1>
                
                <div class="mb-4 text-muted">
                    <p class="mb-2"><i class="fas fa-university text-primary me-2"></i> <?php echo htmlspecialchars($etkinlik['universite']); ?></p>
                    <p class="mb-2"><i class="fas fa-map-marker-alt text-danger me-2"></i> <?php echo htmlspecialchars($etkinlik['konum']); ?></p>
                    <p class="mb-2"><i class="far fa-calendar-alt text-success me-2"></i> <?php echo htmlspecialchars($etkinlik['tarih']); ?> | <?php echo htmlspecialchars($etkinlik['saat']); ?></p>
                </div>

                <h5 class="fw-bold">Etkinlik Hakkında</h5>
                <p class="lead" style="font-size:1rem; line-height: 1.8;">
                    <?php echo nl2br(htmlspecialchars($etkinlik['aciklama'])); ?>
                </p>

                <button class="btn btn-dark btn-lg mt-3 rounded-pill" 
                        data-bs-toggle="modal" 
                        data-bs-target="#joinModal" 
                        data-name="<?php echo htmlspecialchars($etkinlik['baslik']); ?>">
                    Hemen Katıl <i class="fas fa-check-circle ms-2"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="joinModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Etkinliğe Katıl</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="etkinlik_adi" id="modalEtkinlikAdi">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <span id="modalEtkinlikBaslik" class="fw-bold"></span> etkinliğine kaydoluyorsunuz.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Adınız Soyadınız</label>
                        <input type="text" name="adsoyad" class="form-control" required placeholder="Örn: Ahmet Yılmaz">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Okulunuz</label>
                        <select name="okul" class="form-select" required>
                            <option value="" selected disabled>Okulunuzu Seçiniz...</option>
                            <?php foreach($universiteler as $uni): ?>
                                <option value="<?php echo htmlspecialchars($uni); ?>"><?php echo htmlspecialchars($uni); ?></option>
                            <?php endforeach; ?>
                            <option value="Diğer">Diğer / Listede Yok</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Öğrenci Numaranız</label>
                        <input type="text" name="ogrno" class="form-control" required placeholder="Örn: 2023001">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" name="katil_btn" class="btn btn-primary">Kaydı Tamamla</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Modal açıldığında başlığı otomatik dolduran script
    var joinModal = document.getElementById('joinModal')
    joinModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget
        var etkinlikAdi = button.getAttribute('data-name')
        
        var modalTitle = joinModal.querySelector('#modalEtkinlikBaslik')
        var hiddenInput = joinModal.querySelector('#modalEtkinlikAdi')
        
        modalTitle.textContent = etkinlikAdi
        hiddenInput.value = etkinlikAdi
    })
</script>

</body>
</html>