<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-04
 * Time: 17:24
 */
require_once __DIR__ . "/PasswordChecker.php";

$file = fopen(__DIR__ . "/../files/day04.txt", "r");
[$start, $end] = fgetcsv($file, 0, "-");

$validPasswords = [];
for ($i = $start; $i <= $end; $i++) {
    $potential = new PasswordChecker($i);
    if ($potential->isValid()) {
        $validPasswords[] = $potential;
    }
}

echo "Part 1: " . count($validPasswords);

$partTwoPasswords = [];
foreach ($validPasswords as $validPassword) {
    if ($validPassword->containsExactlyOneRepeat()) {
        $partTwoPasswords[] = $validPassword;
    }
}

echo "\nPart 2: " . count($partTwoPasswords);