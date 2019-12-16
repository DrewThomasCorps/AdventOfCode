<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-14
 * Time: 16:22
 */

class Moles
{
    public Element $element;
    public int $moles;

    public function __construct(Element $element, int $moles)
    {
        $this->element = $element;
        $this->moles = $moles;
    }

    public static function parse(string $rawElement): array
    {
        preg_match('/(\d+) (\w+)/', $rawElement, $match);
        array_shift($match);
        return $match;
    }


}