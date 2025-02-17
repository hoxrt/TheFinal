<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// إنشاء مجلد التحميل
$upload_dir = __DIR__ . '\\uploads';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// إنشاء مجلد الصور الافتراضية
$images_dir = __DIR__ . '\\assets\\images';
if (!file_exists($images_dir)) {
    mkdir($images_dir, 0777, true);
}

echo "تم إنشاء المجلدات بنجاح";
echo "<br>مجلد التحميل: " . $upload_dir;
echo "<br>مجلد الصور: " . $images_dir;
?>
