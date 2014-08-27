<?php namespace CaptchaGenerator;

class Captcha {
	private $imageWidth = 300;
	private $imageHeight = 100;
	private $fontSize = 32;
	private $backgroundColor = array(255, 255, 255);
	private $textColor = array(
		array(0, 0, 0),
		array(255, 0, 0),
		array(0, 128, 0)
	);

	public function generate($string = null) {
		ob_start();

		$image = ImageCreateTrueColor($this->imageWidth, $this->imageHeight);
		$background = ImageColorAllocate($image, $this->backgroundColor[0], $this->backgroundColor[1], $this->backgroundColor[2]);
		ImageFill($image, 0, 0, $background);

		for($i = 0; $i < strlen($string); $i++) {
			$x = ($this->imageWidth - 180) / 2 + $i * $this->fontSize;
			$y = ($this->imageHeight / 2) + ($this->fontSize / 2);

			ImageFtText($image, $this->fontSize, rand(0, 60), $x, $y, 
				ImageColorAllocate($image, 0, 0, 0),
				public_path().'/assets/fonts/cousine-regular.ttf', strtoupper($string[$i]));
		}
		
		ImagePNG($image);
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
	public function generateString($length) {
		$abc = 'abcdefghijklmnopqrstuvwxyz1234567890';
		$res = '';

		for($i = 0; $i < $length; $i++) {
			$res .= $abc[rand(0, strlen($abc) - 1)];
		}
		return $res;
	}
}