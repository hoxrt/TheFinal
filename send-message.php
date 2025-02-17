<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'];
$product_id = $_POST['product_id'];
$message = trim($_POST['message']);

$stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, product_id, message) VALUES (?, ?, ?, ?)");
if ($stmt->execute([$sender_id, $receiver_id, $product_id, $message])) {
    header("Location: product.php?id=$product_id&sent=1");
} else {
    header("Location: product.php?id=$product_id&error=1");
}
exit();
