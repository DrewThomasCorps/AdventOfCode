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

ini_set('memory_limit','1000M');

$realInput = [];
for ($i = 0; $i < 10000; $i++) {
    array_push($realInput, ...$input);
}
$offset = 0;
for ($i = 0; $i < 7; $i++) {
    $offset += $input[$i] * pow(10, 6 - $i);
}
if ($offset > count($realInput) / 2) {
    $signal = array_slice($realInput, $offset);
    for ($i = 0; $i < 100; $i++) {
        $nextSignal = [];
        $total = array_sum($signal);
        for ($position = 0; $position < count($signal); $position++) {
            $nextSignal[] = ($total % 10);
            $total -= $signal[$position];
        }
        $signal = $nextSignal;
    }
    echo "\nPart 2: ";
    for ($i = 0; $i < 8; $i++) {
        echo $signal[$i];
    }
}

