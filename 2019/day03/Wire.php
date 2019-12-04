<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-03
 * Time: 20:14
 */

require_once __DIR__ . "/Point.php";

class Wire
{
    public array $points = [];
    private Point $currentPoint;
    private int $steps = 0;

    public function __construct($path)
    {
        $this->currentPoint = new Point(0, 0, 0);
        $this->evaluatePath($path);
    }

    public function getPointWithFewestSteps(Point $point){
        $index = array_search($point->__toString(), $this->points);
        $ret = $this->points[$index];
        return $ret;
    }

    private function evaluatePath($path)
    {
        foreach ($path as $instruction) {
            $this->executeInstruction($instruction);
        }
    }

    private function executeInstruction($instruction)
    {
        $distance = substr($instruction, 1);
        switch (substr($instruction, 0, 1)) {
            case "R":
                for ($i = 0; $i < $distance; $i++) {
                    $this->currentPoint = new Point($this->currentPoint->x + 1, $this->currentPoint->y, ++$this->steps);
                    $this->points[] = $this->currentPoint;
                }
                break;
            case "L":
                for ($i = 0; $i < $distance; $i++) {
                    $this->currentPoint = new Point($this->currentPoint->x - 1, $this->currentPoint->y, ++$this->steps);
                    $this->points[] = $this->currentPoint;
                }
                break;
            case "U":
                for ($i = 0; $i < $distance; $i++) {
                    $this->currentPoint = new Point($this->currentPoint->x, $this->currentPoint->y + 1, ++$this->steps);
                    $this->points[] = $this->currentPoint;
                }
                break;
            case "D":
                for ($i = 0; $i < $distance; $i++) {
                    $this->currentPoint = new Point($this->currentPoint->x, $this->currentPoint->y - 1, ++$this->steps);
                    $this->points[] = $this->currentPoint;
                }
                break;
        }
    }
}