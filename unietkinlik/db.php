 <?php


// 1. INFINITYFREE BAGLANTI BILGILERI
$host = "sql100.infinityfree.com";
$user = "if0_40106418";
$pass= "Mehmet206";
$db = "if0_40106418_unikulup";



try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    // Türkçe karakterler için hayati ayarlar:
    $conn->exec("SET NAMES 'utf8mb4'");
    $conn->exec("SET CHARSET 'utf8mb4'");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
    exit;
}
?>