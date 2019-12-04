<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-03
 * Time: 20:23
 */

class Point
{
    public int $x;
    public int $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getManhattanDistance($fromX = 0, $fromY = 0)
    {
        return abs($this->x - $fromX) + abs($this->y - $fromY);
    }


}