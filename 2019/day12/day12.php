<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-12
 * Time: 12:44
 */
require_once __DIR__ . "/../helper/FileReader.php";
require_once __DIR__ . '/Moon.php';
require_once __DIR__ . "/Jupiter.php";

$fileReader = new FileReader("day12.txt", false, "\n");
$input = $fileReader->getData();

$jupiter = new Jupiter();
foreach ($input as $position) {
    preg_match('/x=(-?\d+), y=(-?\d+), z=(-?\d+)/', $position, $matches);
    [$fullMatch, $x, $y, $z] = $matches;
    $moon = new Moon($x, $y, $z);
    $jupiter->addMoon($moon);
}

for ($i = 0; $i < 1000; $i++) {
    $jupiter->advanceMoons();
}

echo "Part 1: " . $jupiter->getTotalEnergy();

ini_set('memory_limit', "8000M");

$originalMoons = [];
$jupiterSimulation = new Jupiter();
foreach ($input as $position) {
    preg_match('/x=(-?\d+), y=(-?\d+), z=(-?\d+)/', $position, $matches);
    [$fullMatch, $x, $y, $z] = $matches;
    $moon = new Moon($x, $y, $z);
    $originalMoons[] = new Moon($x, $y, $z);
    $jupiterSimulation->addMoon($moon);
}

$match = false;
$steps = 0;
$xSteps = null;
$ySteps = null;
$zSteps = null;
while (!$match) {
    $jupiterSimulation->advanceMoons();
    $steps++;
    $xCount = 0;
    $yCount = 0;
    $zCount = 0;
    for ($i = 0; $i < count($originalMoons); $i++) {
        if ($jupiterSimulation->moons[$i]->xPosition === $originalMoons[$i]->xPosition
            && $jupiterSimulation->moons[$i]->xVelocity === $originalMoons[$i]->xVelocity
            && !isset($xSteps)
        ) {
            $xCount++;
        }
        if (($jupiterSimulation->moons[$i]->yPosition === $originalMoons[$i]->yPosition)
            && ($jupiterSimulation->moons[$i]->yVelocity === $originalMoons[$i]->yVelocity)
            && (!isset($ySteps))
        ) {
            $yCount++;
        }
        if (($jupiterSimulation->moons[$i]->zPosition === $originalMoons[$i]->zPosition)
            && ($jupiterSimulation->moons[$i]->zVelocity === $originalMoons[$i]->zVelocity)
            && !isset($zSteps)
        ) {
            $zCount++;
        }
    }
    if ($xCount === count($originalMoons)) {
        $xSteps = $steps;
    }
    if ($yCount === count($originalMoons)) {
        $ySteps = $steps;
    }
    if ($zCount === count($originalMoons)) {
        $zSteps = $steps;
    }
    $match = (isset($xSteps) && isset($ySteps) && isset($zSteps));
}

$primeFactorization = [
    [
        "x" => []
    ],
    [
        "y" => []
    ],
    [
        "z" => []
    ]
];
$mostFactors = [];
$primes = [];
for ($i = 2; $i < max([$xSteps, $ySteps, $zSteps]); $i = gmp_nextprime($i)) {
    $primes[] = (int)$i;
}
foreach ($primes as $prime) {
    while ($xSteps % $prime === 0) {
        $primeFactorization["x"][$prime] = ($primeFactorization["x"][$prime] ?? 0) + 1;
        $xSteps /= $prime;
    }
    while ($ySteps % $prime === 0) {
        $primeFactorization["y"][$prime] = ($primeFactorization["y"][$prime] ?? 0) + 1;
        $ySteps /= $prime;
    }
    while ($zSteps % $prime === 0) {
        $primeFactorization["z"][$prime] = ($primeFactorization["z"][$prime] ?? 0) + 1;
        $zSteps /= $prime;
    }
    $most = 0;
    foreach ($primeFactorization as $factor) {
        if (($factor[$prime] ?? 0) > $most) {
            $most = $factor[$prime];
        }
    }
    if ($most !== 0) {
        $mostFactors[$prime] = $most;
    }
}

$total = 1;
foreach ($mostFactors as $prime => $factor) {
    $total *= ($prime ** $factor);
}

echo "\nPart 2: $total steps until repeat";