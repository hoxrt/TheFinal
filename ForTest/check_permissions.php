<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$upload_dir = __DIR__ . '/uploads';

// إنشاء المجلد إذا لم يكن موجوداً
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// ضبط الصلاحيات
chmod($upload_dir, 0777);

echo "مجلد التحميل: " . $upload_dir . "<br>";
echo "الصلاحيات: " . substr(sprintf('%o', fileperms($upload_dir)), -4) . "<br>";
echo "قابل للكتابة: " . (is_writable($upload_dir) ? 'نعم' : 'لا') . "<br>";
?>
