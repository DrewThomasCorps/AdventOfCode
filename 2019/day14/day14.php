<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-14
 * Time: 16:03
 */

require_once __DIR__ . "/../helper/FileReader.php";
require_once __DIR__ . "/Atomizer.php";

$fileReader = new FileReader("day14.txt", true, "=");
$input = $fileReader->getData();

$atomizer = new Atomizer($input);
$ore = $atomizer->getElement("ORE");
$ore->amountCreated = 1_000_000_000_000;
$fuel = $atomizer->getElement("FUEL");
$fuel->create();
echo "Part 1: " . $ore->amountUsed . "\n";

$atomizer = new Atomizer($input);
$ore = $atomizer->getElement("ORE");
$ore->amountCreated = 1_000_000_000_000;
$fuel = $atomizer->getElement("FUEL");
$fuel->createMax();
echo "Part 2: " . $fuel->amountCreated;

