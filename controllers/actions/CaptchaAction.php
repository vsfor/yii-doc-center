<?php
namespace app\controllers\actions;

/**
 * 获取验证码图片
 */
class CaptchaAction extends \yii\captcha\CaptchaAction
{
    public $width = 120;
    public $height = 50;
    public $padding = 2;
    public $offset = 4;
    public $backColor = 0xFDFDFD;
    public $foreColor = 0x2040A0;
    public $fontFile = '@app/assets/fonts/syheiti.ttf';
    //gd | imagick
    public $imageLibrary = 'gd';

    /**
     * 固定长度 = 3
     */
    protected function generateVerifyCode()
    {
        $letters = '他的习惯还不错ABDEFKTNH阿拉伯语不常用';
        $len = mb_strlen($letters, 'UTF-8') - 1;
        $code = '';
        for ($i = 0; $i < 3; ++$i) {
            $_i = mt_rand(0,$len);
            $code .= mb_substr($letters,$_i,1,'UTF-8');
        }
        return $code;
    }

    //handle CJK by mbString
    public function renderImageByGD($code)
    {
        $image = imagecreatetruecolor($this->width, $this->height);

        $backColor = imagecolorallocate(
            $image,
            (int) ($this->backColor % 0x1000000 / 0x10000),
            (int) ($this->backColor % 0x10000 / 0x100),
            $this->backColor % 0x100
        );
        imagefilledrectangle($image, 0, 0, $this->width - 1, $this->height - 1, $backColor);
        imagecolordeallocate($image, $backColor);

        if ($this->transparent) {
            imagecolortransparent($image, $backColor);
        }

        $foreColor = imagecolorallocate(
            $image,
            (int) ($this->foreColor % 0x1000000 / 0x10000),
            (int) ($this->foreColor % 0x10000 / 0x100),
            $this->foreColor % 0x100
        );

        $length = mb_strlen($code);
        $box = imagettfbbox(30, 0, $this->fontFile, $code);
        $w = $box[4] - $box[0] + $this->offset * ($length - 1);
        $h = $box[1] - $box[5];
        $scale = min(($this->width - $this->padding * 2) / $w, ($this->height - $this->padding * 2) / $h);
        $x = 10;
        $y = round($this->height * 27 / 40);
        for ($i = 0; $i < $length; ++$i) {
            $fontSize = (int) (mt_rand(16, 32) * $scale * 0.8);
            $angle = mt_rand(-30, 30);
            $letter = mb_substr($code, $i, 1);
            $box = imagettftext($image, $fontSize, $angle, $x, $y, $foreColor, $this->fontFile, $letter);
            $x = $box[2] + $this->offset;
        }

        imagecolordeallocate($image, $foreColor);

        ob_start();
        imagepng($image);
        imagedestroy($image);

        return ob_get_clean();
    }

    public function renderImageByImagick($code)
    {
        $backColor = $this->transparent ? new \ImagickPixel('transparent') : new \ImagickPixel('#' . str_pad(dechex($this->backColor), 6, 0, STR_PAD_LEFT));
        $foreColor = new \ImagickPixel('#' . str_pad(dechex($this->foreColor), 6, 0, STR_PAD_LEFT));

        $image = new \Imagick();
        $image->newImage($this->width, $this->height, $backColor);

        $draw = new \ImagickDraw();
        $draw->setFont($this->fontFile);
        $draw->setFontSize(30);
        $fontMetrics = $image->queryFontMetrics($draw, $code);

        $length = mb_strlen($code);
        $w = (int) $fontMetrics['textWidth'] - 8 + $this->offset * ($length - 1);
        $h = (int) $fontMetrics['textHeight'] - 8;
        $scale = min(($this->width - $this->padding * 2) / $w, ($this->height - $this->padding * 2) / $h);
        $x = 10;
        $y = round($this->height * 27 / 40);
        for ($i = 0; $i < $length; ++$i) {
            $letter = mb_substr($code,$i,1);
            $draw = new \ImagickDraw();
            $draw->setFont($this->fontFile);
            $draw->setFontSize((int) (mt_rand(26, 32) * $scale * 0.8));
            $draw->setFillColor($foreColor);
            $image->annotateImage($draw, $x, $y, mt_rand(-30, 30), $letter);
            $fontMetrics = $image->queryFontMetrics($draw, $letter);
            $x += (int) $fontMetrics['textWidth'] + $this->offset;
        }

        $image->setImageFormat('png');
        return $image->getImageBlob();
    }

    public function generateValidationHash($code)
    {
        for ($h = 0, $i = mb_strlen($code) - 1; $i >= 0; --$i) {
            $letter = mb_substr($code,$i,1);
            $ord = unpack('n', mb_convert_encoding($letter, 'UTF-16BE','UTF-8'));
            $h += $ord[1];
        }

        return $h;
    }

}
