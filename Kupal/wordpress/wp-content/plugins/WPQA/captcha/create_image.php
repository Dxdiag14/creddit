<?php

/* @author    2codeThemes
*  @package   WPQA/captcha
*  @version   1.0
*/

$type = strip_tags(isset($_GET["captcha_type"])?$_GET["captcha_type"]:"");
wpqa_captcha($type);

function wpqa_captcha($type) { 
	if (!session_id() && !headers_sent()) {
		session_start();
	}
	$md5_hash = md5(rand(0,999)); 
	$wpqa_code_captcha = str_replace('0', '', $md5_hash);
	$wpqa_code_captcha = substr($wpqa_code_captcha, 15, 5);
	$_SESSION["wpqa_code_captcha_".$type] = $wpqa_code_captcha;
	$width  = 128;
	$height = 40; 
	$image  = ImageCreate($width, $height); 

	$white = ImageColorAllocate($image, 255, 255, 255);
	$black = ImageColorAllocate($image, rand(0, 100), 0, rand(0, 50));
	$grey  = ImageColorAllocate($image, 204, 204, 204);
	
	$red   = imagecolorallocatealpha($image, 255, 0, 0, 75);
	$green = imagecolorallocatealpha($image, 0, 255, 0, 75);
	$blue  = imagecolorallocatealpha($image, 0, 0, 255, 75);
	imagefilledrectangle($image, 0, 0, $width, $height, $white);
	
	imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $red);
	imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $green);
	imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $blue);
	
	imagefilledrectangle($image, 0, 0, $width, 0, $black);
	imagefilledrectangle($image, $width - 1, 0, $width - 1, $height - 1, $black);
	imagefilledrectangle($image, 0, 0, 0, $height - 1, $black);
	imagefilledrectangle($image, 0, $height - 1, $width, $height - 1, $black);
	
	ImageFill($image, 0, 0, $black); 
	$x = 20;
	$y = 24;
	$angle = rand(-7, -10);
	if (function_exists('imagettftext')) {
		imagettftext ($image, 20,$angle , rand(20, $x), $y+rand(1,3), $black,'./arial.ttf', $wpqa_code_captcha);
	}else {
		imagestring ($image, 5, $x+rand(20,15), $y-rand(10,15), $wpqa_code_captcha, $black);
	}
	ImageRectangle($image,0,0,$width-1,$height-1,$black);
	 
	imageline($image, $width/2, 0, $width/2, $height, $black); 

	header("Content-Type: image/png"); 
	ImagePng($image);
	ImageDestroy($image);
}
exit();?>