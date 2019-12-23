<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 12/16/19
 * Time: 22:53
 */
require_once __DIR__ . "/IntcodeProgramSeven.php";
require_once __DIR__ . "/RepairDroid.php";
require_once __DIR__ . "/Square.php";
require_once __DIR__ . '/Map.php';

$file = fopen(__DIR__ . "/../files/day15.txt", "r");
$input = fgetcsv($file);

$map = new Map();
try {
    $droid = new RepairDroid($input, $map);
} catch (Exception $exception) {
    echo "\nPart 2: " . $map->timeUntilOxygenFills();
    exit();
}

