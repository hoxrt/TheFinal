<?php
// تأكد من إنشاء المجلدات المطلوبة
$directories = [
    __DIR__ . '/uploads',
    __DIR__ . '/assets/images'
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    chmod($dir, 0777);
}

// إنشاء ملف الصورة الافتراضية إذا لم يكن موجوداً
$default_image = __DIR__ . '/assets/images/default-product.png';
if (!file_exists($default_image)) {
    // إنشاء صورة افتراضية بسيطة
    $im = imagecreatetruecolor(200, 200);
    $bg = imagecolorallocate($im, 240, 240, 240);
    imagefill($im, 0, 0, $bg);
    imagepng($im, $default_image);
    imagedestroy($im);
}

echo "تم إعداد النظام بنجاح!";
?>
