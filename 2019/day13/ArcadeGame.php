<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-13
 * Time: 21:48
 */

require_once __DIR__ . "/Screen.php";

require_once __DIR__ . "/Ball.php";
require_once __DIR__ . "/Block.php";
require_once __DIR__ . "/EmptySpace.php";
require_once __DIR__ . "/Paddle.php";
require_once __DIR__ . "/Wall.php";

class ArcadeGame
{
    private IntcodeProgramSix $program;
    public Screen $screen;
    private array $outputs;
    private Ball $ball;
    private Paddle $paddle;

    public bool $humanPlayer = false;

    const EMPTY_TILE = 0;
    const WALL_TILE = 1;
    const BLOCK_TILE = 2;
    const HORIZONTAL_PADDLE_TILE = 3;
    const BALL_TILE = 4;


    public function __construct(IntcodeProgramSix $program)
    {
        $this->screen = new Screen();
        $this->program = $program;
        $this->program->defineOutputCallback([$this, "outputCallback"]);
        $this->program->defineInputCallback([$this, "inputCallback"]);
        $this->program->execute();
    }

    public function getTileCount(string $tileType)
    {
        return array_reduce($this->screen->screen, function (?int $carry, $column) use ($tileType) {
            $carry += array_reduce($column, function (?int $carry, $value) use ($tileType) {
                $carry += (get_class($value) === $tileType ? 1 : 0);
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
        if ($this->humanPlayer) {
            $this->screen->render();
        } else {
            $move = $this->ball->column <=> $this->paddle->column;
            $this->program->defineNextInputs([$move]);
        }
    }

    private function evaluateOutput()
    {
        if ($this->outputs[count($this->outputs) - 3] === -1 && $this->outputs[count($this->outputs) - 2] === 0) {
            $this->screen->score = $this->outputs[count($this->outputs) - 1];
        } else {
            $object = $this->outputs[2];
            if ($object === self::BALL_TILE) {
                $this->ball = new Ball($this->outputs[1], $this->outputs[0]);
            }
            if ($object === self::HORIZONTAL_PADDLE_TILE) {
                $this->paddle = new Paddle($this->outputs[1], $this->outputs[0]);
            }
            $this->screen->addObject($this->outputs);
        }
        $this->outputs = [];
    }


}