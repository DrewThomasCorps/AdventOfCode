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
        foreach ($intersections as $intersection) {
            echo $intersection->getManhattanDistance() . "\n";
        }
        usort($intersections,
            fn (Point $a, Point $b) => $a->getManhattanDistance() <=> $b->getManhattanDistance()
        );
        return $intersections[0];
    }

    private function getPathIntersections()
    {
        $intersections = array_uintersect($this->wires[0]->points, $this->wires[1]->points,
            fn (Point $a, Point $b) => $a <=> $b
        );
        return $intersections;
    }



}