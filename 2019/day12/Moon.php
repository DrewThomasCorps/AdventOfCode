<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-12
 * Time: 13:11
 */

class Moon
{
    public int $xPosition;
    public int $yPosition;
    public int $zPosition;

    public int $xVelocity = 0;
    public int $yVelocity = 0;
    public int $zVelocity = 0;

    public function __construct(int $x, int $y, int $z)
    {
        $this->xPosition = $x;
        $this->yPosition = $y;
        $this->zPosition = $z;
    }

    public function advance(): void
    {
        $this->xPosition += $this->xVelocity;
        $this->yPosition += $this->yVelocity;
        $this->zPosition += $this->zVelocity;
    }

    public function getTotalEnergy(): int
    {
        return $this->getPotentialEnergy() * $this->getKineticEnergy();
    }

    public function getPotentialEnergy(): int
    {
        return abs($this->xPosition) + abs($this->yPosition) + abs($this->zPosition);
    }

    public function getKineticEnergy(): int
    {
        return abs($this->xVelocity) + abs($this->yVelocity) + abs($this->zVelocity);
    }

    public function __toString(): string
    {
        return "xPos: {$this->xPosition}, yPos: {$this->yPosition}, zPos: {$this->zPosition}, " .
            "x{$this->xVelocity}y{$this->yVelocity}z{$this->zVelocity}";
    }
}