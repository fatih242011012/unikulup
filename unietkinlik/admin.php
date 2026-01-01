<?php
session_start();
include 'db.php';

// 1. GÜVENLİK: Giriş yapılmamışsa login sayfasına at
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// 2. ÇIKIŞ YAPMA
if(isset($_GET['cikis'])) {
    session_destroy();
    header("Location: admin_login.php");
    exit;
}

// 3. ETKİNLİK SİLME İŞLEMİ
if(isset($_GET['sil_etkinlik'])) {
    $id = $_GET['sil_etkinlik'];
    $stmt = $conn->prepare("DELETE FROM etkinlikler WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header("Location: admin.php?msg=silindi");
    exit;
}

// 4. MESAJ SİLME İŞLEMİ (YENİ EKLENDİ)
if(isset($_GET['sil_mesaj'])) {
    $id = $_GET['sil_mesaj'];
    $stmt = $conn->prepare("DELETE FROM mesajlar WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header("Location: admin.php?msg=mesaj_silindi");
    exit;
}

// 5. VERİLERİ ÇEKME
$etkinlikler = $conn->query("SELECT * FROM etkinlikler ORDER BY id DESC")->fetchAll();
$mesajlar = $conn->query("SELECT * FROM mesajlar ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yönetim Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4 shadow">
    <a class="navbar-brand fw-bold" href="#"><i class="fas fa-user-shield me-2"></i>ÜniKULÜP Admin</a>
    <div>
        <a href="index.php" target="_blank" class="btn btn-outline-light btn-sm me-2"><i class="fas fa-external-link-alt"></i> Siteyi Gör</a>
        <a href="admin.php?cikis=1" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Çıkış</a>
    </div>
</nav>

<div class="container mt-4">
    
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'silindi'): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Etkinlik başarıyla silindi.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'mesaj_silindi'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Mesaj başarıyla silindi.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kontrol Paneli</h2>
        <a href="admin_ekle.php" class="btn btn-success shadow-sm"><i class="fas fa-plus"></i> Yeni Etkinlik Ekle</a>
    </div>

    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#etkinlikler">
                <i class="fas fa-calendar-alt me-1"></i> Etkinlikler
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#mesajlar">
                <i class="fas fa-envelope me-1"></i> Gelen Mesajlar 
                <span class="badge bg-danger ms-1"><?php echo count($mesajlar); ?></span>
            </button>
        </li>
    </ul>

    <div class="tab-content">
        
        <div class="tab-pane fade show active" id="etkinlikler">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Resim</th>
                                    <th>Başlık</th>
                                    <th>Üniversite</th>
                                    <th>Tarih</th>
                                    <th class="text-end">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($etkinlikler as $et): ?>
                                <tr>
                                    <td><?php echo $et['id']; ?></td>
                                    <td><img src="<?php echo $et['resim_url']; ?>" width="60" height="40" style="object-fit:cover; border-radius:8px;"></td>
                                    <td class="fw-bold"><?php echo $et['baslik']; ?></td>
                                    <td><?php echo $et['universite']; ?></td>
                                    <td><?php echo $et['tarih']; ?></td>
                                    <td class="text-end">
                                        <a href="detay.php?id=<?php echo $et['id']; ?>" target="_blank" class="btn btn-sm btn-info text-white me-1"><i class="fas fa-eye"></i></a>
                                        <a href="admin.php?sil_etkinlik=<?php echo $et['id']; ?>" onclick="return confirm('Bu etkinliği silmek istediğine emin misin?')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="mesajlar">
            <div class="row g-3">
                <?php foreach($mesajlar as $msj): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-start pt-3">
                            <div>
                                <h5 class="card-title mb-0 fw-bold"><?php echo htmlspecialchars($msj['ad']); ?></h5>
                                <small class="text-muted"><?php echo htmlspecialchars($msj['email']); ?></small>
                            </div>
                            <span class="badge bg-light text-dark border">
                                <?php echo date("d.m.Y", strtotime($msj['tarih'])); ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="p-3 bg-light rounded-3 text-secondary" style="min-height: 80px;">
                                <i class="fas fa-quote-left me-2 opacity-50"></i>
                                <?php echo nl2br(htmlspecialchars($msj['mesaj'])); ?>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0 d-flex justify-content-end pb-3">
                            <a href="mailto:<?php echo $msj['email']; ?>" class="btn btn-sm btn-outline-primary me-2">
                                <i class="fas fa-reply"></i> Yanıtla
                            </a>
                            <a href="admin.php?sil_mesaj=<?php echo $msj['id']; ?>" 
                               onclick="return confirm('Bu mesajı kalıcı olarak silmek istediğine emin misin?')" 
                               class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i> Sil
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php if(empty($mesajlar)): ?>
                    <div class="col-12 text-center py-5">
                        <div class="text-muted">
                            <i class="far fa-envelope-open fa-3x mb-3"></i>
                            <p>Henüz gelen kutunuz boş.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>