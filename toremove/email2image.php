<?php

$email = $_GET['email'];

$im = imagecreate(160, 16);

// White background and blue text
$bg = imagecolorallocate($im, 255, 255, 255);
$textcolor = imagecolorallocate($im, 50, 50, 50);

// Write the string at the top left
imagestring($im, 2, 0, 0, $email, $textcolor);

// Output the image
header('Content-type: image/png');

imagepng($im);
imagedestroy($im);

?>
