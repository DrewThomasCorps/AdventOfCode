<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-14
 * Time: 01:43
 */

class Ball extends GameObject
{
    public int $rowDirection;
    public int $columnDirection;

    public function __construct(int $row, int $column)
    {
        parent::__construct($row, $column);
    }


}