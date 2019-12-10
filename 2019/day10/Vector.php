<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-10
 * Time: 00:21
 */

class Vector
{
    public float $direction;

    public function __construct(Coordinates $startCoordinates, Coordinates $endCoordinates)
    {
        $y = $endCoordinates->y - $startCoordinates->y;
        $x = $endCoordinates->x - $startCoordinates->x;
        $this->direction = atan2($y, $x);
    }

}