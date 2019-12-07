<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-07
 * Time: 11:20
 */

class Combinations
{
    public array $combinations = [];

    public function __construct(array $initialArray)
    {
        $this->combinations = $this->findCombinations($initialArray);
    }

    private function findCombinations(array $input): array
    {
        if (count($input) === 1) {
            return [$input];
        }
        $combinations = [];
        for ($i = 0; $i < count($input); $i++){
            $array = $input;
            $element = array_splice($array, $i, 1)[0];
            $nextCombinations = $this->findCombinations($array);
            foreach ($nextCombinations as $combination) {
                $combinations[] = [$element, ...$combination];
            }
        }
        return $combinations;
    }
}