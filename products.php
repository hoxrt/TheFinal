<?php
session_start();
require_once 'config/database.php';

// تحديد الصفحة الحالية للتصفح
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 12; // عدد المنتجات في كل صفحة
$offset = ($page - 1) * $per_page;

// جلب إجمالي عدد المنتجات
$total_stmt = $pdo->query("SELECT COUNT(*) FROM products");
$total_products = $total_stmt->fetchColumn();
$total_pages = ceil($total_products / $per_page);

// جلب المنتجات مع معلومات البائع - تم تصحيح الاستعلام
$stmt = $pdo->prepare("
    SELECT p.*, u.username 
    FROM products p 
    JOIN users u ON p.seller_id = u.id 
    ORDER BY p.created_at DESC 
    LIMIT :limit OFFSET :offset
");

// تعيين القيم كأرقام صحيحة
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>المنتجات المتاحة</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <h1>المنتجات المتاحة</h1>
        
        <div class="products-grid">
            <?php foreach($products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <?php 
                        $image_path = $product['image_path'];
                        if (!empty($image_path) && file_exists($image_path)) {
                            // استخدام المسار النسبي مباشرة
                            echo '<img src="' . htmlspecialchars($image_path) . '" alt="' . htmlspecialchars($product['title']) . '">';
                        } else {
                            // استخدام الصورة الافتراضية
                            echo '<img src="assets/images/default-product.png" alt="صورة افتراضية">';
                        }
                        ?>
                    </div>
                    <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                    <p class="price"><?php echo htmlspecialchars($product['price']); ?> ريال</p>
                    <p class="category"><?php echo htmlspecialchars($product['category']); ?></p>
                    <p class="seller">البائع: <?php echo htmlspecialchars($product['username']); ?></p>
                    <div class="product-actions">
                        <a href="product.php?id=<?php echo $product['id']; ?>" class="btn">عرض التفاصيل</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- أزرار التنقل بين الصفحات -->
        <?php if($total_pages > 1): ?>
            <div class="pagination">
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" 
                       class="btn <?php echo $page === $i ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
