<?php
session_start();
require_once 'config/database.php';

// استعلام لجلب آخر المنتجات المضافة
$stmt = $pdo->query("SELECT products.*, users.username FROM products 
                     JOIN users ON products.seller_id = users.id 
                     ORDER BY created_at DESC LIMIT 8");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مشروعنا - تبادل المستلزمات الدراسية</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <section class="hero">
            <h1>مرحباً بك في مشروعنا</h1>
            <p>المكان الأمثل لتبادل المستلزمات الدراسية بين الطلاب</p>
        </section>

        <section class="latest-products">
            <h2>أحدث المنتجات</h2>
            <div class="products-grid">
                <?php foreach($products as $product): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="صورة المنتج">
                        <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                        <p class="price"><?php echo htmlspecialchars($product['price']); ?> ريال</p>
                        <p class="seller">البائع: <?php echo htmlspecialchars($product['username']); ?></p>
                        <a href="product.php?id=<?php echo $product['id']; ?>" class="btn">عرض التفاصيل</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>جميع الحقوق محفوظة &copy; <?php echo date('Y'); ?> - مشروعنا</p>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
