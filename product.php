<?php
session_start();
require_once 'config/database.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// جلب تفاصيل المنتج
$stmt = $pdo->prepare("
    SELECT p.*, u.username, u.phone 
    FROM products p 
    JOIN users u ON p.seller_id = u.id 
    WHERE p.id = ?
");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

// جلب التعليقات إذا وجدت
$stmt = $pdo->prepare("
    SELECT m.*, u.username 
    FROM messages m 
    JOIN users u ON m.sender_id = u.id 
    WHERE m.product_id = ? 
    ORDER BY m.created_at DESC
");
$stmt->execute([$product_id]);
$comments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['title']); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <div class="product-details">
            <div class="product-image">
                <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="صورة المنتج">
            </div>
            <div class="product-info">
                <h1><?php echo htmlspecialchars($product['title']); ?></h1>
                <p class="price"><?php echo htmlspecialchars($product['price']); ?> ريال</p>
                <p class="condition">الحالة: <?php echo htmlspecialchars($product['condition_status']); ?></p>
                <p class="description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                <div class="seller-info">
                    <h3>معلومات البائع</h3>
                    <p>البائع: <?php echo htmlspecialchars($product['username']); ?></p>
                    <p>رقم الهاتف: <?php echo htmlspecialchars($product['phone']); ?></p>
                </div>
                
                <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != $product['seller_id']): ?>
                    <button class="btn" onclick="showMessageForm()">راسل البائع</button>
                <?php endif; ?>
            </div>
        </div>

        <?php if(isset($_SESSION['user_id'])): ?>
            <div id="messageForm" class="message-form" style="display:none;">
                <form method="POST" action="send-message.php">
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <input type="hidden" name="receiver_id" value="<?php echo $product['seller_id']; ?>">
                    <textarea name="message" required placeholder="اكتب رسالتك هنا..."></textarea>
                    <button type="submit" class="btn">إرسال</button>
                </form>
            </div>
        <?php endif; ?>
    </main>

    <script>
    function showMessageForm() {
        document.getElementById('messageForm').style.display = 'block';
    }
    </script>
</body>
</html>
