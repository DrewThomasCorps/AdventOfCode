<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-11
 * Time: 12:59
 */

require_once __DIR__ . "/HullPainter.php";

$file = fopen(__DIR__ . "/../files/day11.txt", "r");
$input = fgetcsv($file);

$painter = new HullPainter($input);
$painter->execute();
echo "Part 1: " . $painter->getTotalColored();

$secondPainter = new HullPainter($input);
$secondPainter->hull[0][0] = $secondPainter::WHITE;
$secondPainter->execute();
echo "\nPart 2: \n";
$secondPainter->displayHull();


