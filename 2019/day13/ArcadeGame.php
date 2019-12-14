<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-13
 * Time: 21:48
 */

class ArcadeGame
{
    private IntcodeProgramSix $program;
    private array $screen = [[]];
    private array $outputs;

    const EMPTY_TILE = 0;
    const WALL_TILE = 1;
    const BLOCK_TILE = 2;
    const HORIZONTAL_PADDLE_TILE = 3;
    const BALL_TILE = 4;


    public function __construct(IntcodeProgramSix $program)
    {
        $this->program = $program;
        $this->program->defineOutputCallback([$this, "outputCallback"]);
        $this->program->execute();
    }

    public function getTileCount(int $tileId)
    {
        return array_reduce($this->screen, function (?int $carry, $row) use ($tileId) {
            $carry += array_reduce($row, function (?int $carry, $value) use ($tileId) {
                $carry += ($value === $tileId ? 1 : 0);
                return $carry;
            });
            return $carry;
        });
    }

    public function outputCallback(int $output)
    {
        $this->outputs[] = $output;
        if (count($this->outputs) % 3 === 0) {
            $this->addObject();
        }
    }

    private function addObject()
    {
        $column = $this->outputs[count($this->outputs) - 3];
        $row = $this->outputs[count($this->outputs) - 2];
        $object = $this->outputs[count($this->outputs) - 1];
        $this->screen[$column][$row] = $object;
    }


}