<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-10
 * Time: 00:21
 */

class Vector
{
    public float $angleFromTopClockwise;
    public Coordinates $endCoordinates;

    public function __construct(Coordinates $startCoordinates, Coordinates $endCoordinates)
    {
        $y = $startCoordinates->y - $endCoordinates->y; // Rows are in reverse order, the further down the higher the y
        $x = $endCoordinates->x - $startCoordinates->x;
        $this->endCoordinates = $endCoordinates;
        $this->setAngle($x, $y);
    }

    private function setAngle(int $x, int $y)
    {
        if ($x === 0 && $y > 0) {
            $this->angleFromTopClockwise = 0;
        } elseif ($x === 0 && $y < 0) {
            $this->angleFromTopClockwise = 180;
        } else {
            $radians = atan($y / $x);
            $degrees = ($radians * 180) / pi();
            if ($x > 0 && $y > 0) { // First Quadrant
                $this->angleFromTopClockwise = 90 - $degrees;
            } elseif ($x > 0) { // Second Quadrant
                $this->angleFromTopClockwise = 90 + abs($degrees);
            } elseif ($x < 0 && $y < 0) { // Third Quadrant
                $this->angleFromTopClockwise = (90 - $degrees) + 180;
            } else { // Forth Quadrant
                $this->angleFromTopClockwise = 270 + abs($degrees);
            }
        }
    }

}