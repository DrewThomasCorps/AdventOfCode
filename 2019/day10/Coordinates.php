<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-10
 * Time: 12:47
 */

class Coordinates
{
    public int $x;
    public int $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }


}