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
    private array $screen = [[self::EMPTY_TILE]];
    private array $outputs;
    public int $score;

    const EMPTY_TILE = 0;
    const WALL_TILE = 1;
    const BLOCK_TILE = 2;
    const HORIZONTAL_PADDLE_TILE = 3;
    const BALL_TILE = 4;


    public function __construct(IntcodeProgramSix $program)
    {
        $this->program = $program;
        $this->program->defineOutputCallback([$this, "outputCallback"]);
        $this->program->defineInputCallback([$this, "inputCallback"]);
        $this->program->execute();
    }

    public function getTileCount(int $tileId)
    {
        return array_reduce($this->screen, function (?int $carry, $column) use ($tileId) {
            $carry += array_reduce($column, function (?int $carry, $value) use ($tileId) {
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
            $this->evaluateOutput();
        }
    }

    public function inputCallback()
    {
        $this->printScreen();
    }

    private function printScreen()
    {
        echo "\n\n";
        foreach ($this->screen as $row) {
            foreach ($row as $column) {
                switch ($column) {
                    case self::EMPTY_TILE:
                        echo "   ";
                        break;
                    case self::WALL_TILE:
                        echo "///";
                        break;
                    case self::BLOCK_TILE:
                        echo "[ ]";
                        break;
                    case self::HORIZONTAL_PADDLE_TILE:
                        echo "___";
                        break;
                    case self::BALL_TILE:
                        echo " O ";
                        break;
                }
                echo " ";
            }
            echo "\n";
        }
    }

    private function evaluateOutput()
    {
        if ($this->outputs[count($this->outputs) - 3] === -1 && $this->outputs[count($this->outputs) - 2] === 0) {
            $this->setScore();
        } else {
            $this->addObject();
        }
    }

    private function setScore()
    {
        $this->score = $this->outputs[count($this->outputs) - 1];
        echo "\nYour score: {$this->score}";
    }

    private function addObject()
    {
        $column = $this->outputs[count($this->outputs) - 3];
        $this->addColumns($column);
        $row = $this->outputs[count($this->outputs) - 2];
        $this->addRows($row);
        $object = $this->outputs[count($this->outputs) - 1];
        $this->screen[$row][$column] = $object;
    }

    private function addRows(int $rowsMax)
    {
        for ($row = 0; $row <= $rowsMax; $row++) {
            if (!isset($this->screen[$row])) {
                $this->screen[$row] = [];
                for ($row = 0; $row < count($this->screen[$row - 1] ?? [1]); $row++) {
                    $this->screen[$row][] = [self::EMPTY_TILE];
                }
            }
        }
    }

    private function addColumns(int $columnsMax)
    {

        foreach ($this->screen as $row) {
            while (count($row) < $columnsMax) {
                $row[] = self::EMPTY_TILE;
            }
        }
    }


}