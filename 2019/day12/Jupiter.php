<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-12
 * Time: 13:14
 */

class Jupiter
{
    public array $moons;

    public function addMoon(Moon $moon)
    {
        $this->moons[] = $moon;
    }

    public function advanceMoons(): void
    {
        $this->changeMoonsVelocities();
        $this->changeMoonsPositions();
    }

    public function getTotalEnergy(): int
    {
        return array_reduce($this->moons, function (?int $carry, Moon $moon) {
            $carry += $moon->getTotalEnergy();
            return $carry;
        });
    }

    private function changeMoonsVelocities(): void
    {
        foreach ($this->moons as $moon) {
            foreach ($this->moons as $comparisonMoon) {
                if ($moon === $comparisonMoon) {
                    continue;
                }
                $coordinates = ["x", "y", "z"];
                foreach ($coordinates as $coordinate) {
                    $moon->{$coordinate . "Velocity"} += $comparisonMoon->{$coordinate . "Position"} <=> $moon->{$coordinate . "Position"};
                }
            }
        }
    }

    private function changeMoonsPositions(): void
    {
        foreach ($this->moons as $moon) {
            $moon->advance();
        }
    }

    public function __toString(): string
    {
        $string = "";
        for ($i = 0; $i < count($this->moons); $i++) {
            $string .= "$i{$this->moons[$i]->__toString()}.";
        }
        return $string;
    }

}