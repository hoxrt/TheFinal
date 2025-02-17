<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Path: " . __DIR__ . "<br>";

$test_image = $_SERVER['DOCUMENT_ROOT'] . '/ThisIsFinal/uploads/test.txt';
file_put_contents($test_image, 'test');
echo "Can write test file: " . (file_exists($test_image) ? 'Yes' : 'No') . "<br>";

if (file_exists($test_image)) {
    unlink($test_image);
}
?>
