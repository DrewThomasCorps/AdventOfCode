<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-09
 * Time: 23:58
 */
require_once __DIR__ . "/../helper/FileReader.php";
require_once __DIR__ . "/Coordinates.php";
require_once __DIR__ . "/AsteroidBelt.php";
$fileReader = new FileReader("day10.txt", false, "\n");
$asteroidRows = $fileReader->getData();
$asteroidBelt = new AsteroidBelt($asteroidRows);
$chosenAsteroidCoordinates = $asteroidBelt->getCoordinatesOfAsteroidWithMostVisibleAsteroids();
echo "Part 1: " . $asteroidBelt->getVisibleAsteroidsFromCoordinates($chosenAsteroidCoordinates);
$destroyedAsteroids = $asteroidBelt->destroyAsteroidsFromCoordinates($chosenAsteroidCoordinates);
var_dump($destroyedAsteroids);
$twoHundredthAsteroidCoordinates = $destroyedAsteroids[199];
echo "\nPart 2: " . (($twoHundredthAsteroidCoordinates->x * 100) + $twoHundredthAsteroidCoordinates->y);