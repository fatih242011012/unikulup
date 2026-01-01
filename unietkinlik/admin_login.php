<?php
session_start();
if(isset($_POST['giris'])) {
    if($_POST['user'] == 'admin' && $_POST['pass'] == '1234') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Hatalı kullanıcı adı veya şifre!";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Girişi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card shadow p-4" style="width: 350px;">
        <h4 class="text-center mb-4">Yönetici Girişi</h4>
        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <input type="text" name="user" class="form-control" placeholder="Kullanıcı Adı" required>
            </div>
            <div class="mb-3">
                <input type="password" name="pass" class="form-control" placeholder="Şifre" required>
            </div>
            <button type="submit" name="giris" class="btn btn-primary w-100">Giriş Yap</button>
        </form>
        <div class="text-center mt-3"><a href="index.php">Siteye Dön</a></div>
    </div>
</body>
</html>