<?php

namespace link\hefang\site\content\controllers;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\helpers\FileHelper;
use link\hefang\helpers\RandomHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;


class CaptchaController extends BaseController
{
    const CAPTCHA_SESSION_KEY = 'CAPTCHA_ANSWER';

    public function image(string $type = null): BaseView
    {
        $type = $this->_request("type", $type);
        $length = Mvc::getConfig("{$type}|captcha_length", 6);
        $text = RandomHelper::string($length, RandomHelper::LETTERS(Mvc::getConfig("{$type}|captcha_type", 'LDU')));
        $fontSize = 40;
        $width = $fontSize * $length * .8;
        $height = $fontSize * 1.5;

        $img = imagecreate($width, $height);
        $white = imagecolorallocate($img, 0xff, 0xff, 0xff);
        imagefill($img, 0, 0, $white);
        $fonts = FileHelper::listFiles(PATH_DATA . DS . 'fonts', function ($file) {
            return StringHelper::endsWith($file, true, '.ttf');
        });
        $fontsCount = count($fonts);
        for ($i = 0; $i < $length; $i++) {
            $color = $this->randColor($img);
            $angle = rand(-30, 30);
            $x = $fontSize * $i * .8;
            $y = rand(0, 10) + $fontSize;
            $font = $fonts[rand(0, $fontsCount - 1)];
            imagettftext($img, $fontSize, $angle, $x, $y, $color, $font, $text{$i});
        }
        for ($i = 0; $i < 10; $i++) {
            $x1 = rand(0, $width);
            $x2 = rand(0, $width);
            $y1 = rand(0, $height);
            $y2 = rand(0, $height);
            $color = $this->randColor($img);
            imageline($img, $x1, $y1, $x2, $y2, $color);
        }
        $dotCount = $height * $width * .1;
        for ($i = 0; $i < $dotCount; $i++) {
            $x1 = rand(0, $width);
            $y1 = rand(0, $height);
            $color = $this->randColor($img);
            imageline($img, $x1, $y1, $x1, $y1, $color);
        }
        $this->_setSession(self::CAPTCHA_SESSION_KEY, $text);
        $format = strtolower($this->getRouter()->getFormat());
        if (!in_array($format, ['png', 'jpg', 'jpeg', 'bmp'])) {
            $format = 'png';
        } elseif ($format === 'jpg') {
            $format = 'jpeg';
        }
        return $this->_image($img, 'image/' . $format);
    }

    private function randColor($img)
    {
        $start = 0;
        $end = 200;
        return imagecolorallocate($img, rand($start, $end), rand($start, $end), rand($start, $end));
    }
}