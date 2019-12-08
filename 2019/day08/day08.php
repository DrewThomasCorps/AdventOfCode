<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-08
 * Time: 17:08
 */

require_once __DIR__ . "/Image.php";

$input = file_get_contents(__DIR__ . "/../files/day08.txt");
$image = new Image(6, 25, $input);
$layer = $image->getLayerWithFewestOfDigit(0);
$ones = $layer->getPixelDigitCount(1);
$twos = $layer->getPixelDigitCount(2);

echo "Part 1: " . ($ones * $twos) . "\n";
echo "Part 2: \n";
$image->print();