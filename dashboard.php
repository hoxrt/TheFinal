<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// جلب منتجات المستخدم
$stmt = $pdo->prepare("SELECT * FROM products WHERE seller_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$products = $stmt->fetchAll();

// جلب الرسائل الخاصة بالمستخدم
$stmt = $pdo->prepare("
    SELECT m.*, u.username as sender_name, p.title as product_title 
    FROM messages m 
    JOIN users u ON m.sender_id = u.id 
    JOIN products p ON m.product_id = p.id 
    WHERE m.receiver_id = ?
    ORDER BY m.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$messages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php 
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <main class="dashboard">
        <div class="dashboard-header">
            <h1>مرحباً <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
            <a href="add-product.php" class="btn">إضافة منتج جديد</a>
        </div>

        <div class="dashboard-grid">
            <section class="my-products">
                <h2>منتجاتي</h2>
                <div class="products-list">
                    <?php foreach($products as $product): ?>
                        <div class="product-item">
                            <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="">
                            <div class="product-details">
                                <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                                <p class="price"><?php echo htmlspecialchars($product['price']); ?> ريال</p>
                                <div class="actions">
                                    <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="btn">تعديل</a>
                                    <a href="delete-product.php?id=<?php echo $product['id']; ?>" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="messages">
                <h2>الرسائل الواردة</h2>
                <div class="messages-list">
                    <?php foreach($messages as $message): ?>
                        <div class="message-item">
                            <p class="sender">من: <?php echo htmlspecialchars($message['sender_name']); ?></p>
                            <p class="product">المنتج: <?php echo htmlspecialchars($message['product_title']); ?></p>
                            <p class="content"><?php echo htmlspecialchars($message['message']); ?></p>
                            <p class="date"><?php echo date('Y/m/d', strtotime($message['created_at'])); ?></p>
                            <a href="reply.php?to=<?php echo $message['sender_id']; ?>&product=<?php echo $message['product_id']; ?>" class="btn">رد</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
