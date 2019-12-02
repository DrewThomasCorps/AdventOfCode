<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-02
 * Time: 16:01
 */

require_once __DIR__ . '/IntcodeProgram.php';

$file = fopen(__DIR__ . "/../files/day02.txt", "r");
$data = fgetcsv($file);

$intcodeProgram = new IntcodeProgram($data, 12, 2);
echo "Part 1:" . $intcodeProgram->execute() . "\n";

for ($noun = 0; $noun <= 99; $noun++) {
    for ($verb = 0; $verb <= 99; $verb++) {
        $intcodeProgram = new IntcodeProgram($data, $noun, $verb);
        if ($intcodeProgram->execute() === 19690720) {
            echo "Part 2: | noun: $noun | verb: $verb | answer: " . (100 * $noun + $verb);
        }
    }
}
