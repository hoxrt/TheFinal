<?php
session_start();
require_once 'config/database.php';

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$condition = isset($_GET['condition']) ? $_GET['condition'] : '';
$max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : '';

$params = [];
$where_conditions = [];

if ($search) {
    $where_conditions[] = "(title LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category) {
    $where_conditions[] = "category = ?";
    $params[] = $category;
}

if ($condition) {
    $where_conditions[] = "condition_status = ?";
    $params[] = $condition;
}

if ($max_price) {
    $where_conditions[] = "price <= ?";
    $params[] = $max_price;
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

$sql = "SELECT p.*, u.username 
        FROM products p 
        JOIN users u ON p.seller_id = u.id 
        $where_clause 
        ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>بحث عن المنتجات</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <form class="search-form" method="GET">
            <div class="search-controls">
                <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="ابحث عن منتج...">
                <select name="category">
                    <option value="">كل الفئات</option>
                    <option value="مذكرات" <?php echo $category == 'مذكرات' ? 'selected' : ''; ?>>مذكرات</option>
                    <option value="كتب" <?php echo $category == 'كتب' ? 'selected' : ''; ?>>كتب</option>
                    <option value="أدوات مكتبية" <?php echo $category == 'أدوات مكتبية' ? 'selected' : ''; ?>>أدوات مكتبية</option>
                </select>
                <input type="number" name="max_price" value="<?php echo $max_price; ?>" placeholder="السعر الأقصى">
                <button type="submit" class="btn">بحث</button>
            </div>
        </form>

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
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
