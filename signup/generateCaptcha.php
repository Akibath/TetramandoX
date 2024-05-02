<?php
	session_start();
	header('Content-type: image/jpeg');
	$captchaText = $_SESSION['captchaRand'];
	$captchaImage = imageCreate(225, 60);
	imageColorAllocate($captchaImage, 255, 255, 255);
	for($i=0;$i<100;$i++){
		$x1 = rand(1, 300);
		$y1 = rand(1, 300);
		$x2 = rand(1, 300);
		$y2 = rand(1, 300);
		imageLine($captchaImage, $x1, $y1, $x2, $y2, imageColorAllocate($captchaImage, 0, 0, 200));
	}
	imageTtfText($captchaImage, 25, 5, 40, 50, imageColorAllocate($captchaImage, 0, 0, 200), 'captchaFont.ttf', $captchaText);
	imageJpeg($captchaImage);
?>