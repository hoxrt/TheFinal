<?php
require_once 'upload_config.php';

function handleImageUpload($file) {
    // تبسيط المسارات
    $upload_dir = 'uploads/';
    
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['error' => 'خطأ في تحميل الملف'];
    }

    // تحقق من نوع الملف
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime_type, $allowed_types)) {
        return ['error' => 'نوع الملف غير مدعوم'];
    }

    // اسم الملف الجديد
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = uniqid() . '_' . time() . '.' . $extension;
    $upload_path = $upload_dir . $new_filename;

    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        return ['path' => $upload_path];
    }

    return ['error' => 'فشل في حفظ الملف'];
}
?>
