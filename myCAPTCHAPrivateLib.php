<?php
//include this file whenever you have to use imageconvolution...
//you can use in your project, but keep the comment below :)
//great for any image manipulation library
//Made by Chao Xu(Mgccl) 2/28/07
//www.webdevlogs.com
//V 1.0
if(!function_exists('imageconvolution')){
	function imageconvolution(&$src, $filter, $filter_div, $offset){
		if ($src==NULL) {
			return 0;
		}

		$sx = imagesx($src);
		$sy = imagesy($src);
		$srcback = ImageCreateTrueColor ($sx, $sy);
		ImageCopy($srcback, $src,0,0,0,0,$sx,$sy);

		if($srcback==NULL){
			return 0;
		}

		for ($y=0; $y<$sy; ++$y){
			for($x=0; $x<$sx; ++$x){
				$new_r = $new_g = $new_b = 0;
				$alpha = imagecolorat($srcback, $pxl[0], $pxl[1]);
				$new_a = $alpha >> 24;

				for ($j=0; $j<3; ++$j) {
					$yv = min(max($y - 1 + $j, 0), $sy - 1);
					for ($i=0; $i<3; ++$i) {
						$pxl = array(min(max($x - 1 + $i, 0), $sx - 1), $yv);
						$rgb = imagecolorat($srcback, $pxl[0], $pxl[1]);
						$new_r += (($rgb >> 16) & 0xFF) * $filter[$j][$i];
						$new_g += (($rgb >> 8) & 0xFF) * $filter[$j][$i];
						$new_b += ($rgb & 0xFF) * $filter[$j][$i];
					}
				}

				$new_r = ($new_r/$filter_div)+$offset;
				$new_g = ($new_g/$filter_div)+$offset;
				$new_b = ($new_b/$filter_div)+$offset;

				$new_r = ($new_r > 255)? 255 : (($new_r < 0)? 0:$new_r);
				$new_g = ($new_g > 255)? 255 : (($new_g < 0)? 0:$new_g);
				$new_b = ($new_b > 255)? 255 : (($new_b < 0)? 0:$new_b);

				$new_pxl = ImageColorAllocateAlpha($src, (int)$new_r, (int)$new_g, (int)$new_b, $new_a);
				if ($new_pxl == -1) {
					$new_pxl = ImageColorClosestAlpha($src, (int)$new_r, (int)$new_g, (int)$new_b, $new_a);
				}
				if (($y >= 0) && ($y < $sy)) {
					imagesetpixel($src, $x, $y, $new_pxl);
				}
			}
		}
		imagedestroy($srcback);
		return 1;
	}
}
?>
<?php
function getUserIP(){ 
	$ip = "127.0.0.1"; 

	if (isset($_SERVER)){ 
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){ 
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; 
		} elseif (isset($_SERVER["HTTP_CLIENT_IP"])) { 
			$ip = $_SERVER["HTTP_CLIENT_IP"]; 
		} else { 
			$ip = $_SERVER["REMOTE_ADDR"]; 
		} 
	}else { 
		if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) { 
			$ip = getenv( 'HTTP_X_FORWARDED_FOR' ); 
		} elseif ( getenv( 'HTTP_CLIENT_IP' ) ) { 
			$ip = getenv( 'HTTP_CLIENT_IP' ); 
		} else { 
			$ip = getenv( 'REMOTE_ADDR' ); 
		} 
	} 
	return $ip; 
}
/*
 * This function is used to generate a random string
 * @Para $length: This parameter can determind the length of string.
 */
function generate_random_string($length = 6){
	$characters = "123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$temp_string = "";
	for($i = 0; $i < $length; $i++){
		$temp_string .= $characters[mt_rand(0, strlen($characters))];
	}
	return $temp_string;
}

function generate_image($para_challenge, $font_size_pixel = 15){
	$width = $font_size_pixel*2*strlen($para_challenge);
	$height = $font_size_pixel*2;
	$temp_image = @imagecreatetruecolor($width, $height);
	$font = '/usr/share/fonts/truetype/ttf-dejavu/DejaVuSerifCondensed-Bold.ttf';

	$the_way = rand(0,1);

	switch ($the_way){
		case 0:
			for( $i = 0; $i < strlen($para_challenge); $i++){
				$temp_char_img = @imagecreatetruecolor($font_size_pixel*2, $font_size_pixel*2);
				$degree = mt_rand(-45,45);
				if ($degree >= 0){
					$c_x = $font_size_pixel*1;
					$c_y = $font_size_pixel*1.5;
				}else{
					$c_x = $font_size_pixel*0.5;
					$c_y = $font_size_pixel*1.25;
				}
				imagettftext($temp_char_img, $font_size_pixel, $degree, $c_x, $c_y, 0xFFFFFF, $font, $para_challenge[$i]);
				for( $j=0; $j<15; $j++ ) {
					imagecharup($temp_char_img,0.5,mt_rand(0,imagesx($temp_char_img)),mt_rand(0,imagesy($temp_char_img)),".",0xFFFFFF);
				}
				imagecopymerge($temp_image, $temp_char_img, $i*$font_size_pixel*2, 0, 0, 0, $font_size_pixel*2, $font_size_pixel*2 , 100);
				imagedestroy($temp_char_img);
			}
			$gaussian = array(array(1.0, 2.0, 1.0), array(2.0, 4.0, 2.0), array(1.0, 2.0, 1.0));
			imageconvolution($temp_image, $gaussian, 16, 0);
			break;
		case 1:
			$white = imagecolorallocate($temp_image,255,255,255);
			imagefilledrectangle($temp_image, 0, 0, $width, $height,0xFFFFFF);
			for($i = 0; $i < 100; $i++){
				$char = generate_random_string(1);
				imagettftext($temp_image, rand(0, 10), rand(-45,45), rand(0, $width), rand(0, $height), rand(0,16581375), $font, $char);
			}
			for($i = 0; $i < strlen($para_challenge); $i++){
				imagettftext($temp_image, $font_size_pixel, 0, $font_size_pixel*($i*2+1), $font_size_pixel*1.5, 0x22000000, $font, $para_challenge[$i]);
			}
			break;
	}

	header("Content-type: image/png");
	imagepng($temp_image);
	imagedestroy($temp_image);
}
?>
