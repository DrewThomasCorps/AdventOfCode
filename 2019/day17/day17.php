<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 12/28/19
 * Time: 11:46
 */
require_once __DIR__ . "/IntcodeProgramEight.php";

$file = fopen(__DIR__ . "/../files/day17.txt", "r");
$input = fgetcsv($file);

const SCAFFOLD = 35;
const SPACE = 46;
const NEW_ROW = 10;

$program = new IntcodeProgramEight($input);
$map = [[]];
$program->defineOutputCallback(function (int $output) use (&$map) {
    if ($output === NEW_ROW) {
        $map[] = [];
    } else {
        $map[array_key_last($map)][] = $output;
    }
});
$program->execute();
$total = 0;
for ($row = 0; $row < count($map); $row++) {
    for ($column = 0; $column < count($map[$row]); $column++) {
        echo chr($map[$row][$column]);
        if ($map[$row][$column] === SCAFFOLD) {
            if (isset($map[$row - 1][$column]) && isset($map[$row + 1][$column]) && isset($map[$row][$column + 1]) && isset($map[$row][$column - 1]))
            {
                if ($map[$row - 1][$column] === SCAFFOLD && $map[$row + 1][$column] === SCAFFOLD && $map[$row][$column + 1] === SCAFFOLD && $map[$row][$column - 1] === SCAFFOLD)
                {
                    $total += $row * $column;
                }
            }
        }
    }
    echo "\n";
}
echo "Part 1: $total\n";