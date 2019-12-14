<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-14
 * Time: 01:51
 */

class GameObject
{
    public int $row;
    public int $column;

    public function __construct($row, $column)
    {
        $this->row = $row;
        $this->column = $column;
    }

}