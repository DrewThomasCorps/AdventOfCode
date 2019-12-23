<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 12/16/19
 * Time: 22:54
 */

class Square
{
    public bool $isOxygen = false;
    public bool $isWall = false;
    public bool $deadEnd = false;
    public bool $explored = false;
    public bool $receivedOxygen = false;

    public ?Square $squareNorth;
    public ?Square $squareEast;
    public ?Square $squareSouth;
    public ?Square $squareWest;

    /** @noinspection PhpUnhandledExceptionInspection */
    public function getDirectionsToTravel(): array
    {
        if ($this->hasUnexploredDirection()) {
            $direction = $this->getFirstUnexploredDirection();
            return [$direction];
        } else {
            return $this->getDirectionsToNearestSquareWithUnexploredDirection();
        }
    }

    public function getDirectionsBack(array $directions = []): array
    {
        $this->explored = true;
        if (isset($this->squareNorth) && !$this->squareNorth->explored && !$this->squareNorth->isWall && !$this->squareNorth->deadEnd) {
            $directions[] = RepairDroid::NORTH;
            return $this->squareNorth->getDirectionsBack($directions);
        }
        if (isset($this->squareEast) && !$this->squareEast->explored && !$this->squareEast->isWall && !$this->squareEast->deadEnd) {
            $directions[] = RepairDroid::EAST;
            return $this->squareEast->getDirectionsBack($directions);
        }
        if (isset($this->squareSouth) && !$this->squareSouth->explored && !$this->squareSouth->isWall && !$this->squareSouth->deadEnd) {
            $directions[] = RepairDroid::SOUTH;
            return $this->squareSouth->getDirectionsBack($directions);
        }
        if (isset($this->squareWest) && !$this->squareWest->explored && !$this->squareWest->isWall && !$this->squareWest->deadEnd) {
            $directions[] = RepairDroid::WEST;
            return $this->squareWest->getDirectionsBack($directions);
        }
        return $directions;
    }

    private function hasUnexploredDirection(): bool
    {
        return (
            (!$this->isWall) &&
            !(
                isset($this->squareNorth)
                && isset($this->squareEast)
                && isset($this->squareSouth)
                && isset($this->squareWest)
            )
        );
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    private function getFirstUnexploredDirection(): int
    {
        if (!isset($this->squareNorth)) {
            return RepairDroid::NORTH;
        } elseif (!isset($this->squareEast)) {
            return RepairDroid::EAST;
        } elseif (!isset($this->squareSouth)) {
            return RepairDroid::SOUTH;
        } elseif (!isset($this->squareWest)) {
            return RepairDroid::WEST;
        }
        throw new Exception("Error: All directions are explored");
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    private function getDirectionsToNearestSquareWithUnexploredDirection(): array
    {
        if ($this->squareNorth->hasUnexploredDirection()) {
            return [RepairDroid::NORTH, $this->squareNorth->getFirstUnexploredDirection()];
        } elseif ($this->squareEast->hasUnexploredDirection()) {
            return [RepairDroid::EAST, $this->squareEast->getFirstUnexploredDirection()];
        } elseif ($this->squareSouth->hasUnexploredDirection()) {
            return [RepairDroid::SOUTH, $this->squareSouth->getFirstUnexploredDirection()];
        } elseif ($this->squareWest->hasUnexploredDirection()) {
            return [RepairDroid::WEST, $this->squareWest->getFirstUnexploredDirection()];
        } else {
            $this->deadEnd = true;
            return $this->directionsOutOfDeadEnd();
        }
    }

    private function directionsOutOfDeadEnd(): array
    {
        if (!($this->squareNorth->deadEnd || $this->squareNorth->isWall)) {
            return [RepairDroid::NORTH];
        } elseif (!($this->squareEast->deadEnd || $this->squareEast->isWall)) {
            return [RepairDroid::EAST];
        } elseif (!($this->squareSouth->deadEnd || $this->squareSouth->isWall)) {
            return [RepairDroid::SOUTH];
        } elseif (!($this->squareWest->deadEnd || $this->squareWest->isWall)) {
            return [RepairDroid::WEST];
        }
        throw new Exception("All Directions Explored");
    }

    public function spreadOxygen()
    {
        if ($this->isOxygen && !$this->receivedOxygen) {
            if (isset($this->squareNorth) && !$this->squareNorth->isWall && !$this->squareNorth->isOxygen) {
                $this->squareNorth->isOxygen = true;
                $this->squareNorth->receivedOxygen = true;
            }
            if (isset($this->squareEast) && !$this->squareEast->isWall && !$this->squareEast->isOxygen) {
                $this->squareEast->isOxygen = true;
                $this->squareEast->receivedOxygen = true;
            }
            if (isset($this->squareSouth) && !$this->squareSouth->isWall && !$this->squareSouth->isOxygen) {
                $this->squareSouth->isOxygen = true;
                $this->squareSouth->receivedOxygen = true;
            }
            if (isset($this->squareWest) && !$this->squareWest->isWall && !$this->squareWest->isOxygen) {
                $this->squareWest->isOxygen = true;
                $this->squareWest->receivedOxygen = true;
            }
        }
    }

    public function setDeadEnd(): void
    {
        $walls = 0;
        if ($this->hasUnexploredDirection()) {
            return;
        }
        if ($this->squareNorth->deadEnd || $this->squareNorth->isWall) {
            $walls++;
        }
        if ($this->squareSouth->deadEnd || $this->squareSouth->isWall) {
            $walls++;
        }
        if ($this->squareEast->deadEnd || $this->squareEast->isWall) {
            $walls++;
        }
        if ($this->squareWest->deadEnd || $this->squareWest->isWall) {
            $walls++;
        }
        if ($walls === 3) {
            $this->deadEnd = true;
        }
    }


}