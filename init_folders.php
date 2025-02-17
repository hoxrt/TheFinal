<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// المجلدات المطلوبة
$directories = [
    'uploads',
    'assets/images'
];

foreach ($directories as $dir) {
    $path = __DIR__ . '/' . $dir;
    if (!file_exists($path)) {
        if (mkdir($path, 0777, true)) {
            echo "تم إنشاء المجلد: $dir <br>";
            chmod($path, 0777);
        } else {
            echo "فشل في إنشاء المجلد: $dir <br>";
        }
    } else {
        echo "المجلد موجود: $dir <br>";
    }
}

// إنشاء صورة افتراضية إذا لم تكن موجودة
$default_image = __DIR__ . '/assets/images/default-product.png';
if (!file_exists($default_image)) {
    // إنشاء صورة بيضاء بسيطة
    $image = imagecreatetruecolor(200, 200);
    $bg = imagecolorallocate($image, 240, 240, 240);
    imagefill($image, 0, 0, $bg);
    imagepng($image, $default_image);
    imagedestroy($image);
    echo "تم إنشاء الصورة الافتراضية<br>";
}

echo "تم الانتهاء من الإعداد<br>";
?>
