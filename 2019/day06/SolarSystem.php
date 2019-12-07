<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-06
 * Time: 22:36
 */

require_once __DIR__ . "/Planet.php";

class SolarSystem
{
    private array $planets = [];

    public function getTotalOrbits(): int
    {
        return array_reduce($this->planets, function(?int $carry, Planet $planet) {
            $carry += $planet->getTotalSatellites();
            return $carry;
        });
    }

    public function addOrbits($orbits)
    {
        foreach ($orbits as $orbit) {
            [$center, $satellite] = $orbit;
            if (!array_key_exists($center, $this->planets)) {
                $this->planets[$center] = new Planet($center);
            }
            if (!array_key_exists($satellite, $this->planets)) {
                $this->planets[$satellite] = new Planet($satellite);
            }
            $this->planets[$center]->addSatellite($this->planets[$satellite]);
        }
    }

}