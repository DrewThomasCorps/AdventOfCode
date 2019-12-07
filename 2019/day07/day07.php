<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-07
 * Time: 10:58
 */
require_once __DIR__ . "/../helper/FileReader.php";
require_once __DIR__ . "/IntcodeProgramThree.php";
require_once __DIR__ . "/Combinations.php";

$fileReader = new FileReader("day07.txt", true);
$input = $fileReader->getData()[0];

$array = [0, 1, 2, 3, 4];
$combinations = new Combinations($array);

$maxSignal = 0;
$output = 0;
foreach ($combinations->combinations as $combination) {
    foreach ($combination as $amplifier) {
        $program = new IntcodeProgramThree($input);
        $program->defineNextInputs([$amplifier, $output]);
        $program->execute();
        $output = $program->outputs[0];
    }
    if ($program->outputs[0] > $maxSignal) {
        $maxSignal = $program->outputs[0];
    }
    $output = 0;
}

echo "Part 1: " . $maxSignal;
$array = [9, 8, 7, 6, 5];
$combinations = new Combinations($array);

$maxSignal = 0;
foreach ($combinations->combinations as $combination) {
    $amplifiers = [];
    for ($i = 0; $i < count($combination); $i++) {
        $amplifiers[] = new IntcodeProgramThree($input);
        $amplifiers[$i]->defineNextInputs([$combination[$i]]);
    }
    $amplifiers[0]->defineNextInputs([0]);
    for ($i = 0; $i < count($amplifiers); $i++) {
        $inputAmplifier = $amplifiers[$i + 1] ?? $amplifiers[0];
        $currentAmplifier = $amplifiers[$i];
        $currentAmplifier->defineOutputCallback(
            function  ($output) use ($inputAmplifier, $currentAmplifier) {
                $inputAmplifier->defineNextInputs([$output]);
                if (!$inputAmplifier->running) {
                    $inputAmplifier->execute();
                }
                if (count($currentAmplifier->inputs) === 0) {
                    $currentAmplifier->running = false;
                }
            }
        );
    }
    $amplifiers[0]->execute();
    $output = end($amplifiers[4]->outputs);
    if ($output > $maxSignal) {
        $maxSignal = $output;
    }
}

echo "\nPart 2: " . $maxSignal;

