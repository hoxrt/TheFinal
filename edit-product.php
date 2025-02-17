<?php
session_start();
require_once 'config/database.php';
require_once 'includes/image_handler.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// التحقق من ملكية المنتج
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
$stmt->execute([$product_id, $_SESSION['user_id']]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $image_path = $product['image_path'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $image_result = handleImageUpload($_FILES['image']);
            if (!isset($image_result['error'])) {
                // حذف الصورة القديمة إذا كانت موجودة
                if ($product['image_path'] && file_exists($product['image_path'])) {
                    unlink($product['image_path']);
                }
                $image_path = $image_result['path'];
            } else {
                $error = $image_result['error'];
            }
        }

        if (!isset($error)) {
            $stmt = $pdo->prepare("UPDATE products SET title = ?, description = ?, price = ?, category = ?, condition_status = ?, image_path = ? WHERE id = ? AND seller_id = ?");
            $stmt->execute([
                trim($_POST['title']),
                trim($_POST['description']),
                floatval($_POST['price']),
                $_POST['category'],
                $_POST['condition'],
                $image_path,
                $product_id,
                $_SESSION['user_id']
            ]);

            $_SESSION['success_message'] = 'تم تحديث المنتج بنجاح';
            header('Location: products.php');
            exit();
        }
    } catch (Exception $e) {
        $error = 'حدث خطأ: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تعديل المنتج</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <form method="POST" enctype="multipart/form-data" class="product-form">
            <h2>تعديل المنتج</h2>
            
            <?php if($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="form-group">
                <label>عنوان المنتج</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>الوصف</label>
                <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label>السعر</label>
                <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>الفئة</label>
                <select name="category" required>
                    <option value="مذكرات" <?php echo $product['category'] == 'مذكرات' ? 'selected' : ''; ?>>مذكرات</option>
                    <option value="كتب" <?php echo $product['category'] == 'كتب' ? 'selected' : ''; ?>>كتب</option>
                    <option value="أدوات مكتبية" <?php echo $product['category'] == 'أدوات مكتبية' ? 'selected' : ''; ?>>أدوات مكتبية</option>
                    <option value="أخرى" <?php echo $product['category'] == 'أخرى' ? 'selected' : ''; ?>>أخرى</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>الحالة</label>
                <select name="condition" required>
                    <option value="جديد" <?php echo $product['condition_status'] == 'جديد' ? 'selected' : ''; ?>>جديد</option>
                    <option value="مستعمل - ممتاز" <?php echo $product['condition_status'] == 'مستعمل - ممتاز' ? 'selected' : ''; ?>>مستعمل - ممتاز</option>
                    <option value="مستعمل - جيد" <?php echo $product['condition_status'] == 'مستعمل - جيد' ? 'selected' : ''; ?>>مستعمل - جيد</option>
                    <option value="مستعمل - مقبول" <?php echo $product['condition_status'] == 'مستعمل - مقبول' ? 'selected' : ''; ?>>مستعمل - مقبول</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>الصورة الحالية</label>
                <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="الصورة الحالية" style="max-width: 200px;">
                <label>تغيير الصورة (اختياري)</label>
                <input type="file" name="image" accept="image/*">
            </div>
            
            <button type="submit" class="btn">حفظ التعديلات</button>
            <a href="dashboard.php" class="btn btn-secondary">إلغاء</a>
        </form>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
