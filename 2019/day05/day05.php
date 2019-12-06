<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-05
 * Time: 16:52
 */
require_once __DIR__ . "/../helper/FileReader.php";
require_once __DIR__ . "/IntcodeProgramTwo.php";
$fileReader = new FileReader("day05.txt", true);
$input = $fileReader->getData()[0];

$program = new IntcodeProgramTwo($input);
$program->execute();

