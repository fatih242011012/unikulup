<?php
header('Content-Type: text/html; charset=utf-8');
include 'db.php';

// --- KATILMA İŞLEMİ ---
$mesaj = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['katil_btn'])) {
    $etkinlikAdi = $_POST['etkinlik_adi'];
    $isim = htmlspecialchars($_POST['adsoyad']);
    $okul = htmlspecialchars($_POST['okul']);
    $mesaj = "Harika! <b>$isim</b>, <b>$etkinlikAdi</b> için biletin oluşturuldu. ($okul) 🎉";
}

// --- VERİ ÇEKME ---
$uniQuery = $conn->query("SELECT DISTINCT universite FROM etkinlikler ORDER BY (universite LIKE '%Alanya Alaaddin Keykubat%') DESC, universite ASC");
$universiteler = $uniQuery->fetchAll(PDO::FETCH_COLUMN);

$secilenUni = isset($_GET['uni']) ? $_GET['uni'] : '';
$aramaKelime = isset($_GET['q']) ? $_GET['q'] : '';

$sql = "SELECT * FROM etkinlikler WHERE 1=1";
$params = [];

if (!empty($secilenUni)) {
    $sql .= " AND universite = :uni";
    $params['uni'] = $secilenUni;
}
if (!empty($aramaKelime)) {
    $sql .= " AND (baslik LIKE :kelime OR kategori LIKE :kelime)";
    $params['kelime'] = "%$aramaKelime%";
}
$sql .= " ORDER BY (universite LIKE '%Alanya Alaaddin Keykubat%') DESC, tarih ASC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$etkinlikler = $stmt->fetchAll();

// --- ZAMAN FONKSİYONLARI ---
function tarihFormatla($tarih) {
    $aylar = ['01'=>'OCAK','02'=>'ŞUB','03'=>'MART','04'=>'NİS','05'=>'MAY','06'=>'HAZ','07'=>'TEM','08'=>'AĞU','09'=>'EYL','10'=>'EKİM','11'=>'KAS','12'=>'ARA'];
    $zaman = new DateTime($tarih);
    return ['gun' => $zaman->format('d'), 'ay' => $aylar[$zaman->format('m')]];
}

function kalanZamanHesapla($tarih) {
    $bugun = new DateTime();
    $etkinlikTarihi = new DateTime($tarih);
    $etkinlikTarihi->setTime(23, 59, 59);

    if ($bugun > $etkinlikTarihi) return ['durum' => 'gecti', 'metin' => 'Süre Doldu', 'renk' => 'text-muted'];
    $fark = $bugun->diff($etkinlikTarihi);
    $gun = $fark->days;

    if ($gun == 0) return ['durum' => 'bugun', 'metin' => 'BUGÜN SON GÜN!', 'renk' => 'text-danger fw-bold'];
    elseif ($gun <= 3) return ['durum' => 'az', 'metin' => "Acele Et! Son $gun Gün", 'renk' => 'text-danger fw-bold'];
    else return ['durum' => 'var', 'metin' => "$gun gün kaldı", 'renk' => 'text-primary'];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ÜniKULÜP | Keşfet</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;500;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #6366f1;
            --secondary: #8b5cf6;
            --glass: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.5);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f3f4f6;
            color: #1f2937;
            overflow-x: hidden;
            min-height: 100vh;
            position: relative;
        }

        /* --- ARKA PLAN ANİMASYONU (BLOBS) --- */
        .blob-bg {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            overflow: hidden;
            background: #f0f2f5;
        }
        .blob {
            position: absolute;
            filter: blur(80px);
            opacity: 0.6;
            animation: moveBlob 20s infinite alternate;
        }
        .blob-1 { top: -10%; left: -10%; width: 500px; height: 500px; background: #c4b5fd; animation-duration: 25s; }
        .blob-2 { bottom: -10%; right: -10%; width: 400px; height: 400px; background: #a5f3fc; animation-duration: 30s; }
        .blob-3 { top: 40%; left: 40%; width: 300px; height: 300px; background: #fbcfe8; animation-duration: 35s; }

        @keyframes moveBlob {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, -50px) scale(1.1); }
        }

        /* --- YÜZEN NAVBAR --- */
        .nav-floating {
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-radius: 50px;
            padding: 10px 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid var(--glass-border);
        }
        .navbar-brand { font-weight: 800; font-size: 1.4rem; background: linear-gradient(45deg, #4f46e5, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .nav-link { font-weight: 600; color: #4b5563 !important; transition: 0.3s; }
        .nav-link:hover { color: #4f46e5 !important; transform: translateY(-2px); }

        /* --- HERO --- */
        .hero-container {
            text-align: center;
            padding: 80px 0 60px 0;
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 15px;
            letter-spacing: -1px;
            color: #111827;
        }
        .hero-desc { font-size: 1.2rem; color: #6b7280; margin-bottom: 40px; font-weight: 500; }

        /* --- ARAMA KUTUSU (Glassmorphism) --- */
        .search-glass {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(20px);
            border: 2px solid white;
            border-radius: 20px;
            padding: 10px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            display: flex;
            max-width: 800px;
            margin: 0 auto;
            transition: 0.3s;
        }
        .search-glass:hover { transform: translateY(-5px); box-shadow: 0 25px 50px rgba(0,0,0,0.08); }
        .form-select-uni, .form-input-search { border: none; background: transparent; padding: 15px; font-size: 1.05rem; outline: none; }
        .form-select-uni:focus, .form-input-search:focus { box-shadow: none; background: transparent; }
        .btn-search-round {
            width: 50px; height: 50px; border-radius: 15px;
            background: #111827; color: white; border: none;
            display: flex; align-items: center; justify-content: center;
            transition: 0.3s;
        }
        .btn-search-round:hover { background: #4f46e5; transform: rotate(10deg); }

        /* --- KARTLAR --- */
        .card-modern {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 24px;
            border: 1px solid white;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.03);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .card-modern:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.15);
        }
        
        .img-wrap { position: relative; height: 230px; overflow: hidden; border-radius: 24px 24px 0 0; }
        .img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .card-modern:hover .img-wrap img { transform: scale(1.1); }

        /* Rozetler */
        .date-float {
            position: absolute; top: 15px; right: 15px;
            background: rgba(255,255,255,0.95);
            padding: 8px 14px; border-radius: 12px;
            text-align: center; font-weight: 800;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .alku-float {
            position: absolute; top: 15px; left: 15px;
            background: #FFD700; color: #000;
            font-size: 0.75rem; font-weight: 800;
            padding: 6px 14px; border-radius: 30px;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.4);
        }

        .card-content { padding: 25px; flex-grow: 1; display: flex; flex-direction: column; }
        .cat-pill {
            font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
            color: #6366f1; background: #e0e7ff;
            padding: 5px 12px; border-radius: 20px;
            display: inline-block; margin-bottom: 10px; width: fit-content;
        }
        .card-h { font-weight: 800; font-size: 1.2rem; margin-bottom: 8px; color: #111827; }
        
        .timer-box { 
            background: #f9fafb; border-radius: 12px; padding: 10px; 
            font-size: 0.85rem; margin-top: auto; margin-bottom: 15px;
            display: flex; align-items: center; gap: 8px;
        }

        /* --- BUTONLAR --- */
        .btn-group-custom { display: flex; gap: 10px; }
        .btn-join-main {
            flex: 2;
            background: #111827; color: white;
            border: none; padding: 12px; border-radius: 12px;
            font-weight: 600; transition: 0.3s;
        }
        .btn-join-main:hover { background: #4f46e5; box-shadow: 0 5px 15px rgba(79, 70, 229, 0.3); color: white;}
        .btn-join-main:disabled { background: #d1d5db; color: #9ca3af; cursor: not-allowed; box-shadow: none; }
        
        .btn-detail-main {
            flex: 1;
            background: white; border: 2px solid #f3f4f6;
            color: #374151; font-weight: 600;
            border-radius: 12px; text-decoration: none;
            display: flex; align-items: center; justify-content: center;
            transition: 0.3s;
        }
        .btn-detail-main:hover { border-color: #111827; color: #111827; }

    </style>
</head>
<body>

    <div class="blob-bg">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <div class="container sticky-top" style="z-index: 999;">
        <nav class="navbar navbar-expand-lg nav-floating">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">ÜniKULÜP</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav align-items-center">
                        <li class="nav-item"><a class="nav-link" href="index.php">Keşfet</a></li>
                        <li class="nav-item"><a class="nav-link" href="hakkimizda.php">Hakkımızda</a></li>
                        <li class="nav-item"><a class="nav-link" href="iletisim.php">İletişim</a></li>
                        <li class="nav-item ms-3">
                            <a href="admin_login.php" class="btn btn-sm btn-dark rounded-pill px-4">Admin</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <?php if($mesaj): ?>
    <div class="container mt-3" style="position:relative; z-index:1000;">
        <div class="alert alert-success text-center shadow-sm border-0 rounded-4">
            <?php echo $mesaj; ?> <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?>

    <div class="container hero-container">
        <h1 class="hero-title">Kampüsün <span style="background: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%); padding: 0 10px; border-radius: 10px; -webkit-background-clip: initial; -webkit-text-fill-color: initial; color: #0f172a;">Enerjisini</span> Keşfet.</h1>
        <p class="hero-desc">Sadece derslere girme, hayatı yakala. Senin için seçtiğimiz etkinlikler burada.</p>
        
        <form action="" method="GET" class="search-glass">
            <select name="uni" class="form-select form-select-uni">
                <option value="">Tüm Üniversiteler</option>
                <?php foreach($universiteler as $uni): ?>
                    <?php $displayName = ($uni == 'Alanya Alaaddin Keykubat Üniversitesi') ? '⭐ ' . $uni : $uni; ?>
                    <option value="<?php echo htmlspecialchars($uni); ?>" <?php echo ($secilenUni == $uni) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($displayName); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div style="width: 1px; background: #e5e7eb; margin: 10px 0;"></div>
            <input type="text" name="q" class="form-input-search w-100" placeholder="Etkinlik ara..." value="<?php echo htmlspecialchars($aramaKelime); ?>">
            <button type="submit" class="btn-search-round"><i class="fas fa-arrow-right"></i></button>
        </form>
    </div>

    <div class="container pb-5">
        <div class="row g-4">
            <?php foreach($etkinlikler as $etkinlik): ?>
                <?php 
                    $tarihBilgi = tarihFormatla($etkinlik['tarih']); 
                    $kalanZaman = kalanZamanHesapla($etkinlik['tarih']);
                ?>
                
                <div class="col-lg-4 col-md-6">
                    <div class="card-modern">
                        <div class="img-wrap">
                            <div class="date-float">
                                <div style="font-size:1.3rem; line-height:1;"><?php echo $tarihBilgi['gun']; ?></div>
                                <div style="font-size:0.7rem; color:#6366f1;"><?php echo $tarihBilgi['ay']; ?></div>
                            </div>
                            <?php if($etkinlik['universite'] == 'Alanya Alaaddin Keykubat Üniversitesi'): ?>
                                <div class="alku-float"><i class="fas fa-crown"></i> ALKÜ</div>
                            <?php endif; ?>
                            <img src="<?php echo $etkinlik['resim_url']; ?>" alt="Etkinlik">
                        </div>

                        <div class="card-content">
                            <span class="cat-pill"><?php echo htmlspecialchars($etkinlik['kategori']); ?></span>
                            <h3 class="card-h text-truncate"><?php echo htmlspecialchars($etkinlik['baslik']); ?></h3>
                            
                            <div class="text-muted small mb-3 text-truncate">
                                <i class="fas fa-map-marker-alt me-1 text-danger"></i> 
                                <?php echo str_replace('Üniversitesi', '', htmlspecialchars($etkinlik['universite'])); ?>
                            </div>

                            <div class="timer-box <?php echo $kalanZaman['renk']; ?>">
                                <i class="far fa-clock"></i> <?php echo $kalanZaman['metin']; ?>
                            </div>

                            <div class="btn-group-custom">
                                <button class="btn-join-main" 
                                        <?php echo ($kalanZaman['durum'] == 'gecti') ? 'disabled' : ''; ?>
                                        data-bs-toggle="modal" 
                                        data-bs-target="#joinModal" 
                                        data-name="<?php echo htmlspecialchars($etkinlik['baslik']); ?>">
                                    <?php echo ($kalanZaman['durum'] == 'gecti') ? 'Doldu' : 'Katıl'; ?>
                                </button>
                                
                                <a href="detay.php?id=<?php echo $etkinlik['id']; ?>" class="btn-detail-main">
                                    İncele
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <footer class="text-center py-5 mt-4 text-muted border-top">
            <small>&copy; 2026 ÜniKULÜP. Öğrenciler için tasarlandı.</small>
        </footer>
    </div>

    <div class="modal fade" id="joinModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">Yerini Ayırt! 🚀</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body p-4">
                        <input type="hidden" name="etkinlik_adi" id="modalEtkinlikAdi">
                        <p class="text-muted small mb-4">Etkinlik: <span id="modalEtkinlikBaslik" class="fw-bold text-dark"></span></p>
                        
                        <div class="form-floating mb-3">
                            <input type="text" name="adsoyad" class="form-control rounded-4 bg-light border-0" id="floatingInput" placeholder="Ad Soyad" required>
                            <label for="floatingInput">Adınız Soyadınız</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <select name="okul" class="form-select rounded-4 bg-light border-0" id="floatingSelect" required>
                                <option value="" selected disabled>Seçiniz...</option>
                                <?php foreach($universiteler as $uni): ?>
                                    <?php $displayName = ($uni == 'Alanya Alaaddin Keykubat Üniversitesi') ? '⭐ ' . $uni : $uni; ?>
                                    <option value="<?php echo htmlspecialchars($uni); ?>"><?php echo htmlspecialchars($displayName); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="floatingSelect">Okulunuz</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" name="ogrno" class="form-control rounded-4 bg-light border-0" id="floatingNo" placeholder="No" required>
                            <label for="floatingNo">Öğrenci Numaranız</label>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="submit" name="katil_btn" class="btn btn-dark rounded-pill px-5">Kaydı Tamamla</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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