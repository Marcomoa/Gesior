<?php

require_once "custom_scripts/gd-text/Box.php";
require_once "custom_scripts/gd-text/Color.php";
require_once "custom_scripts/gd-text/HorizontalAlignment.php";
require_once "custom_scripts/gd-text/TextWrapping.php";
require_once "custom_scripts/gd-text/VerticalAlignment.php";
require_once "custom_scripts/gd-text/Struct/Point.php";
require_once "custom_scripts/gd-text/Struct/Rectangle.php";

use GDText\Box;
use GDText\Color;

$text = $_REQUEST['text'];


$im = imagecreatetruecolor(500, 35);
$backgroundColor = imagecolorallocate($im, 0, 0, 0);
imagefill($im, 0, 0, imagecolortransparent($im, null));


$box = new Box($im);
$box->setFontFace("images/fonts/martel.ttf");
$box->setFontSize(25);
$box->setFontColor(new Color(240, 209, 164));
$box->setBox(0, -5, 500, 35);
$box->setTextAlign('left', 'top');

$box->setStrokeColor(new Color(1, 1, 1));
$box->setStrokeSize(1);

$box->draw($text);

header("Content-type: image/png;");
imagepng($im, null, 9, PNG_ALL_FILTERS);
die();
