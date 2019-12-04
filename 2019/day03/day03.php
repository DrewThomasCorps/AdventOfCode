<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-03
 * Time: 20:01
 */
require_once __DIR__ . "/../helper/FileReader.php";
require_once __DIR__ . "/Circuit.php";

$fileReader = new FileReader("day03.txt", true);
$input = $fileReader->getData();

$circuit = new Circuit($input);
$closestIntersection = $circuit->getClosestIntersection();
$fewestSteps = $circuit->getFewestStepsAtIntersections();

echo "Day 1: Manhattan Distance: {$closestIntersection->getManhattanDistance()}";
echo "\nDay 2: Fewest Steps: $fewestSteps";