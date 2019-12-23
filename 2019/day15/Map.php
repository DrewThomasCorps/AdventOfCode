<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 12/16/19
 * Time: 22:54
 */

class Map
{
    private array $tiles = [[]];
    private int $droidRow = 0;
    private int $droidColumn = 0;

    public function __construct()
    {
        $this->tiles[0][0] = new Square();
    }

    public function display()
    {
        $rowStart = min(array_keys($this->tiles));
        $rowEnd = max(array_keys($this->tiles));
        $columnStart = min(array_keys($this->tiles[0]));
        $columnEnd = max(array_keys($this->tiles[0]));
        for ($row = $rowStart; $row <= $rowEnd; $row++) {
            for ($column = $columnStart; $column <= $columnEnd; $column++) {
                if ($row === 0 && $column === 0) {
                    echo " + ";
                } elseif ($row === $this->droidRow && $column === $this->droidColumn) {
                    echo " ^ ";
                } elseif ($this->tiles[$row][$column] instanceof Square && $this->tiles[$row][$column]->isOxygen) {
                    echo " O ";
                } elseif ($this->tiles[$row][$column] instanceof Square && $this->tiles[$row][$column]->isWall) {
                    echo " X ";
                } elseif ($this->tiles[$row][$column] instanceof Square && $this->tiles[$row][$column]->deadEnd) {
                    echo "   ";
                } elseif ($this->tiles[$row][$column] instanceof Square) {
                    echo "   ";
                } elseif ($this->tiles[$row][$column] === 0) {
                    echo "---";
                }
            }
            echo "\n";
        }
    }

    public function getCurrentDroidSquare(): Square
    {
        return $this->tiles[$this->droidRow][$this->droidColumn];
    }

    public function moveNorth(): void
    {
        if (!isset($this->tiles[--$this->droidRow])) {
            $this->addRow();
        }
        $this->addSquare();
    }

    public function moveEast(): void
    {
        if (!isset($this->tiles[$this->droidRow][++$this->droidColumn])) {
            $this->addColumn();
        }
        $this->addSquare();
    }

    public function moveSouth(): void
    {
        if (!isset($this->tiles[++$this->droidRow])) {
            $this->addRow();
        }
        $this->addSquare();
    }

    public function moveWest(): void
    {
        if (!isset($this->tiles[$this->droidRow][--$this->droidColumn])) {
            $this->addColumn();
        }
        $this->addSquare();
    }

    public function wallNorth(): void
    {
        $this->moveNorth();
        $currentSquare = $this->getCurrentDroidSquare();
        $currentSquare->isWall = true;
        $this->moveSouth();
    }

    public function wallEast(): void
    {
        $this->moveEast();
        $currentSquare = $this->getCurrentDroidSquare();
        $currentSquare->isWall = true;
        $this->moveWest();
    }

    public function wallSouth(): void
    {
        $this->moveSouth();
        $currentSquare = $this->getCurrentDroidSquare();
        $currentSquare->isWall = true;
        $this->moveNorth();
    }

    public function wallWest(): void
    {
        $this->moveWest();
        $currentSquare = $this->getCurrentDroidSquare();
        $currentSquare->isWall = true;
        $this->moveEast();
    }

    public function tankNorth(): void
    {
        $this->moveNorth();
        $currentSquare = $this->getCurrentDroidSquare();
        $currentSquare->isOxygen = true;
    }

    public function tankEast(): void
    {
        $this->moveEast();
        $currentSquare = $this->getCurrentDroidSquare();
        $currentSquare->isOxygen = true;
    }

    public function tankSouth(): void
    {
        $this->moveSouth();
        $currentSquare = $this->getCurrentDroidSquare();
        $currentSquare->isOxygen = true;
    }

    public function tankWest(): void
    {
        $this->moveWest();
        $currentSquare = $this->getCurrentDroidSquare();
        $currentSquare->isOxygen = true;
    }

    public function addSquare(): Square
    {
        $square = $this->tiles[$this->droidRow][$this->droidColumn];
        if (!$square instanceof Square) {
            $square = new Square();
            $this->tiles[$this->droidRow][$this->droidColumn] = $square;
        }
        $northTile = $this->tiles[$this->droidRow - 1][$this->droidColumn] ?? null;
        if ($northTile instanceof Square) {
            $square->squareNorth = $northTile;
            $northTile->squareSouth = $square;
        }
        $southTile = $this->tiles[$this->droidRow + 1][$this->droidColumn] ?? null;
        if ($southTile instanceof Square) {
            $square->squareSouth = $southTile;
            $southTile->squareNorth = $square;
        }
        $eastTile = $this->tiles[$this->droidRow][$this->droidColumn + 1] ?? null;
        if ($eastTile instanceof Square) {
            $square->squareEast = $eastTile;
            $eastTile->squareWest = $square;
        }
        $westTile = $this->tiles[$this->droidRow][$this->droidColumn - 1] ?? null;
        if ($westTile instanceof Square) {
            $square->squareWest = $westTile;
            $westTile->squareEast = $square;
        }
        $square->setDeadEnd();
        return $square;
    }

    private function addRow()
    {
        $columnStart = min(array_keys($this->tiles[0]));
        $columnEnd = max(array_keys($this->tiles[0]));
        for ($i = $columnStart; $i <= $columnEnd; $i++) {
            $this->tiles[$this->droidRow][$i] = 0;
        }
    }

    private function addColumn()
    {
        $rowStart = min(array_keys($this->tiles));
        $rowEnd = max(array_keys($this->tiles));
        for ($i = $rowStart; $i <= $rowEnd; $i++) {
            $this->tiles[$i][$this->droidColumn] = 0;
        }
    }

    public function timeUntilOxygenFills(): int
    {
        $count = 0;
        while (!$this->hasAllOxygen()) {
            $count++;
            $this->spreadOxygen();
        }
        return $count;
    }

    private function hasAllOxygen(): bool
    {
        for ($row = min(array_keys($this->tiles)); $row <= max(array_keys($this->tiles)); $row++) {
            for ($column = min(array_keys($this->tiles[0])); $column <= max(array_keys($this->tiles[0])); $column++) {
                if ($this->tiles[$row][$column] instanceof Square && (!$this->tiles[$row][$column]->isOxygen && !$this->tiles[$row][$column]->isWall)) {
                    return false;
                }
            }
        }
        return true;
    }

    private function spreadOxygen()
    {
        array_walk($this->tiles, function (array $row) {
            array_walk($row, function ($square) {
                if ($square instanceof Square) {
                    $square->receivedOxygen = false;
                }
            });
        });
        array_walk($this->tiles, function (array $row) {
            array_walk($row, function ($square) {
                if ($square instanceof Square) {
                    $square->spreadOxygen();
                }
            });
        });
    }
}