<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 12/28/19
 * Time: 08:30
 */

require_once __DIR__ . "/PhasePattern.php";
require_once __DIR__ . "/Signal.php";

$input = file_get_contents(__DIR__ . "/../files/day16.txt");
$input = str_split($input);

$pattern = new PhasePattern([0, 1, 0, -1], count($input));
$signal = new Signal($input, $pattern);
for ($i = 0; $i < 100; $i++) {
    $signal = $signal->getOutputSignal();
}
$finalSignal = $signal->inputSignal;
echo "Part 1: ";
for ($i = 0; $i < 8; $i++) {
    echo $finalSignal[$i];
}

$realInput = [];
for ($i = 0; $i < 10000; $i++) {
    array_push($realInput, ...$input);
}
echo "HERE";
$pattern = new PhasePattern([0, 1, 0, -1], count($realInput));
$signal = new Signal($realInput, $pattern);
echo "\nTest";
for ($i = 0; $i < 100; $i++) {
    $signal = $signal->getOutputSignal();
    echo "\n$i";
}
$finalSignal = $signal->inputSignal;
echo "Part 1: ";
for ($i = 0; $i < 8; $i++) {
    echo $finalSignal[$i];
}

