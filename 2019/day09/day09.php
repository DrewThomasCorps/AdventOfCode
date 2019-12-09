<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-08
 * Time: 18:37
 */
require_once __DIR__ . "/IntcodeProgramFour.php";

$file = fopen(__DIR__ . "/../files/day09.txt", "r");
$input = fgetcsv($file);

$program = new IntcodeProgramFour($input);
$program->defineNextInputs([1]);
$program->execute();
echo "Part 1: " . $program->outputs[0];

$program = new IntcodeProgramFour($input);
$program->defineNextInputs([2]);
$program->execute();
echo "\nPart 2: " . $program->outputs[0];

