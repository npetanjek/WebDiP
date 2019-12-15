<?php
$str = md5(microtime());
$str = substr($str, 0, 6);
session_start();
$_SESSION['cap_code'] = $str;
$img = imagecreate(100, 30);
imagecolorallocate($img, 255, 255, 255);
$txtcolor = imagecolorallocate($img, 0, 0, 0);
imagestring($img,5, 5, 5, $str, $txtcolor);
header('Content:image/jpeg');
imagejpeg($img);