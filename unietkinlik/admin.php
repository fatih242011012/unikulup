<?php
session_start();
include 'db.php';

// Güvenlik
if(!isset($_SESSION['admin_logged_in'])) { header("Location: admin_login.php"); exit; }

// Çıkış
if(isset($_GET['cikis'])) { session_destroy(); header("Location: admin_login.php"); exit; }

// Silme İşlemleri
if(isset($_GET['sil_etkinlik'])) {
    $stmt = $conn->prepare("DELETE FROM etkinlikler WHERE id = :id");
    $stmt->execute(['id' => $_GET['sil_etkinlik']]);
    header("Location: admin.php?msg=silindi");
}
if(isset($_GET['sil_mesaj'])) {
    $stmt = $conn->prepare("DELETE FROM mesajlar WHERE id = :id");
    $stmt->execute(['id' => $_GET['sil_mesaj']]);
    header("Location: admin.php?msg=mesaj_silindi");
}

// Veri Çekme
$etkinlikler = $conn->query("SELECT * FROM etkinlikler ORDER BY id DESC")->fetchAll();
$mesajlar = $conn->query("SELECT * FROM mesajlar ORDER BY id DESC")->fetchAll();
// Yeni: Katılımcıları Çek
$katilimcilar = $conn->query("SELECT * FROM katilimcilar ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4 shadow">
    <a class="navbar-brand fw-bold" href="#"><i class="fas fa-user-shield me-2"></i>ÜniKULÜP Admin</a>
    <div>
        <a href="index.php" target="_blank" class="btn btn-outline-light btn-sm me-2">Siteye Dön</a>
        <a href="admin.php?cikis=1" class="btn btn-danger btn-sm">Çıkış</a>
    </div>
</nav>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kontrol Paneli</h2>
        <a href="admin_ekle.php" class="btn btn-success"><i class="fas fa-plus"></i> Yeni Etkinlik</a>
    </div>

    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#etkinlikler">Etkinlikler</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#mesajlar">Mesajlar <span class="badge bg-danger"><?php echo count($mesajlar); ?></span></button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#katilimcilar">Katılımcılar <span class="badge bg-primary"><?php echo count($katilimcilar); ?></span></button></li>
    </ul>

    <div class="tab-content">
        
        <div class="tab-pane fade show active" id="etkinlikler">
            <div class="card border-0 shadow-sm"><div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>ID</th><th>Başlık</th><th>Üniversite</th><th>Tarih</th><th class="text-end">İşlem</th></tr></thead>
                <tbody>
                    <?php foreach($etkinlikler as $et): ?>
                    <tr>
                        <td><?php echo $et['id']; ?></td>
                        <td><?php echo $et['baslik']; ?></td>
                        <td><?php echo $et['universite']; ?></td>
                        <td><?php echo $et['tarih']; ?></td>
                        <td class="text-end">
                            <a href="admin.php?sil_etkinlik=<?php echo $et['id']; ?>" onclick="return confirm('Silinsin mi?')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table></div></div>
        </div>

        <div class="tab-pane fade" id="mesajlar">
            <div class="row g-3">
                <?php foreach($mesajlar as $msj): ?>
                <div class="col-md-6"><div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($msj['ad']); ?></h5>
                        <h6 class="text-muted"><?php echo htmlspecialchars($msj['email']); ?></h6>
                        <p class="bg-light p-2 rounded"><?php echo htmlspecialchars($msj['mesaj']); ?></p>
                        <a href="admin.php?sil_mesaj=<?php echo $msj['id']; ?>" class="btn btn-sm btn-outline-danger float-end">Sil</a>
                    </div>
                </div></div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="katilimcilar">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Öğrenci Adı</th>
                                <th>Okul</th>
                                <th>Öğrenci No</th>
                                <th>Katıldığı Etkinlik</th>
                                <th>Kayıt Zamanı</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($katilimcilar as $k): ?>
                            <tr>
                                <td><?php echo $k['id']; ?></td>
                                <td class="fw-bold"><?php echo htmlspecialchars($k['ad_soyad']); ?></td>
                                <td><?php echo htmlspecialchars($k['okul']); ?></td>
                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($k['ogr_no']); ?></span></td>
                                <td class="text-primary"><?php echo htmlspecialchars($k['etkinlik_adi']); ?></td>
                                <td class="small text-muted"><?php echo $k['kayit_tarihi']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($katilimcilar)) echo "<tr><td colspan='6' class='text-center py-4'>Henüz kayıt olan yok.</td></tr>"; ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
