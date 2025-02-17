<?php
session_start();
require_once 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'بيانات الدخول غير صحيحة';
    }
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <form method="POST" class="auth-form">
            <h2>تسجيل الدخول</h2>
            <?php if($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="form-group">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>كلمة المرور</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">دخول</button>
            <p>ليس لديك حساب؟ <a href="register.php">سجل الآن</a></p>
        </form>
    </div>
</body>
</html>
