<?php
session_start();
require_once 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $phone = trim($_POST['phone']);

    // التحقق من عدم وجود المستخدم مسبقاً
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $username]);
    
    if ($stmt->rowCount() > 0) {
        $error = 'البريد الإلكتروني أو اسم المستخدم مسجل مسبقاً';
    } else {
        // إنشاء حساب جديد
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, phone) VALUES (?, ?, ?, ?)");
        if($stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT), $phone])) {
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل حساب جديد</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <form method="POST" class="auth-form">
            <h2>تسجيل حساب جديد</h2>
            <?php if($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="form-group">
                <label>اسم المستخدم</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="form-group">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>كلمة المرور</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label>رقم الهاتف</label>
                <input type="tel" name="phone" required>
            </div>
            
            <button type="submit" class="btn">تسجيل</button>
            <p>لديك حساب بالفعل؟ <a href="login.php">تسجيل دخول</a></p>
        </form>
    </div>
</body>
</html>
