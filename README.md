# math-captcha
A simple math captcha to use with PHP application

## How to use
```
require_once ('path\to\MathCaptcha.php');

$mathCaptcha = new MathCaptcha\Captcha();

$mathCaptcha->generate();

$mathCaptcha->output();
```
## How to verify
```
require_once ('path\to\MathCaptcha.php');

$mathCaptcha = new MathCaptcha\Captcha();

$mathCaptcha->verify( $answer)
```
Enjoy!!
