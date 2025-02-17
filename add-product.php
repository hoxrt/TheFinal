<?php
session_start();
require_once 'config/database.php';
require_once 'includes/image_handler.php';
require_once 'includes/upload_config.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $image_result = handleImageUpload($_FILES['image']);
        
        if (isset($image_result['error'])) {
            $error = $image_result['error'];
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO products (seller_id, title, description, price, category, condition_status, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
                
                if ($stmt->execute([
                    $_SESSION['user_id'],
                    trim($_POST['title']),
                    trim($_POST['description']),
                    floatval($_POST['price']),
                    $_POST['category'],
                    $_POST['condition'],
                    $image_result['path']
                ])) {
                    // إضافة تأكيد نجاح العملية
                    $_SESSION['success_message'] = 'تم إضافة المنتج بنجاح';
                    header('Location: products.php');
                    exit();
                }
            } catch (PDOException $e) {
                $error = 'حدث خطأ في إضافة المنتج: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إضافة منتج جديد</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <form method="POST" enctype="multipart/form-data" class="product-form">
            <h2>إضافة منتج جديد</h2>
            
            <div class="form-group">
                <label>عنوان المنتج</label>
                <input type="text" name="title" required>
            </div>
            
            <div class="form-group">
                <label>الوصف</label>
                <textarea name="description" required></textarea>
            </div>
            
            <div class="form-group">
                <label>السعر</label>
                <input type="number" step="0.01" name="price" required>
            </div>
            
            <div class="form-group">
                <label>الفئة</label>
                <select name="category" required>
                    <option value="مذكرات">مذكرات</option>
                    <option value="كتب">كتب</option>
                    <option value="أدوات مكتبية">أدوات مكتبية</option>
                    <option value="أخرى">أخرى</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>الحالة</label>
                <select name="condition" required>
                    <option value="جديد">جديد</option>
                    <option value="مستعمل - ممتاز">مستعمل - ممتاز</option>
                    <option value="مستعمل - جيد">مستعمل - جيد</option>
                    <option value="مستعمل - مقبول">مستعمل - مقبول</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>صورة المنتج</label>
                <input type="file" name="image" accept="image/*" required>
            </div>
            
            <button type="submit" class="btn">إضافة المنتج</button>
        </form>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
