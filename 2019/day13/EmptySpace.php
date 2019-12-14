<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-14
 * Time: 02:17
 */

class EmptySpace extends GameObject
{
    public function __construct($row = 0, $column = 0)
    {
        parent::__construct($row, $column);
    }
}