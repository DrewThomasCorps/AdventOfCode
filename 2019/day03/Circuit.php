<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-03
 * Time: 20:11
 */
require_once __DIR__ . "/Wire.php";

class Circuit
{
    private array $wires;

    public function __construct(array $wires)
    {
        foreach ($wires as $path) {
            $this->wires[] = new Wire($path);
        }
    }

    public function getClosestIntersection(): Point
    {
        $intersections = $this->getPathIntersections();
        usort($intersections,
            fn (Point $a, Point $b) => $a->getManhattanDistance() <=> $b->getManhattanDistance()
        );
        return $intersections[0];
    }

    public function getFewestStepsAtIntersections()
    {
        $intersections = $this->getPathIntersections();
        $fewestSteps = 99999999999999999;
        foreach ($intersections as $intersection){
            $a = $this->wires[0]->getPointWithFewestSteps($intersection);
            $b = $this->wires[1]->getPointWithFewestSteps($intersection);
            $totalSteps = $a->steps + $b->steps;
            if ($totalSteps < $fewestSteps) {
                $fewestSteps = $totalSteps;
            }
        }
        return $fewestSteps;
    }

    private function getPathIntersections()
    {
        $intersections = array_intersect($this->wires[0]->points, $this->wires[1]->points);
        return $intersections;
    }



}