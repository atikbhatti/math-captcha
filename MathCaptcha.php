<?php
namespace MathCaptcha;

class Captcha
{
	private $salt = '9ie37f';

	private $numberI;
	private $numberII;
	private $answer = null;
	private $captchaImg = null;
	private $captchaId = 0;
	
	public function __construct( $captchaId = 0 )
	{
		$this->captchaId = hash('sha256', 'captcha_' . $captchaId);

		if ( isset($_SESSION[$this->captchaId]) ) {
			$this->answer = $_SESSION[$this->captchaId];
			// unset($_SESSION[$this->captchaId]);
		}
	}
	
	// Generates new captcha
	public function generate()
	{
		$this->numberI = rand(1, 11) * rand(1, 3);
		$this->numberII = rand(1, 11) * rand(1, 3);
		
		$answer = $this->salt . ($this->numberI + $this->numberII);

		$_SESSION[$this->captchaId] = $this->answer = hash('sha256', $answer);

		// Create a canvas
		if ( ($this->captchaImg = @imagecreatetruecolor(99, 30)) === false ) {
			throw new Exception('Creation of true color image failed');
		}
		
		// Allocate black and white colors
		$color_black = imagecolorallocate($this->captchaImg, 0, 0, 0);
		$color_white = imagecolorallocate($this->captchaImg, 63, 188, 246);
		
		// Make the background of the image white
		imagefilledrectangle($this->captchaImg, 0, 0, 99, 30, $color_white);
		
		// Draw the math question on the image using black color
		imagestring($this->captchaImg, 10, 15, 7,  $this->numberI . ' + ' . $this->numberII . ' = ', $color_black);	
	}
	
	// Outputs captcha png
	public function output()
	{	
		if ( $this->captchaImg === null ) {
			throw new Exception('Captcha image has not been generated');
		}
		
		// header('Content-Type: image/png');
		ob_start();
		imagepng($this->captchaImg);
		$data = ob_get_contents();
		ob_end_clean();
		
		imagedestroy($this->captchaImg);

		echo '<img src="data:image/png;base64,' . base64_encode($data).'" style="border-radius: 3px;"/>';
	}
	
	// Verifies captcha
	public function verify( $answer )
	{
		// Check if math captcha has been generated
		if ( $this->answer === null || empty($answer) )
			return false;

		// Validates captcha
		$ans = (int) trim($answer);
		$ans = hash('sha256', ($this->salt . $ans));

		if ( $this->answer ===  $ans)
			return true;
		else
			return false;
	}
}