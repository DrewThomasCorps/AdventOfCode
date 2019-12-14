<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-14
 * Time: 02:41
 */

class Screen
{
    public array $screen = [[]];
    public int $score = 0;

    public function render()
    {
    $string = "";
        foreach ($this->screen as $row) {
            foreach ($row as $column) {
                switch (get_class($column)) {
                    case EmptySpace::class:
                        $string .= "   ";
                        break;
                    case Wall::class:
                        $string .= "///";
                        break;
                    case Block::class:
                        $string .= "[+]";
                        break;
                    case Paddle::class:
                        $string .= "___";
                        break;
                    case Ball::class:
                        $string .= " O ";
                        break;
                }
                $string .= " ";
            }
            $string .= "\n";
        }
        $string .= "Your Score: {$this->score}\n\n\n";
        echo $string;
    }

    public function addObject($outputs)
    {
        [$column, $row, $object] = $outputs;
        $this->addColumns($column);
        $this->addRows($row);
        if ($object === ArcadeGame::BALL_TILE) {
            $this->screen[$row][$column] = new Ball($row, $column);
        } elseif ($object === ArcadeGame::BLOCK_TILE) {
            $this->screen[$row][$column] = new Block($row, $column);
        } elseif ($object === ArcadeGame::HORIZONTAL_PADDLE_TILE) {
            $this->screen[$row][$column] = new Paddle($row, $column);
        } elseif ($object === ArcadeGame::WALL_TILE) {
            $this->screen[$row][$column] = new Wall($row, $column);
        } elseif ($object === ArcadeGame::EMPTY_TILE) {
            $this->screen[$row][$column] = new EmptySpace($row, $column);
        }
    }

    private function addRows(int $rowsMax)
    {
        for ($row = 0; $row <= $rowsMax; $row++) {
            if (!isset($this->screen[$row])) {
                $this->screen[$row] = [];
                for ($row = 0; $row < count($this->screen[$row - 1] ?? []); $row++) {
                    $this->screen[$row][] = new EmptySpace();
                }
            }
        }
    }

    private function addColumns(int $columnsMax)
    {
        foreach ($this->screen as $row) {
            while (count($row) < $columnsMax) {
                $row[] = new EmptySpace();
            }
        }
    }

}