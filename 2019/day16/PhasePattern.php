<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 12/28/19
 * Time: 08:36
 */

class PhasePattern
{
    private array $basePattern;
    private int $returnLength;

    public function __construct(array $basePattern, int $returnLength)
    {
        $this->basePattern = $basePattern;
        $this->returnLength = $returnLength;
    }

    public function getPatternForPosition(int $position): array
    {
        $pattern = [];
        foreach ($this->basePattern as $element) {
            array_push($pattern, ...array_fill(0, $position+1, $element));
        }
        while (count($pattern) <= $this->returnLength) {
            array_push($pattern, ...$pattern);
        }
        return array_slice($pattern, 1, $this->returnLength);
    }

}