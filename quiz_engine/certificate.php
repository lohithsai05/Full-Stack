<?php
ob_start();
session_start();

date_default_timezone_set("Asia/Kolkata");

if (!isset($_SESSION['user_id']) || !isset($_POST['percentage'])) {
    exit("Invalid access");
}

$name       = strtoupper($_SESSION['user_name']);
$college    = $_SESSION['college'];
$percentage = intval($_POST['percentage']);

/* FIXED DATE WITH YEAR 2026 */
$date = date("d F") . " 2026";

/* ONLY QUIZ-2026 (NO NUMBER) */
$certificate_id = "QUIZ-2026";

$width = 1600;
$height = 1100;

$image = imagecreatetruecolor($width, $height);

/* Colors */
$white = imagecolorallocate($image, 255, 255, 255);
$blue  = imagecolorallocate($image, 25, 60, 110);
$gold  = imagecolorallocate($image, 200, 160, 40);
$black = imagecolorallocate($image, 0, 0, 0);

/* Background */
imagefill($image, 0, 0, $white);

/* Outer Border */
imagesetthickness($image, 12);
imagerectangle($image, 20, 20, $width-20, $height-20, $blue);

/* Inner Border */
imagesetthickness($image, 5);
imagerectangle($image, 60, 60, $width-60, $height-60, $gold);

/* Font Path */
$font = __DIR__ . "/certificate_font.ttf";

/* Center Text Function */
function centerText($img, $size, $y, $color, $font, $text) {
    $bbox = imagettfbbox($size, 0, $font, $text);
    $textWidth = $bbox[2] - $bbox[0];
    $imgWidth = imagesx($img);
    $x = ($imgWidth - $textWidth) / 2;
    imagettftext($img, $size, 0, $x, $y, $color, $font, $text);
}

/* Title */
centerText($image, 75, 240, $blue, $font, "CERTIFICATE");
centerText($image, 30, 290, $gold, $font, "OF ACHIEVEMENT");

/* Presented Line */
centerText($image, 28, 380, $black, $font, "This Certificate is Proudly Presented To");

/* Name */
centerText($image, 65, 500, $black, $font, $name);

/* College */
centerText($image, 30, 580, $black, $font, "Representing $college");

/* Score */
centerText($image, 40, 660, $blue, $font, "Final Score: $percentage%");

/* Bottom Left Date */
imagettftext($image, 22, 0, 200, 1000, $black, $font, $date);

/* Bottom Right QUIZ-2026 */
$bbox = imagettfbbox(22, 0, $font, $certificate_id);
$textWidth = $bbox[2] - $bbox[0];
imagettftext($image, 22, 0, $width - $textWidth - 200, 1000, $black, $font, $certificate_id);

/* Output */
ob_clean();
header('Content-Type: image/jpeg');
header('Content-Disposition: attachment; filename="Certificate_'.$name.'.jpg"');

imagejpeg($image, null, 95);
imagedestroy($image);
exit();
?>