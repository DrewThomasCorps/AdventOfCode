<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 12/16/19
 * Time: 22:57
 */

require_once __DIR__ . "/Map.php";

class RepairDroid
{
    public const NORTH = 1;
    public const SOUTH = 2;
    public const WEST = 3;
    public const EAST = 4;

    private const WALL = 0;
    private const MOVE = 1;
    private const OXYGEN = 2;

    private IntcodeProgramSeven $program;
    private Map $map;
    private array $input = [];

    public function __construct(array $program, Map $map)
    {
        $this->program = new IntcodeProgramSeven($program);
        $this->map = $map;
        $this->program->defineInputCallback([$this, "inputCallback"]);
        $this->program->defineOutputCallback([$this, "outputCallback"]);
        $this->program->execute();
    }

    public function inputCallback()
    {
        if (count($this->input) === 0) {
            $square = $this->map->getCurrentDroidSquare();
            $this->input = $square->getDirectionsToTravel();
            $this->program->defineNextInputs($this->input);
        }
    }

    public function outputCallback(int $output)
    {
        $direction = array_shift($this->input);
        if ($output === self::MOVE) {
            switch ($direction) {
                case self::NORTH:
                    $this->map->moveNorth();
                    break;
                case self::EAST:
                    $this->map->moveEast();
                    break;
                case self::WEST:
                    $this->map->moveWest();
                    break;
                case self::SOUTH:
                    $this->map->moveSouth();
                    break;
            }
        } elseif ($output === self::WALL) {
            switch ($direction) {
                case self::NORTH:
                    $this->map->wallNorth();
                    break;
                case self::EAST:
                    $this->map->wallEast();
                    break;
                case self::WEST:
                    $this->map->wallWest();
                    break;
                case self::SOUTH:
                    $this->map->wallSouth();
                    break;
            }
        } elseif ($output === self::OXYGEN) {
            switch ($direction) {
                case self::NORTH:
                    $this->map->tankNorth();
                    break;
                case self::EAST:
                    $this->map->tankEast();
                    break;
                case self::WEST:
                    $this->map->tankWest();
                    break;
                case self::SOUTH:
                    $this->map->tankSouth();
                    break;
            }
            $directionsBack = $this->map->getCurrentDroidSquare()->getDirectionsBack();
            echo "Part 1: " . count($directionsBack);
        }
    }
}