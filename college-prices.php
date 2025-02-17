<?php
session_start();
require_once 'config/database.php';

$stmt = $pdo->query("SELECT * FROM college_prices ORDER BY category, item_name");
$prices = $stmt->fetchAll();

// تنظيم المنتجات حسب الفئات
$categories = [];
foreach ($prices as $item) {
    $categories[$item['category']][] = $item;
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أسعار مكتبة الكلية</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <h1>أسعار مكتبة الكلية</h1>
        
        <div class="prices-container">
            <?php foreach ($categories as $category => $items): ?>
                <div class="category-section">
                    <h2><?php echo htmlspecialchars($category); ?></h2>
                    <table class="prices-table">
                        <thead>
                            <tr>
                                <th>اسم المنتج</th>
                                <th>السعر</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                    <td><?php echo htmlspecialchars($item['price']); ?> ريال</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
