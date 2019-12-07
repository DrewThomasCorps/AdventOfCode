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

    public function getOrbitalTransfersBetweenPlanets(string $startPlanet, string $endPlanet)
    {
        $startPlanet = $this->planets[$startPlanet];
        $endPlanet = $this->planets[$endPlanet];
        $potentialParents = $this->findPlanetsWithDescendents($startPlanet, $endPlanet);
        $startSteps = $this->findStepsToFirstPotentialParent($startPlanet, $potentialParents);
        $endSteps = $this->findStepsToFirstPotentialParent($endPlanet, $potentialParents);
        return $endSteps + $startSteps;
    }

    private function findPlanetsWithDescendents(Planet $startPlanet, Planet $endPlanet): array
    {
        return array_filter($this->planets, function (Planet $planet) use ($startPlanet, $endPlanet) {
            return $planet->hasSatellite($startPlanet) && $planet->hasSatellite($endPlanet);
        });
    }

    private function findStepsToFirstPotentialParent(Planet $planet, array $potentialParents): int
    {
        $steps = 0;
        $parent = $planet->parent;
        while (!in_array($parent, $potentialParents)) {
            $steps++;
            $parent = $parent->parent;
        }
        return $steps;
    }



}