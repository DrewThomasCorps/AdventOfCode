<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-13
 * Time: 21:44
 */
require_once __DIR__ . "/IntcodeProgramSix.php";
require_once __DIR__ . "/ArcadeGame.php";

$file = fopen(__DIR__ . "/../files/day13.txt", "r");
$input = fgetcsv($file);
$program = new IntcodeProgramSix($input);
$game = new ArcadeGame($program);
$blocks = $game->getTileCount(ArcadeGame::BLOCK_TILE);
echo "Part 1: $blocks";

$input[0] = "2";
$freeProgram = new IntcodeProgramSix($input);
$freeGame = new ArcadeGame($freeProgram);
echo "\nPart 2: {$freeGame->score}";

