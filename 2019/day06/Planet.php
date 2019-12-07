<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-06
 * Time: 22:49
 */

class Planet
{
    private string $name;
    private array $satellites = [];

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function addSatellite(Planet $satellite): void
    {
        $this->satellites[] = $satellite;
    }

    public function getTotalSatellites(): int
    {
        $total = count($this->satellites);
        foreach ($this->satellites as $satellite) {
            $total += $satellite->getTotalSatellites();
        }
        return $total;
    }
}