<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$upload_dir = __DIR__ . '/uploads';

// إنشاء مجلد uploads إذا لم يكن موجوداً
if (!file_exists($upload_dir)) {
    if (!mkdir($upload_dir, 0777, true)) {
        die('فشل في إنشاء مجلد uploads');
    }
}

// ضبط الصلاحيات
chmod($upload_dir, 0777);

echo "تم إعداد مجلد uploads بنجاح";
echo "<br>المسار: " . $upload_dir;
echo "<br>الصلاحيات: " . substr(sprintf('%o', fileperms($upload_dir)), -4);
?>
