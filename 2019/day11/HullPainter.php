<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-11
 * Time: 17:22
 */
require_once __DIR__ . "/IntcodeProgramFive.php";

class HullPainter
{
    private const BLACK = ".";
    public const WHITE = "#";
    private const NOT_PAINTED = " ";

    private const BLACK_INSTRUCTION = 0;
    private const WHITE_INSTRUCTION = 1;
    private const LEFT_INSTRUCTION = 0;
    private const RIGHT_INSTRUCTION = 1;

    private IntcodeProgramFive $program;
    public array $hull = [[self::NOT_PAINTED]];
    private int $currentRow = 0;
    private int $currentColumn = 0;
    private array $outputs = [];
    private string $direction = "UP"; // UP, DOWN, LEFT, RIGHT


    public function __construct(array $programInput)
    {
        $this->program = new IntcodeProgramFive($programInput);
        $this->setupCallbacks();
    }

    public function execute()
    {
        $this->program->execute();
    }

    public function getTotalColored(): int
    {
        return array_reduce($this->hull, function ($carry, $row) {
            $carry += array_reduce($row, function ($carry, $value) {
                $carry += $value === self::NOT_PAINTED ? 0 : 1;
                return $carry;
            });
            return $carry;
        });
    }

    public function displayHull(): void
    {
        ksort($this->hull);
        foreach ($this->hull as $row) {
            ksort($row);
            foreach ($row as $value) {
                echo ($value === self::WHITE ? self::WHITE : " ") . " ";
            }
            echo "\n";
        }
    }

    public function inputCallback(): void
    {
        $input = 0;
        if ($this->hull[$this->currentRow][$this->currentColumn] === self::WHITE) {
            $input = 1;
        }
        $this->program->defineNextInputs([$input]);
    }

    public function outputCallback(int $output): void
    {
        if (count($this->outputs) % 2 === 0) {
            $this->paint($output);
        } else {
            $this->turn($output);
        }
        $this->outputs[] = $output;
    }

    private function setupCallbacks()
    {
        $this->program->defineInputCallback([$this, "inputCallback"]);
        $this->program->defineOutputCallback([$this, "outputCallback"]);
    }

    private function paint(int $output)
    {
        $color = self::BLACK;
        if ($output === self::WHITE_INSTRUCTION) {
            $color = self::WHITE;
        }
        $this->hull[$this->currentRow][$this->currentColumn] = $color;
    }

    private function turn(int $output)
    {
        if (($output === self::LEFT_INSTRUCTION && $this->direction === "UP") || ($output === self::RIGHT_INSTRUCTION && $this->direction === "DOWN")) {
            $this->direction = "LEFT";
        } elseif (($output === self::LEFT_INSTRUCTION && $this->direction === "RIGHT") || ($output === self::RIGHT_INSTRUCTION && $this->direction === "LEFT")) {
            $this->direction = "UP";
        } elseif (($output === self::LEFT_INSTRUCTION && $this->direction === "DOWN") || ($output === self::RIGHT_INSTRUCTION && $this->direction === "UP")) {
            $this->direction = "RIGHT";
        } elseif (($output === self::LEFT_INSTRUCTION && $this->direction === "LEFT") || ($output === self::RIGHT_INSTRUCTION && $this->direction === "RIGHT")) {
            $this->direction = "DOWN";
        }
        $this->move();
    }

    private function move()
    {
        if ($this->direction === "UP") {
            $this->currentRow--;
            if (!isset($this->hull[$this->currentRow][$this->currentColumn])) {
                $this->addRow();
            }
        }
        if ($this->direction === "DOWN") {
            $this->currentRow++;
            if (!isset($this->hull[$this->currentRow][$this->currentColumn])) {
                $this->addRow();
            }
        }
        if ($this->direction === "LEFT") {
            $this->currentColumn--;
            if (!isset($this->hull[$this->currentRow][$this->currentColumn])) {
                $this->addColumn();
            }
        }
        if ($this->direction === "RIGHT") {
            $this->currentColumn++;
            if (!isset($this->hull[$this->currentRow][$this->currentColumn])) {
                $this->addColumn();
            }
        }
    }

    private function addRow()
    {
        $minColumn = min(array_keys($this->hull[0]));
        $maxColumn = max(array_keys($this->hull[0]));
        for ($i = $minColumn; $i <= $maxColumn; $i++) {
            $this->hull[$this->currentRow][$i] = self::NOT_PAINTED;
        }
    }

    private function addColumn()
    {
        $minRow = min(array_keys($this->hull));
        $maxRow = max(array_keys($this->hull));
        for ($i = $minRow; $i <= $maxRow; $i++) {
            $this->hull[$i][$this->currentColumn] = self::NOT_PAINTED;
        }
    }


}