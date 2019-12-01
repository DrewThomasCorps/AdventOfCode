<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-11-30
 * Time: 23:51
 */

require_once __DIR__ . "/../helper/FileReader.php";
$fileReader = new FileReader("day01.txt");
$data = $fileReader->getData();

function part1($data): int
{
    $totalFuel = 0;
    foreach ($data as $mass) {
        $fuel = getFuel($mass);
        $totalFuel += $fuel;
    }
    return $totalFuel;
}

function part2($data): int
{
    $totalFuel = 0;
    foreach ($data as $mass) {
        $fuel = getFuel($mass);
        while ($fuel >= 0) {
            $totalFuel += $fuel;
            $fuel = getFuel($fuel);
        }
    }
    return $totalFuel;
}

function getFuel($mass): int
{
    return floor($mass / 3) - 2;
}

echo "Part 1: " . part1($data) . "\n";
echo "Part 2: " . part2($data);