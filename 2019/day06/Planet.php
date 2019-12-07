<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-06
 * Time: 22:49
 */

class Planet
{
    public string $name;
    private array $satellites = [];
    public Planet $parent;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function addSatellite(Planet $satellite): void
    {
        $this->satellites[$satellite->name] = $satellite;
        $satellite->addParent($this);
    }

    public function getTotalSatellites(): int
    {
        $total = count($this->satellites);
        $total += array_reduce($this->satellites, function (?int $carry, Planet $planet) {
            $carry += $planet->getTotalSatellites();
            return $carry;
        });
        return $total;
    }

    public function hasSatellite(Planet $search): bool
    {
        if (key_exists($search->name, $this->satellites)) {
            return true;
        } elseif (count($this->satellites) > 0) {
            foreach ($this->satellites as $satellite) {
                if ($satellite->hasSatellite($search)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function addParent(Planet $parent)
    {
        $this->parent = $parent;
    }
}