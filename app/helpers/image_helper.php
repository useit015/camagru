<?php

function resize_image($url, $width, $height) {
	list($width_orig, $height_orig) = getimagesize($url);
	$ratio_orig = $width_orig / $height_orig;
	if ($width / $height > $ratio_orig)
		$width = $height * $ratio_orig;
	else
		$height = $width / $ratio_orig;
	$src = imagecreatefromstring(file_get_contents($url));
	$dst = imagecreatetruecolor($width, $height);
	imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	return $dst;
}

function watermark($imgUrl, $supUrl, $x, $y) {
	$stamp = imagecreatefromstring(file_get_contents($supUrl));
	$imgTmp = resize_image($imgUrl, 800, 600);
	$sx = imagesx($stamp);
	$sy = imagesy($stamp);
	imagecopy($imgTmp, $stamp, $x - ($sx / 2), $y - ($sy / 2), 0, 0, $sx, $sy);
	imagejpeg($imgTmp, $imgUrl);
	imagedestroy($imgTmp);
}

?>