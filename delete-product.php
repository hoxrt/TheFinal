<?php
session_start();
require_once 'config/database.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// التحقق من وجود معرف المنتج
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = 'معرف المنتج غير صالح';
    header('Location: dashboard.php');
    exit();
}

$product_id = (int)$_GET['id'];

try {
    // التحقق من ملكية المنتج
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
    $stmt->execute([$product_id, $_SESSION['user_id']]);
    $product = $stmt->fetch();

    if (!$product) {
        $_SESSION['error'] = 'لا يمكنك حذف هذا المنتج';
        header('Location: dashboard.php');
        exit();
    }

    // حذف الصورة إذا كانت موجودة
    if ($product['image_path'] && file_exists($product['image_path'])) {
        unlink($product['image_path']);
    }

    // حذف المنتج
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
    $stmt->execute([$product_id, $_SESSION['user_id']]);

    $_SESSION['success'] = 'تم حذف المنتج بنجاح';
} catch (PDOException $e) {
    $_SESSION['error'] = 'حدث خطأ أثناء حذف المنتج';
}

header('Location: dashboard.php');
exit();
