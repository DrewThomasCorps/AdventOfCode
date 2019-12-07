<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-06
 * Time: 22:30
 */

require_once __DIR__ . "/../helper/FileReader.php";
require_once __DIR__ . "/SolarSystem.php";

$fileReader = new FileReader("day06.txt", true, ")");
$orbits = $fileReader->getData();

$solarSystem = new SolarSystem();
$solarSystem->addOrbits($orbits);
echo "Part 1: " . $solarSystem->getTotalOrbits();