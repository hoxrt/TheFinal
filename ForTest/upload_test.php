<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// اختبار المجلدات والصلاحيات
$upload_dir = __DIR__ . '/uploads';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

echo "<h2>معلومات النظام:</h2>";
echo "مسار المجلد: " . $upload_dir . "<br>";
echo "الصلاحيات: " . substr(sprintf('%o', fileperms($upload_dir)), -4) . "<br>";
echo "قابل للكتابة: " . (is_writable($upload_dir) ? 'نعم' : 'لا') . "<br>";
echo "المستخدم الحالي: " . get_current_user() . "<br>";
echo "معرف المجموعة: " . getmyuid() . "<br>";

// نموذج اختبار التحميل
echo "<h2>اختبار تحميل الصور:</h2>";
?>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="test_image">
    <button type="submit">تجربة التحميل</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['test_image'])) {
    require_once 'includes/image_handler.php';
    $result = handleImageUpload($_FILES['test_image']);
    echo "<pre>";
    print_r($result);
    echo "</pre>";
}
?>
