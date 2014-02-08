<?php

$file = $_GET['file'];

if (file_exists($file))
{
	header('Content-type: image/jpeg');
	
	$im = imagecreatefromjpeg($file);
	
	imagefilter($im, IMG_FILTER_GRAYSCALE);
	
	imagejpeg($im);
	
	imagedestroy($im);
}

?>