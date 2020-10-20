<?php
require_once "../custom_scripts/gd-text/Box.php";
require_once "../custom_scripts/gd-text/Color.php";
require_once "../custom_scripts/gd-text/HorizontalAlignment.php";
require_once "../custom_scripts/gd-text/TextWrapping.php";
require_once "../custom_scripts/gd-text/VerticalAlignment.php";
require_once "../custom_scripts/gd-text/Struct/Point.php";
require_once "../custom_scripts/gd-text/Struct/Rectangle.php";

use GDText\Box;
use GDText\Color;

$text = $_REQUEST['txt'];


$im = imagecreatetruecolor(250, 28);
$backgroundColor = imagecolorallocate($im, 0, 0, 0);
imagefill($im, 0, 0, imagecolortransparent($im, null));


$box = new Box($im);
$box->setFontFace("../images/fonts/martel.ttf");
$box->setFontSize(24);
$box->setFontColor(new Color(240, 209, 164));
$box->setBox(4, -4, 260, 28);
$box->setTextAlign('left', 'top');

$box->setStrokeColor(new Color(1, 1, 1));
$box->setStrokeSize(0);

$box->draw($text);

header("Content-type: image/png;");
imagepng($im, null, 0, PNG_ALL_FILTERS);
die();
