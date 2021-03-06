<?php

require_once __DIR__ . "/Vector.php";

/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-10
 * Time: 00:05
 */
class AsteroidBelt
{
    private const ASTEROID = "#";
    private const SPACE = ".";

    private array $asteroidRows;

    public function __construct(array $asteroidRows)
    {
        foreach ($asteroidRows as $row) {
            $this->asteroidRows[] = str_split($row);
        }
    }

    public function getCoordinatesOfAsteroidWithMostVisibleAsteroids(): Coordinates
    {
        $visibleCount = 0;
        $bestAsteroidCoordinates = null;
        for ($row = 0; $row < count($this->asteroidRows); $row++) {
            for ($column = 0; $column < count($this->asteroidRows[$row]); $column++) {
                $coordinates = new Coordinates($column, $row);
                $count = $this->getVisibleAsteroidsFromCoordinates($coordinates);
                if ($count > $visibleCount) {
                    $visibleCount = $count;
                    $bestAsteroidCoordinates = $coordinates;
                }
            }
        }
        return $bestAsteroidCoordinates;
    }

    public function getVisibleAsteroidsFromCoordinates(Coordinates $searchCoordinates): ?int
    {
        if (!$this->asteroidIsAtCoordinates($searchCoordinates)) {
            return null;
        }
        $vectors = $this->getVectorsOfAllAsteroidsFromCoordinates($searchCoordinates);
        $uniqueDirections = $this->getUniqueVectorDirections($vectors);
        return count($uniqueDirections);
    }

    private function getVectorsOfAllAsteroidsFromCoordinates(Coordinates $searchCoordinates): array
    {
        $vectors = [];
        for ($row = 0; $row < count($this->asteroidRows); $row++) {
            for ($column = 0; $column < count($this->asteroidRows[$row]); $column++) {
                $coordinates = new Coordinates($column, $row);
                if ($coordinates == $searchCoordinates) {
                    continue;
                }
                if ($this->asteroidIsAtCoordinates($coordinates)) {
                    $vectors[] = new Vector($searchCoordinates, $coordinates);
                }
            }
        }
        return $vectors;
    }

    private function asteroidIsAtCoordinates(Coordinates $coordinates)
    {
        return $this->asteroidRows[$coordinates->y][$coordinates->x] === self::ASTEROID;
    }

    private function getUniqueVectorDirections(array $vectors): array
    {
        $anglesFromTopClockwise = [];
        foreach ($vectors as $vector) {
            $anglesFromTopClockwise[(string)$vector->angleFromTopClockwise] = $vector->angleFromTopClockwise;
        }
        return $anglesFromTopClockwise;
    }

    public function destroyAsteroidsFromCoordinates(Coordinates $coordinates): array
    {
        $vectors = $this->getVectorsOfAllAsteroidsFromCoordinates($coordinates);
        sort($vectors);
        $destroyedAsteroidCoordinates = [];
        $possibleAngles = $this->getUniqueVectorDirections($vectors);
        sort($possibleAngles);
        while (count($vectors) !== 0) {
            foreach ($possibleAngles as $angle) {
                $possibleVectors = array_filter($vectors, function (Vector $vector) use ($angle) {
                    return $vector->angleFromTopClockwise === $angle;
                });
                $closestVectorMagnitude = 999999999999999;
                $closestVector = null;
                foreach ($possibleVectors as $possibleVector) {
                    if ($possibleVector->magnitude < $closestVectorMagnitude) {
                        $closestVector = $possibleVector;
                    }
                }
                if (isset($closestVector)) {
                    $destroyedAsteroidCoordinates[] = $closestVector->endCoordinates;
                    foreach ($vectors as $key => $vector) {
                        if ($vector->endCoordinates === $closestVector->endCoordinates) {
                            unset($vectors[$key]);
                            break;
                        }
                    }
                }

            }
        }
        return $destroyedAsteroidCoordinates;
    }


}